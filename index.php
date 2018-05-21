<?php

require_once("config/database.php");
require_once("src/utils.php");
require_once("src/generic_classes.php");
require_once("src/routes.php");
require_once("src/data.class.php");
require_once("src/Creation.php");
require_once("src/User.php");
require_once("src/Comment.php");
require_once("src/Token.php");

if (session_start() === false) {
	echo "failed to start session ?";
	exit ;
}

if (!isset($_SESSION['post_token']))
	$_SESSION['post_token'] = bin2hex(openssl_random_pseudo_bytes(10));

$interface = function() {
	return file_get_contents('templates/home.html');
};

function htmlescape_array(array $a) {
	foreach ($a as $k => $v) {
		$a[$k] = htmlspecialchars($v);
	}
	return $a;
}

$clean_post_data = function() {
	if (!preg_match("/\/reinit_pass/", $_SERVER['REQUEST_URI'])) {
		if (empty($_POST['token']) || strcmp($_POST['token'], $_SESSION['post_token'])) {
			echo json_encode(['status' => false, 'reason' => 'bad token']);
			die ;
		}
		if (strncmp($_SERVER['HTTP_REFERER'], "http://".$_SERVER['HTTP_HOST'], strlen("http://".$_SERVER['HTTP_HOST']))) {
			echo json_encode(['status' => false, 'reason' => 'bad referer']);
			die ;
		}
	}
	foreach ($_POST as $k => $v) {
		/*
		if (strcmp($v, SQLite3::escapeString($v))) {
			echo json_encode(['status' => false, 'reason' => "invalid characters in $k field"]);
			error_log("invalid: from $v to ". SQLite3::escapeString($v));
			die ;
		}
		 */
		//error_log("POST cleaning: from $v to ". SQLite3::escapeString($v));
		//$_POST[$k] = SQLite3::escapeString(htmlspecialchars($_POST[$k]));
		$_POST[$k] = SQLite3::escapeString($v);
	}
};

$website['router'] = new Router();
$website['router']->get([
	"" => $interface,
	"gallery(/mines|/\d+)?" => $gallery,
	"comment(s)?/\d+" => $getComments,
	"log(/infos)?" => $logPage,
	"creation/delete/\d+" => $deleteCreation,
	"token/\d+/[a-z0-9]{100}" => $verifyToken,
	"forgot/\d+/[a-z0-9]{100}" => $forgot_verifyToken,
	"flush_session" => function() {
		foreach ($_SESSION as $i => $v) {
			if (strcmp($i, "post_token"))
				$_SESSION[$i] = null;
		}
		return json_encode(['status' => true, 'reason' => 'deconnected']);
	}]);
$website['router']->post([
	"log" => $logUser,
	"mod" => $modUser,
	"comment" => $writeComment,
	"like" => $likeItem,
	"forgot" => $forgotPw,
	"reinit_pass" => $reinitPw,
	"creation" => $createItem,
	"register" => "User::create"], $clean_post_data);

$website['router']->respond(str_replace("?", "", $_SERVER['REQUEST_URI']));
