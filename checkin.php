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
$options['title'] = "Mood Tracker | new checkin";
$header = getHeader($options);
$footer = renderFooter();

echo $header;

// Form handler
if(isset($_GET['add'])){
	$title = htmlspecialchars($_POST['title'], ENT_QUOTES);
	$startdate= strtotime($_POST['startdate']);
	$desc = htmlspecialchars($_POST['description'], ENT_QUOTES);
	$userID = $_SESSION['userID'];
	$engLvl = htmlspecialchars($_POST['energyLvl'], ENT_QUOTES);
	$anLvl = htmlspecialchars($_POST['anxietyLvl'], ENT_QUOTES);
	$feeling = htmlspecialchars($_POST['moodLvl'], ENT_QUOTES);
	$regActivities = $_POST['activities'];

	// register checkin
	$postfields = "{\n\t\"title\": \"$title\", \n\t\"timestamp\": \"$startdate\",\n\t\"description\": \"$desc\",\n\t\"energyLVL\": \"$engLvl\",\n\t\"anxietyLVL\": \"$anLvl\",\n\t\"moodLVL\": \"$feeling\",\n\t\"userIDFK\": \"$userID\"\n\t\n}";
	$checkinID =  postCall($config->api_url . "checkin", $postfields);

	if(is_numeric($checkinID)){
		foreach($regActivities as $act) {
			$postfields = "{\n\t\"checkinIDFK\": \"$checkinID\",\n\t\"activityIDFK\": \"$act\"\n\t\n}";
			$ActivityMapperID = postCall($config->api_url . "checkinHasActivity", $postfields);
			if(!is_numeric($ActivityMapperID)){
				$actFail = true;
			}
		}
		$saved = true;
	} else {
		$checkFail = true;
	}
}
?>

<div class="topspacer"></div>
<main role="main">
	<div class="container">

<?php

if($actFail){
	echo '
		<div class="alert alert-danger" role="alert">
		<strong>Error.</strong> There was an error saving one or more activity.
		  </div>
	';
}
if ($checkFail){
	echo '
		<div class="alert alert-danger" role="alert">
		<strong>Error.</strong> There was an error saving your check-in
		  </div>
	';
}

if($saved){
	echo '
		<div class="alert alert-success" role="alert">
		<strong>Success.</strong> Your check-in was saved
		  </div>
	';
}

$tg_user = getTelegramUserData();
saveSessionArray($tg_user);

if ($tg_user !== false) {
	$activityURL = $config->api_url . "activities?transform=1&order=activityID";
	$activities = json_decode(getCall($activityURL), true);
	?>
	<h1>New Mood checkin</h1>
	<form action="?add=1" method="POST">
		<div class="form-group">
	  		<label for="startdate">Checkin time</label>
			<input type="datetime-local" class="form-control" name="startdate" id="startdate" value="<?php echo $now->format('Y-m-d\TH:i');?>" placeholder="<?php echo $now->format('Y-m-d H:i:s');?>" require_onced>
	  	</div>
	  	<div class="form-group">
	  		<label for="title">Checkin title</label>
			<input type="text" class="form-control" name="title" id="title" placeholder="If you'd like, add a title for this checkin">
	  	</div>
		<div class="form-group">
			<label for="energyLvl" class="form-label">Energy Level</label>
			<input type="range" class="form-range" min="0" max="10" step="1" name="energyLvl" id="energyLvl">
		</div>
		<div class="form-group">
			<label for="anxietyLvl" class="form-label">Anxiety Level</label>
			<input type="range" class="form-range" min="0" max="10" step="1" name="anxietyLvl" id="anxietyLvl">
		</div>
		<div class="form-group">
			<label for="moodLvl" class="form-label">Mood Level</label>
			<input type="range" class="form-range" min="0" max="10" step="1" name="moodLvl" id="moodLvl">
		</div>
		<div class="form-group">
			<label for="activites" class="form-label">Activities</label>
			<select class="form-select" name="activities[]" multiple aria-label="Activities">
				<?php
				foreach($activities["activities"] as $activity){
					echo '<option value="' . $activity["activityID"] . '">' . $activity['activityName'] . '</option>';
				}
				?>
			</select>
		</div>
	  	<div class="form-group">
	  		<label for="description">Checkin Description</label>
	  		<textarea class="form-control" name="description" id="description" rows="3"></textarea>
	  	</div>	
		<button type="submit" class="btn btn-warning">Submit</button>
	
	</form>
</div><?php
	}
	else {
		echo '
		<div class="alert alert-danger" role="alert">
		<strong>Error.</strong> You need to <a href="https://mood-tracker.ch/login.php">login</a> first.
		  </div>
	';
	}
	
	echo $footer;
	?>