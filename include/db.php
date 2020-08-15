<?php
include_once 'config.php';
class DbConnect
{
	private $connect;
	public function __construct()
	{
		try {
			$this->connect = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}catch (PDOException $e) {
			print "Hata!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getDb()
	{
		return $this->connect;
	}
	public function closeDB()
	{
		$this->connect = NULL;
	}
}
class DbConnectLocalTops
{
	private $connect;
	public function __construct()
	{
		try {
			$this->connect = new PDO('mysql:host='.DB_HOST_LOCAL.';dbname=toptimes', DB_USER_LOCAL, DB_PASSWORD_LOCAL);
		}catch (PDOException $e) {
			print "Hata!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getDb()
	{
		return $this->connect;
	}
	public function closeDB()
	{
		$this->connect = NULL;
	}
}
class DbConnectLocalPanel
{
	private $connect;
	public function __construct()
	{
		try {
			$this->connect = new PDO('mysql:host='.DB_HOST_LOCAL.';dbname=panel', DB_USER_LOCAL, DB_PASSWORD_LOCAL,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}catch (PDOException $e) {
			print "Hata!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	public function getDb()
	{
		return $this->connect;
	}
	public function closeDB()
	{
		$this->connect = NULL;
	}
}
?>