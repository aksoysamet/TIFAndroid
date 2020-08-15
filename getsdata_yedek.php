<?php 
	require('server_data.php');
	$json = array();
	$aInformation = array();
	$aServerRules = array();
	$aBasicPlayer = array();
	$lasttime = apc_fetch("CheckTime");
	if($lasttime === false)
	{
		apc_store('CheckTime', time ());
		try
		{
			CheckServerData();
		}
		catch (QueryServerException $pError)
		{
			echo 'Server is offline<br>';
			die();
		}
	}else
	{		
		if((time() - $lasttime) > 30)
		{
			try
			{
				CheckServerData();
				apc_store('CheckTime', time ());
				$lasttime = time();
			}
			catch (QueryServerException $pError)
			{
				if((time() - $lasttime) > 120)
				{
					echo 'Server is offline<br>';
					die();
				}else
				{
					$aInformation = apc_fetch("swInfo");
					$aServerRules = apc_fetch("swRules");
					$aBasicPlayer = apc_fetch("swPlayer");
				}				
			}
		}else
		{
			$aInformation = apc_fetch("swInfo");
			$aServerRules = apc_fetch("swRules");
			$aBasicPlayer = apc_fetch("swPlayer");
		}
	}
	
	function CheckServerData()
	{
		global $aInformation,$aServerRules,$aBasicPlayer;
		$serverIP = 'server.turkibiza.net';
		$serverPort = 7777;
		$rQuery = new QueryServer( $serverIP, $serverPort );
		$aInformation = $rQuery->GetInfo( );
		$aServerRules = $rQuery->GetRules( );
		$aBasicPlayer = $rQuery->GetPlayers( );
		$aInformation['Hostname'] = '••[0.3.7]•• Turk•Ibiza•Server®™•24/7 •••DM•••';
		$aInformation['Gamemode'] = 'TurkIbiza••Freeroam••Stunt••DM';
		$aInformation['Language'] = 'Turkce - English';
		$rQuery->Close( );
		apc_store('swInfo', $aInformation);
		apc_store('swRules', $aServerRules);
		apc_store('swPlayer', $aBasicPlayer);
	}
	
	$json['ServerData'] = $aInformation;
	$json['ServerRules'] = $aServerRules;
	$json['BasicPlayer'] = $aBasicPlayer;
	$json['LastUpdate'] = date("h:i:s d-m-Y",$lasttime );
	echo json_encode($json);
?>