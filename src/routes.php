<?php

$gallery = function(string $url="") {
	$creations = Creation::getAll();
	header("Content-type:application/json");
	return Creation::jsonify($creations);
};

$userPage = function($data=null) {
	$username = trim($data[0], "/");
	if (!$username)
		return "... user list ...";
	//return ["content" => $username];
	return $username;
};

$logPage = function(string $url="") {
	header("Content-type:application/json");
	if (isset($_SESSION['is_connected']) && $_SESSION['is_connected'] == true)
		$r = json_encode(['status' => '1',
			'user' => $_SESSION['user']->getusername()]);
	else
		$r = json_encode(['status' => 0]);

	return $r;
};

/* POST */

$logUser = function(string $url="") {
	$user = User::connect($_POST['mail'], $_POST['pass']);
	if ($user === null) {
		return json_encode(['status' => '0', 'msg' => 'bad credentials']);
	}
	$_SESSION['is_connected'] = true;
	$_SESSION['user'] = $user;
	header("Content-type:application/json");
	return json_encode(['status' => '1',
	   	'user' => $_SESSION['user']->getusername()]);
};

$newUser = function(string $url="") {
	$user = User::create($_POST['mail'], $_POST['pass'], $_POST['username']);
	if ($user === null) {
		return json_encode(['status' => 0, 'msg' => 'mail already in use']);
	}
	User::save($user);
	return json_encode(['status' => 1, 'user' => $user->getusername()]);
};

$createItem = function(string $url="") {
	header("Content-type:application/json");
	$c = Creation::create($_FILES['file']['name']);
	if ($c === null || $c === false) {
		echo "fail to create creation";
		die ;
	}
	return Creation::jsonify([$c]);
};
