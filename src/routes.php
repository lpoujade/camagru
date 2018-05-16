<?php

/**** GET ****/

/*
 * /gallery(/mines|/username|/int:offset)
 * return @array of Creation items
 */
$gallery = function($url) {
	if (strstr($url, "mines")) {
		$creations = $_SESSION['user']->getCreations();
	} else if (preg_match('/gallery\/\d+/', $url)) {
		$t = explode("/", $url);
		$offset = $t[count($t) -1];
		$creations = Creation::getAll($offset);
	} else
		$creations = Creation::getAll();
	return Creation::jsonify($creations);
};

/*
 * /log(/infos)
 * return @array of user infos
 */
$logPage = function($url) {
	$infos = strstr($url, "infos");
	$user = User::getCurrentUser();
	if ($user)
		$r = ['status' => true,
			'user' => $user->getusername()];
	else
		$r = ['status' => false];

	if ($infos && $r['status'] == true) {
		$r['mail'] = $user->getmail();
	}
	return json_encode($r);
};

/*
 * /comment(s)/id
 * return @json comment id or json array of comments for creation id
 */
$getComments = function($url) {
	$ue = explode("/", $url);
	$id = $ue[count($ue) - 1];
	$c = Comment::getFor($id);
	return Comment::jsonify($c);
};

$mailMe = function($url) {
	$user = User::getCurrentUser();
	if (!$user)
		return json_encode(['status' => false, 'reason' => 'not connected']);
	$r = mail($user->getMail(), "Test mail", "Welcome to the camagru");
	return json_encode(['status' => $r]);
};

$verifyToken = function($url) {
	$url = explode("/", $url);
	$token = $url[count($url) - 1];
	$user_id = $url[count($url) - 2];
	if (!Token::verifyToken($user_id, $token)) {
		$user = new User($user_id);
		$user->setconfirmed(1);
		User::save($user);
		echo 'ok';
	}
	else
		echo 'problem';
};

/**** POST ****/

$logUser = function($url) {
	$user = User::connect($_POST['mail'], $_POST['pass']);
	if ($user === null) {
		return json_encode(['status' => false, 'reason' => 'bad credentials']);
	}
	$_SESSION['is_connected'] = true;
	$_SESSION['user'] = $user;
	return json_encode(['status' => true,
	   	'user' => $_SESSION['user']->getusername()]);
};

$newUser = function($url) {
	$user = User::create($_POST['mail'], $_POST['pass'], $_POST['username']);
	if ($user === null) {
		return json_encode(['status' => false, 'reason' => 'mail already in use']);
	}
	User::save($user);
	return json_encode(['status' => true]);
};

$createItem = function($url) {
	global $DATAS_DIR;
	$post = $_POST;
	$b64_img = substr($post['photo'], strpos($post['photo'], ",") + 1);
	$img = imagecreatefromstring(base64_decode($b64_img));
	if ($img === false) {
		echo "failed to create initial photo";
		die;
	}
	$calcs = json_decode($post['calcs']);
	foreach ($calcs as $v) {
		$b64_calc = substr($v->image, strpos($v->image, ",") + 1);
		$raw_calc = base64_decode($b64_calc);
		$im = imagecreatefromstring($raw_calc);
		$is = getimagesizefromstring($raw_calc);
		if ($im === false)
			return json_encode(['status' => false, 'reason' => 'failed to create initial photo']);
		$r = imagecopy($img, $im, $v->ofLeft, $v->ofTop, 0, 0, $is[0], $is[1]);
		if ($r === false)
			return json_encode(['status' => false, 'reason' => 'failed to copy filter']);
	}
	$user = User::getCurrentUser();
	$c = Creation::create("finally useless");
	if ($c === null || $c === false) {
		return json_encode(['status' => false, 'reason' => 'failed to create item']);
	}
	imagepng($img, $DATAS_DIR."/".$c->getid().".png");
	return json_encode(['status' => true]);
};

$deleteCreation = function($url) {
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

$modUser = function($url) {
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

$writeComment = function($url) {
	$com = new Comment();
	$post = $_POST;
	$user = User::getCurrentUser();
	if (!$user) {
		return json_encode(['status' => false, 'reason' => 'not connected']);
	}
	$com->setcreation_id($post['creation_id']);
	$com->setcontent($post['content']);
	$com->setuser_id($user->getid());
	if ($com->save() === false)
		return json_encode(['status' => false, 'reason' => 'unknow error']);
	else
		return json_encode(['status' => true]);
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
