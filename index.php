<?php
session_start();

// check if maintenance is active
if(file_exists(".maintenance")){
	require_once("maintenance.php");
	die();
}

// Init application
require_once 'functions/apicalls.php';
require_once 'functions/telegram.php';
$config = require_once 'config.php';

// If user is logged in & registred & no referer => main.php if not registred => register.php if not logged in => login.php if registred, logged in & referer set => referer

$users = json_decode(getCall($config->api_url . "users?transform=1"), true);

$userids = array();
foreach($users["users"] as $user){
array_push($userids, $user["telegramID"]);
}

$tg_user = getTelegramUserData();
if ($tg_user !== false && in_array($tg_user["id"], $userids)) {
	if (isset($_COOKIE['referer']) && $_COOKIE['referer'] !=  $config->app_url . "login.php"){
		setcookie('referer', '', time() - 3600);
		header('Location: ' . $_COOKIE['referer']);
	} else {
	header('Location: main.php');
	}

}elseif ($tg_user !== false && !in_array($tg_user["id"], $userids)) {
	header('Location: checker.php');
}else{
	header('Location: login.php');
	}