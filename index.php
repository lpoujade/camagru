<?php

require_once("config/database.php");
require_once("src/utils.php");
require_once("src/generic_classes.php");
require_once("src/routes.php");
require_once("src/data.class.php");
require_once("src/Creation.php");
require_once("src/User.php");
require_once("src/Comment.php");

if (session_start() === false) {
	echo "failed to start session ?";
	exit ;
}

$interface = function() {
	echo file_get_contents('templates/home.html');
};

$clean_post_data = function() {
	foreach ($_POST as $k => $v) {
		$_POST[$k] = SQLite3::escapeString($v);
	}
};

$website['router'] = new Router();

$website['router']->get([
	"" => $interface,
	"gallery(\/mines|\/\d+)?" => $gallery,
	"user" => $userPage,
	"comment(s)?\/\d+" => $getComments,
	"log(\/infos)?" => $logPage,
	"creation\/delete\/\d+" => $deleteCreation,
	"flush_session" => function() {
		foreach ($_SESSION as $i => $v)
			$_SESSION[$i] = null;
		return ;
	}]);

$website['router']->post([
	"log" => $logUser,
	"mod" => $modUser,
	"comment" => $writeComment,
	"like" => $likeItem,
	"creation" => $createItem,
	"register" => $newUser], $clean_post_data);

$website['router']->respond($_SERVER['REQUEST_URI']);
