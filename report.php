<?php
session_start();
require_once 'functions/apicalls.php';
require_once 'functions/telegram.php';
$config = require_once "config.php";
$now = new datetime();

require_once 'functions/header.php';
require_once 'functions/footer.php';
require_once 'functions/application.php';

$menu = renderMenu();
$options['nav'] = $menu;
$options['title'] = "Mood Tracker | History";
$header = getHeader($options);
$footer = renderFooter();

echo $header;

?>

<div class="topspacer"></div>
<main role="main">
	<div class="container">

<?php
$tg_user = getTelegramUserData();
saveSessionArray($tg_user);

if ($tg_user !== false) {

	?>
	<h1>Check-in History</h1>
	<p class="desc">These are all your check-ins you have done through this tool. To get a statistic about your mood, check <a href="<?php echo $config->app_url; ?>statistics.php">the statistics page</a></p>


<?php

} else {
	echo '
	<div class="alert alert-danger" role="alert">
	<strong>Error.</strong> You need to <a href="' . $config->app_url . 'login.php">login</a> first.
	  </div>
';
}

echo $footer;
?>