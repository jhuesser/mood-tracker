<?php
session_start();
if(!isset($_COOKIE['referer'])){
	setcookie('referer', $_SERVER['HTTP_REFERER']);
}
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
 	   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<link rel="stylesheet" href="global/main.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<title>Mood Tracker - Login</title>
	</head>
	<body>


	<nav class="navbar navbar-expand-lg navbar-dark bg-warning">
	<a class="navbar-brand" href="#">mood-tracker.ch</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
        <li class="nav-item active">
				<a class="nav-link" href="#">Login <span class="sr-only">(current)</span></a>
			  </li>
			
		</ul>
	</div>
</nav>
<div class="topspacer"></div>
<main role="main">
	<div class="container">

<?php

define('BOT_USERNAME', 'moodtrackerCH_bot'); // place username of your bot here

require 'functions/telegram.php';
if ($_GET['logout']) {
	setcookie('tg_user', '');
	setcookie('referer', '', time() - 3600);
  header('Location: login.php');
}

$tg_user = getTelegramUserData();
if ($tg_user !== false) {
  header('Location: index.php');
} else {
  $bot_username = BOT_USERNAME;
  $html = <<<HTML
<h1>Please login:</h1>
<script async src="https://telegram.org/js/telegram-widget.js?4" data-telegram-login="moodtrackerCH_bot" data-size="large" data-auth-url="https://mood-tracker.ch/check.php" data-request-access="write"></script>
HTML;
}


  echo <<<HTML

<center>{$html}


</center>
<div class="topspacer"></div>

<center>
<a href="https://github.com/jhuesser/mood-tracker/blob/main/README.md"><button type="button" class="btn btn-warning">Help</button></a>
<a href="https://github.com/jhuesser/mood-tracker/issues"><button type="button" class="btn btn-warning">Known issues</button></a>
<a href="https://github.com/jhuesser/mood-tracker/issues/new/"><button type="button" class="btn btn-warning">Submit new issue</button></a>
</center>

</div></div></body>
</html>
HTML;

?>