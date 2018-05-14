<?php

/**** GET ****/

/*
 * /gallery(/mines|/username|/int:offset)
 * return @array of Creation items
 */
$gallery = function(string $url="") {
	if (strstr($url, "mines")) {
		$creations = $_SESSION['user']->getCreations();
	} else if (preg_match('/gallery\/\d+/', $url)) {
		$t = explode("/", $url);
		$offset = $t[count($t) -1];
		$creations = Creation::getAll($offset);
	} else
		$creations = Creation::getAll();
	header("Content-type:application/json");
	return Creation::jsonify($creations);
};

/*
 * /user/username
 * return public User info
 */
$userPage = function($data=null) {
	$username = trim($data[0], "/");
	if (!$username)
		return "... user list ...";
	//return ["content" => $username];
	return $username;
};

/*
 * /log(/infos)
 * return @array of user infos
 */
$logPage = function(string $url="") {
	$infos = strstr($url, "infos");
	$user = User::getCurrentUser();
	if ($user)
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

/*
 * /comment(s)/id
 * return @json comment id or json array of comments for creation id
 */
$getComments = function(string $url) {
	$ue = explode("/", $url);
	$id = $ue[count($ue) - 1];
	$c = Comment::getFor($id);
	return Comment::jsonify($c);
};


/**** POST ****/

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
		return json_encode(['status' => false, 'reason' => 'mail already in use']);
	}
	User::save($user);
	return json_encode(['status' => true]);
};

$createItem = function(string $url="") {
	header("Content-type:application/json");
	return json_encode($_POST);
	die ;
	$c = Creation::create(SQLite3::escapeString($_FILES['file']['name']));
	if ($c === null || $c === false) {
		echo "failed to create creation";
		die ;
	}
	return Creation::jsonify([$c]);
};

$deleteCreation = function(string $url) {
	$id = explode("/", $url);
	$id = $id[count($id) - 1];
	if ($id <= 0) {
		return json_encode(['status' => false, 'bad creation id' => $id]);
	}
	$c = new Creation($id);
	$user = User::getCurrentUser();
	if (!$user)
		return json_encode(['status' => false, 'reason' => 'not connected']);
	else if ($user->getid() != $c->getuserid()) {
		return json_encode(['status' => false, 'reason' => 'bad user']);
	}
	Creation::remove($c);
	return json_encode(['status' => true]);
};

$modUser = function(string $url) {
	$post = $_POST;
	$user = User::getCurrentUser();
	if (!$user)
		return json_encode(['status' => false, 'reason' => 'not connected']);
	if (!User::checkpass($user->getid(), $post['pass']))
		return json_encode(['status' => false, 'reason' => 'bad password']);
	$user->setmail($post['mail']);
	$user->setusername($post['username']);
	if (User::save($user) === true)
		return json_encode(['status' => true]);
   	return json_encode(['status' => false, 'reason' => 'unknow']);
};

$writeComment = function(string $url) {
	$com = new Comment();
	$post = $_POST;
	$user = User::getCurrentUser();
	if (!$user) {
		return json_encode(['status' => 'not connected']);
	}
	$com->setcreation_id($post['creation_id']);
	$com->setcontent($post['content']);
	$com->setuser_id($user->getid());
	if ($com->save() === false)
		return json_encode('?');
	else
		return json_encode(['status' => 1]);
};

$likeItem = function() {
	$c = new Creation($_POST['creation_id']);
	$user = User::getCurrentUser();
	if (!$user)
		return json_encode(['status' => false, 'reason' => 'not connected']);
	$c->addLike($user->getid());
	//Creation::save($c); // || $c->save();
	return json_encode(['status' => true]);
};
