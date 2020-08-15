<?php 
	$json = array();
	$json['ServerData'] = apc_fetch("swInfo");
	$json['ServerRules'] = apc_fetch("swRules");
	$json['BasicPlayer'] = apc_fetch("swPlayer");
	$json['cnrServerData'] = apc_fetch("cnrswInfo");
	$json['cnrServerRules'] = apc_fetch("cnrswRules");
	$json['cnrBasicPlayer'] = apc_fetch("cnrswPlayer");
	$json['LastUpdate'] = date("H:i:s d-m-Y",apc_fetch("CheckTime") );
	echo json_encode($json);
?>