<?php

$gallery = function() {
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

$logPage = function() {
	echo $db['access'];
	printData($_SERVER);
	$content = render('log', []);
	echo render('home', ['content' => $content]);
};
