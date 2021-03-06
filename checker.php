<?php
session_start();

require_once 'functions/apicalls.php';
require_once 'functions/telegram.php';
$config = require_once "config.php";
require_once 'functions/header.php';
require_once 'functions/footer.php';


$menu = renderMenu();
$options['nav'] = $menu;
$options['title'] = "Mood Tracker | User Checker";
$header = getHeader($options);
$footer = renderFooter();
echo $header;
?>

<div class="topspacer"></div>
<main role="main">
	<div class="container">

<?php



$tg_user = getTelegramUserData();

//check if user is logged in
if ($tg_user !== false) {

	//get my user info
	$users = json_decode(getCall($config->api_url . "users?transform=1&filter=telegramID,eq," . $tg_user["id"]),true);

	//user is not a Mood Tracker user
	if(empty($users["users"])){
		$postfields = '{
			"telegramID": "' . $tg_user["id"] . '",
			"tgusername": "' . $tg_user["username"] . '",
			"firstname": "' . $tg_user["first_name"] . '",
			"lastname": "' . $tg_user["last_name"] . '"
		}';
		//register user in Mood Tracker DB
		$register = postCall($config->api_url . "users/", $postfields);
		//show result
		if($register !== null){
			echo "User " . $tg_user["username"] . " with ID " . $tg_user["id"] . " registered as MoodTracker-User " . $register;

$alertText= urlencode("New user: " . $tg_user["username"] . " with ID " . $tg_user["id"] . " registered as Mood Tracker-User " . $register . 
' <a href="' . $config->app_url . "quick.php?doguest=" . $register . '">Guestmote</a>' . chr(10). 
            '<a href="' . $config->app_url . "quick.php?doirm=" . $register . '">promote</a>' . chr(10).
            '<a href="'. $config->app_url . "quick.php?bannew=" . $register . '">ban</a>');
			$alertURL = "https://api.telegram.org/bot" . $config->telegram['token'] . "/sendMessage?chat_id=10024714&parse_mode=HTML&text=" . $alertText;
			getCall($alertURL);


		} else {
			echo "<storng>Error</strong> Clould not tegister user.";
		}

	}
	//user is already an Mood tracker user
	foreach($users["users"] as $user){
		if($tg_user["id"] == $user["telegramID"]){
			echo "Already registred.";
		} 

	}


//show user info
	$first_name = htmlspecialchars($tg_user['first_name']);
	$last_name = htmlspecialchars($tg_user['last_name']);
	if (isset($tg_user['username'])) {
		$username = htmlspecialchars($tg_user['username']);
    	$html = "<p>You are: <a href=\"https://t.me/{$username}\">{$first_name} {$last_name}</a></p>";
	} elseif(!isset($tg_user['username']) && $tg_user !== false) {
		$html = "<p>You Are: {$first_name} {$last_name}</p>";
	}
	if (isset($tg_user['photo_url'])) {
    	$photo_url = htmlspecialchars($tg_user['photo_url']);
    	$html .= "<img src=\"{$photo_url}\">";

	}

} 
if($tg_user == false) {
	//user is not logged in
	$html = 'You need to <a href="login.php">login</a> first.';
}
echo $html;

echo $footer;
?>