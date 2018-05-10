<?php

$gallery = function(string $url="") {
	$creations = Creation::getAll();
	header("Content-type:application/json");
	echo Creation::jsonify($creations);
	/*
	foreach($creations as $c) {
		$data .= render('gallery',
			['item_id' => $c->getid(),
			'name' => $c->getname(),
			'image' => $c->getimage()]);
	}
	 */
};

$userPage = function($data=null) {
	$username = trim($data[0], "/");
	if (!$username)
		return "... user list ...";
	return ["content" => $username];
};

$logPage = function(string $url="") {
	if ($_SESSION['is_connected'] == true)
		echo "ok";
	else
		echo "no";
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
	if ($user === FALSE) {
		echo "bad credentials";
		die ;
	}
	$_SESSION['is_connected'] = true;
	$_SESSION['user'] = $user;
	echo "ok";
};

$newUser = function(string $url="") {
	//$new_user = new User();
};

