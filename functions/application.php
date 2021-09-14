<?php
function saveSessionArray($tg_user){
	global $config;
	$irmarray = json_decode(getCall($config->api_url . "userStation?transform=1&filter=telegramID,eq," . $tg_user['id']),true);	
	$_SESSION['tgID'] = $tg_user['id'];
	foreach($irmarray['userStation'] as $irm_user){
	$_SESSION['irmID'] = $irm_user['userID'];
	$_SESSION['station'] = $irm_user['station'];
	$_SESSION['public_transport'] = $irm_user['public_transport'];
	}
	$detailarray =  json_decode(getCall($config->api_url . "users/" . $_SESSION['irmID']),true);
	$_SESSION['access'] = $detailarray['accessIDFK'];	
	$_SESSION['username'] = $tg_user['username'];
	$_SESSION['firstname'] = $tg_user['first_name'];
	$_SESSION['lastname'] = $tg_user['last_name'];
}