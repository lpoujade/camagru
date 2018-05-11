<?php

$gallery = function(string $url="") {
	$creations = Creation::getAll();
	header("Content-type:application/json");
	echo Creation::jsonify($creations);
};

$userPage = function($data=null) {
	$username = trim($data[0], "/");
	if (!$username)
		return "... user list ...";
	return ["content" => $username];
};

$logPage = function(string $url="") {
	header("Content-type:application/json");
	if (isset($_SESSION['is_connected']) && $_SESSION['is_connected'] == true)
		echo json_encode(['status' => '1',
			'user' => $_SESSION['user']->getusername()]);
	else
		echo json_encode(['status' => 0]);
};

$accountPage = function(string $url="") {
	if (!$_SESSION['is_connected']) {
		header('Location: /log');
		die ;
	}
	echo $_SESSION['user'];
};

/* POST */

$logUser = function(string $url="") {
	$user = User::connect($_POST['mail'], $_POST['pass']);
	if ($user === null) {
		echo json_encode(['status' => '0']);
		die ;
	}
	$_SESSION['is_connected'] = true;
	$_SESSION['user'] = $user;
	header("Content-type:application/json");
	echo json_encode(['status' => '1',
	   	'user' => $_SESSION['user']->getusername()]);
};

$newUser = function(string $url="") {
	$user = User::create($_POST['mail'], $_POST['pass'], $_POST['username']);
	if ($user === null) {
		echo json_encode(['status' => 0]);
		die;
	}
	User::save($user);
	echo json_encode(['status' => 1]);
};

