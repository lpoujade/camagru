<?php

$gallery = function(string $url="") {
	$creations = Creation::getAll();
	foreach($creations as $c) {
		$data .= render('gallery',
			['item_id' => $c->getid(),
			'name' => $c->getname(),
			'image' => $c->getimage()]);
	}
	echo render('home', ['content' => $data]);
};

$userPage = function($data=null) {
	$username = trim($data[0], "/");
	if (!$username)
		return "... user list ...";
	return ["content" => $username];
};

$logPage = function(string $url="") {
	//printData($_SERVER);
	$content = render('log', []);
	echo render('home', ['content' => $content]);
};

$accountPage = function(string $url="") {
	if (!$_SESSION['is_connected']) {
		header('Location: /log');
		die ;
	}
	echo "hi ".$_SESSION['user'];
};

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
	$new_user = new User();
	//echo json_encode([$_POST, $new_user]);
	echo json_encode($new_user);
};

