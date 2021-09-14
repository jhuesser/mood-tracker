<?php
function saveSessionArray($tg_user){
	global $config;
	$irmarray = json_decode(getCall($config->api_url . "users?transform=1&filter=telegramID,eq," . $tg_user['id']),true);	
	$_SESSION['tgID'] = $tg_user['id'];
	foreach($irmarray['users'] as $irm_user){
	$_SESSION['userID'] = $irm_user['userID'];
	}
	$_SESSION['username'] = $tg_user['username'];
	$_SESSION['firstname'] = $tg_user['first_name'];
	$_SESSION['lastname'] = $tg_user['last_name'];
}