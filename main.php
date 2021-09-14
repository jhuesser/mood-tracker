<?php
session_start();
$config = require_once 'config.php';
require_once 'functions/apicalls.php';
require_once 'functions/telegram.php';
require_once 'functions/application.php';
require_once 'functions/header.php';
require_once 'functions/footer.php';

$tg_user = getTelegramUserData();
saveSessionArray($tg_user);

$tgID = $tg_user["id"];
$firstname = $tg_user["first_name"];
$lastname = $tg_user["last_name"];
$username = $tg_user["username"];

$menu = renderMenu();
$options['nav'] = $menu;
$options['title'] = "Mood Tracker | Home";
$header = getHeader($options);
$footer = renderFooter();
echo $header;
if ($tg_user !== false) {
?>


<div class="topspacer"></div>
<main role="main">
	<div class="container">
	
				<div class="list-group">
				
  					<a href="settings.php" class="list-group-item list-group-item-action">Settings</a>
					<a href="<?php echo $config->app_url;?>checkin.php" class="list-group-item list-group-item-action">New Mood Checkin</a>
					<a href="<?php echo $config->app_url;?>report.php" class="list-group-item list-group-item-action">View Mood report</a>
				</div>



<?php


} else {
	echo '
	<div class="alert alert-danger" role="alert">
	<strong>Error.</strong> You need to <a href="login.php>login</a> first
  </div>
';
}

echo $footer;
?>