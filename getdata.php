<?php 
	require('server_data.php');
	$aInformation = array();
	$aServerRules = array();
	$aBasicPlayer = array();
	$lasttime = apc_fetch("CheckTime");
	//if($lasttime === false)
		$lasttime = 0;
	if((time() - $lasttime) > 28)
	{
		try
		{
			CheckServerData('server.turkibiza.net','','••[0.3.7]•• Turk•Ibiza•Server®™•24/7 •••DM•••', 'TurkIbiza••Freeroam••Stunt••DM', 'Turkce - English');
		}
		catch (QueryServerException $pError)
		{
			if((time() - $lasttime) > 120)
			{
				$aInformation['Hostname'] = '••[0.3.7]•• Turk•Ibiza•Server®™•24/7 •••DM•••';
				$aInformation['Gamemode'] = 'TurkIbiza••Freeroam••Stunt••DM';
				$aInformation['Language'] = 'Turkce - English';
				$aInformation['Players'] = 0;
				$aInformation['MaxPlayers'] = 0;
				$aInformation['Password'] = 0;
				apc_store('CheckTime', time ());
				apc_store('swInfo', $aInformation);
				apc_store('swRules', $aServerRules);
				apc_store('swPlayer', $aBasicPlayer);
			}				
		}
		try
		{
			CheckServerData('cnr.turkibiza.net','cnr');
		}
		catch (QueryServerException $pError)
		{
			if((time() - $lasttime) > 120)
			{
				$aInformation['Hostname'] = '••• [0.3.7] • TURK - IBIZA • HIRSIZ - POLIS •••';
				$aInformation['Gamemode'] = 'TurkIbiza••Freeroam••Stunt••DM';
				$aInformation['Language'] = 'TurkIbiza•TürkCnR•HırsızPolis';
				$aInformation['Players'] = 0;
				$aInformation['MaxPlayers'] = 0;
				$aInformation['Password'] = 0;
				apc_store('CheckTime', time ());
				apc_store('cnrswInfo', $aInformation);
				apc_store('cnrswRules', $aServerRules);
				apc_store('cnrswPlayer', $aBasicPlayer);
			}				
		}
	}
	function CheckServerData($serverIP,$prefix='',$default_Hostname='',$default_Gamemode='',$default_Language='')
	{
		global $aInformation,$aServerRules,$aBasicPlayer;
		$serverPort = 7777;
		$rQuery = new QueryServer($serverIP, $serverPort );
		$aInformation = $rQuery->GetInfo( );
		$aServerRules = $rQuery->GetRules( );
		$aBasicPlayer = $rQuery->GetPlayers( );
        $rQuery->Close( );
		if(!empty($default_Hostname))$aInformation['Hostname'] = $default_Hostname;
			else $aInformation['Hostname'] = str_replace(array('�','�','�','�','�','�','�','�','�','�','�','�','�'),array('•','Ğ','Ü','Ş','İ','Ö','Ç','ğ','ü','ş','ı','ö','ç'),$aInformation['Hostname']);
		if(!empty($default_Gamemode))$aInformation['Gamemode'] = $default_Gamemode;
			else $aInformation['Gamemode'] = str_replace(array('�','�','�','�','�','�','�','�','�','�','�','�','�'),array('•','Ğ','Ü','Ş','İ','Ö','Ç','ğ','ü','ş','ı','ö','ç'),$aInformation['Gamemode']);
		if(!empty($default_Language))$aInformation['Language'] = $default_Language;
			else $aInformation['Language'] = str_replace(array('�','�','�','�','�','�','�','�','�','�','�','�','�'),array('•','Ğ','Ü','Ş','İ','Ö','Ç','ğ','ü','ş','ı','ö','ç'),$aInformation['Language']);	
        apc_store('CheckTime', time ());
		apc_store($prefix.'swInfo', $aInformation);
		apc_store($prefix.'swRules', $aServerRules);
		apc_store($prefix.'swPlayer', $aBasicPlayer);
	}
?>