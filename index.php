<?php

include_once("config/database.php");

include_once("src/utils.php");
include_once("src/generic_classes.php");
include_once("src/routes.php");
include_once("src/Creation.php");
include_once("src/User.php");

if (session_start() === false) {
	echo "failed to start session ?";
	exit ;
}

$interface = function() {
	echo file_get_contents('templates/home.html');
};

$website['router'] = new Router();
$website['router']->get([
	"" => $interface,
	"gallery" => $gallery,
	"user" => $userPage,
	"log" => $logPage,
	"account" => $accountPage,
	"flush_session" => function() {
		foreach ($_SESSION as $i => $v)
			$_SESSION[$i] = null;
		header('Location: /account');
	}]);

$website['router']->post([
	"log" => $logUser,
	"register" => $newUser]);

$website['router']->respond($_SERVER['REQUEST_URI']);
