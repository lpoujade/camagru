<?php

require_once("config/database.php");
require_once("src/utils.php");
require_once("src/generic_classes.php");
require_once("src/routes.php");
require_once("src/Creation.php");
require_once("src/User.php");

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
	"gallery(\/mines)?" => $gallery,
	"user" => $userPage,
	"log(\/infos)?" => $logPage,
	"creation\/delete\/\d+" => $deleteCreation,
	"flush_session" => function() {
		foreach ($_SESSION as $i => $v)
			$_SESSION[$i] = null;
		return ;
	}]);

$website['router']->post([
	"log" => $logUser,
	"creation" => $createItem,
	"register" => $newUser]);

$website['router']->respond($_SERVER['REQUEST_URI']);
