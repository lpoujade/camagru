<?php

$gallery = function(string $url="") {
	if (strstr($url, "mines")) {
		$c = $_SESSION['user']->getCreations();
		return Creation::jsonify($c);
	}
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
	$infos = strstr($url, "infos");
	$user = $_SESSION['user'];
	if (isset($_SESSION['is_connected']) && $_SESSION['is_connected'] == true)
		$r = ['status' => '1',
			'user' => $user->getusername()];
	else
		$r = ['status' => 0];

	header("Content-type:application/json");
	if ($infos && $r['status'] == '1') {
		$r['mail'] = $user->getmail();
	}
	return json_encode($r);
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
	$c = Creation::create($_FILES['file']['name']);
	if ($c === null || $c === false) {
		echo "failed to create creation";
		die ;
	}
	header("Content-type:application/json");
	return Creation::jsonify([$c]);
};

$deleteCreation = function(string $url) {
	$id = explode("/", $url);
	$id = $id[count($id) - 1];
	if ($id <= 0) {
		return json_encode(['bad creation id' => $id]);
	}
	$c = new Creation($id);
	if (!$_SESSION['is_connected'])
		return json_encode(['not connected']);
	else if ($_SESSION['user']->getid() != $c->getuserid()) {
		return json_encode('bad user');
	}
	Creation::remove($c);
	return json_encode('ok');
};

$modUser = function(string $url) {
	$user = User::getCurrentUser();
	return json_encode();
};
