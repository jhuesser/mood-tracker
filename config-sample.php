<?php
return (object) array(
	'api_url' => 'ENTER_API_URL_HERE', //with ending slash
	'app_url' => 'ENTER_APP_URL_HERE', //with ending slash
	'app_title' => 'ENTER_APP_TITLE_HERE',
	'menu' => array(
		'Home' => 'ENTER_APP_URL_HERE/main.php',
		'Settings'=> 'ENTER_APP_URL_HERE/settings.php',
		"Checkin" => 'ENTER_APP_URL_HERE/checkin.php',
		"Report" => 'ENTER_APP_URL_HERE/report.php',
		"CustomEntry" => "CUSTOM_ENTRY_URL"
		
	),
	'telegram' => array(
		'bot' => 'BOT_USERNAME_HERE',
		'token' => 'BOT_TOKEN_HERE'
	)
	);