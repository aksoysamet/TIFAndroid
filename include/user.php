<?php
include_once 'db.php';
class User{
	private $db;
	private $dbl;
	private $json = array();
	private $ip;
	public function __construct()
	{
		$this->db = new DbConnect();
		$this->dbl = new DbConnectLocalPanel();
		$this->ip = $this->get_client_ip();
	}

	public function isLoginExist($username, $password){
		$query = "select kullaniciadi,money,Deaths,Kills,Level,Animator,score,LoggedIn,LastOn,RegisteredDate,TimesOnServer,banned,Jailed,JailTime,language,savaskazandi,yariskazandi,etkkazandi,onlinet,fallkazandi,derbykazandi,skinplayer,rengim,bannedtime from oyuncular where kullaniciadi = ? AND sifre = md5(?) Limit 1";
		$deyim = $this->db->getDb()->prepare($query);
		$deyim->bindParam(1, $username);
		$deyim->bindParam(2, $password);
		if ($deyim->execute())
		{
			if($deyim->rowCount() > 0)
			{
				$this->addLoginLog($username, $this->ip, 1);
				$this->json = $deyim->fetch();
				$this->db->closeDB();
				$this->dbl->closeDB();
				return true;
			}else
			{
				$this->db->closeDB();
				$this->addLoginAttempt($this->ip);
				$this->addLoginLog($username, $this->ip, 0);
				$this->dbl->closeDB();
				return false;
			}
		}
	}
	public function loginUsers($username, $password)
	{
		if($this->ip == 'UNKNOWN')return array('success'=>'0');
		if($this->confirmIPAddress($this->ip) == 1 || $this->confirmPlayerName($username) == 1)return array('denied'=>'1','success'=>'0');
		$canUserLogin = $this->isLoginExist($username, $password);
		if($canUserLogin)
		{
			$this->json['success'] = 1;
		} else {
			$this->json['success'] = 0;
		}		
		return $this->json;
	}
	function confirmIPAddress($value) { 

		$q = "SELECT attempts, (CASE when lastlogin is not NULL and DATE_ADD(LastLogin, INTERVAL 15 MINUTE)>NOW() then 1 else 0 end) as Denied FROM LoginAttempts WHERE ip = ?"; 
		$deyim = $this->dbl->getDb()->prepare($q);
		$deyim->bindParam(1, $value);
		if ($deyim->execute())
		{
			if($deyim->rowCount() > 0)
			{
				$data = $deyim->fetch();
				if ($data["attempts"] >= 3) 
				{
					if($data["Denied"] == 1)return 1; 
					else $this->clearLoginAttempts($value);
				}else return 0;
			}else return 0;
		}
		return 1; 
	} 
	
	function confirmPlayerName($user) { 

		$q = "SELECT Count(`Success`) AS attempts FROM LoginGate WHERE `PlayerName` = ? AND `Success` = 0 AND `GateTime` > DATE_SUB(NOW(), INTERVAL 1 HOUR)"; 
		$deyim = $this->dbl->getDb()->prepare($q);
		$deyim->bindParam(1, $user);
		if ($deyim->execute())
		{
			if($deyim->rowCount() > 0)
			{
				$data = $deyim->fetch();
				if ($data["attempts"] >= 15) 
				{
					return 1;
				}else return 0;
			}else return 0;
		}
		return 1; 
	} 

	function addLoginAttempt($value) {

		//Increase number of attempts. Set last login attempt if required.

		$q = "SELECT * FROM LoginAttempts WHERE ip = ?"; 
		$deyim = $this->dbl->getDb()->prepare($q);
		$deyim->bindParam(1, $value);
		if ($deyim->execute())
		{
			if($deyim->rowCount() > 0)
			{
				$data = $deyim->fetch();
				$attempts = $data["Attempts"]+1;
				if($attempts==3) 
				{
					$q = "UPDATE LoginAttempts SET attempts = ?, lastlogin=NOW() WHERE ip = ?";
					$deyim = $this->dbl->getDb()->prepare($q);
					$deyim->bindParam(1, $attempts);
					$deyim->bindParam(2, $value);
					$deyim->execute();
				}else
				{
					$q = "UPDATE LoginAttempts SET attempts = ? WHERE ip = ?";
					$deyim = $this->dbl->getDb()->prepare($q);
					$deyim->bindParam(1, $attempts);
					$deyim->bindParam(2, $value);
					$deyim->execute();
				}
			}else{
				$q = "INSERT INTO LoginAttempts (attempts,IP,lastlogin,ispanel) values (1, ?, NOW(),2)";
				$deyim = $this->dbl->getDb()->prepare($q);
				$deyim->bindParam(1, $value);
				$deyim->execute();
			}
		}
	}

	
	function addLoginLog($pname, $ip, $success) {

		$q = "INSERT INTO LoginGate (PlayerName,IP,Success,GateTime,ispanel) values (?, ?, ?, NOW(),2)";
		$deyim = $this->dbl->getDb()->prepare($q);
		$deyim->bindParam(1, $pname);
		$deyim->bindParam(2, $ip);
		$deyim->bindParam(3, $success);
		$deyim->execute();
	}
	function clearLoginAttempts($value) {
		$q = "DELETE FROM LoginAttempts WHERE ip = ?"; 
		$deyim = $this->dbl->getDb()->prepare($q);
		$deyim->bindParam(1, $value);
		$deyim->execute();	  
	}
	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
}
?>