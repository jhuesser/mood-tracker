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
	<div class="alert alert-primary" role="alert">
  The table looks best in landscape
</div>
	<div class="table-responsive">
<table class="table table-striped table-hover">
<thead>
	<tr>
		<th scope="col">Time</th> 
		<th scope="col">Title</th> 
		<th scope="col">Description</th> 
		<th scope="col">Energy Level</th>
		<th scope="col">Anxiety Level</th>
		<th scope="col">Mood Level</th>
		<th scope="col">Activities</th>
</thead>
<tbody>

<?php
// get all checkins from current user
$qryString = $config->api_url . 'checkin?transform=1&filter=userIDFK,eq,' . $_SESSION['userID'];
$myCheckins = json_decode(getCall($qryString), true);
foreach($myCheckins['checkin'] as $checkin){
	//get activities for that checkin
	$actSring = $config->api_url . "CheckinActivityReport?transform=1&filter=checkinIDFK,eq," . $checkin["checkinID"];
	$registredActivities = json_decode(getCall($actSring), true);

	echo '<tr><td>' . date('d.m.Y H:i', $checkin['timestamp']) . '</td><td>' . $checkin["title"] , '</td><td>' . $checkin["description"] . '</td><td>' . $checkin["energyLVL"] . "</td><td>" . $checkin["anxietyLVL"] . "</td><td>" . $checkin["moodLVL"] . "</td><td>";
	foreach($registredActivities['CheckinActivityReport'] as $activityReport){
		echo $activityReport['activityName'] . "<br>";
	}
	echo "</td></tr>";
}
?>
</tbody>
</table>
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