<?php

class User extends Data {
	private $id;
	private $notif_mail;
	private $username;
	private $hash;
	private $salt;
	private $confirmed;

	public function __construct($id = -1) {
		global $db;
		$this->id = $id;
		if ($id != -1) {
			$r = $db->query("select * from users where id=$id")->fetchAll()[0];
			$this->username = $r['username'];
			$this->mail = $r['mail'];
			$this->confirmed = $r['confirmed'];
			$this->hash = $r['hash'];
			$this->salt = $r['salt'];
			$this->notif_mail = $r['notif_mail'];
		}
	}

	public function __toString() {
		return "$this->username ($this->id, $this->mail)";
	}

	public function getid() {
		return $this->id;
	}

	public function setid($value) {
		$this->id = $value;
	}

	public function getnotif_mail() {
		return $this->notif_mail;
	}

	public function setnotif_mail($value) {
		$this->notif_mail = $value;
	}

	public function getusername() {
		return $this->username;
	}

	public function setusername($value) {
		$this->username = $value;
	}

	public function getmail() {
		return $this->mail;
	}

	public function setmail($value) {
		/* confirmation mail */
		$this->confirmed = false;
		$this->mail = $value;
	}

	public function sethash($pass) {
		/* hash pass */
		$this->salt = bin2hex(openssl_random_pseudo_bytes(2));
		$this->hash = hash('whirlpool', $pass.$this->salt);
	}

	public function gethash() {
		return $this->hash;
	}

	public function setsalt($salt) {
		$this->salt = $salt;
	}

	public function getsalt() {
		return $this->salt;
	}

	public function setconfirmed($confirmed) {
		$this->confirmed = $confirmed;
	}

	public function getconfirmed() {
		return $this->confirmed;
	}

	public function getCreations() {
		global $db;

		$res = $db->query("select * from creations where user_id={$_SESSION['user']->getid()}")->fetchAll();
		$c = [];
		foreach ($res as $r) {
			$c[] = new Creation($r['id'], $r['img_path'], $r['user_id'], $r['creation_date']);
		}
		return $c;
	}

	static function getCurrentUser() {
		if (isset($_SESSION['is_connected'])
			&& $_SESSION['is_connected'] === true
			&& $_SESSION['user'] != null)
			return $_SESSION['user'];
	}

	static function checkpass($id, $pass) {
		global $db;
		$r = $db->query("select hash,salt from users where id='$id'");
		$res = $r->fetchAll();
		if (count($res) != 1) {
			echo "fail?";
			die ;
		}
		$hash = $res[0]['hash'];
		$salt = $res[0]['salt'];
		if (!strcmp($hash, hash('whirlpool', $pass.$salt)))
			return true;
		return false;
	}

	static function connect($mail, $pass) {
		global $db;
		$id = false;
		$r = $db->query("select id from users where mail='$mail'");
		$res = $r->fetchAll();
		if (count($res) < 1)
			return null;
		$id = $res[0]['id'];
		if (User::checkpass($id, $pass) === true)
			return new User($id);
		return null;
	}

	static function checkmail($mail) {
		global $db;
		$r = $db->query("select id from users where mail='$mail'")->fetchAll();
		if (count($r) > 0)
			return false;
		return true;
	}

	static function getBy(array $datas) {
		global $db;

		$conditions = "";
		foreach ($datas as $crit => $value) {
			if (strlen($conditions > 0))
				$conditions .= " and ";
			$conditions .= $crit." = '$value'";
		}
		if (strlen($conditions) <= 1)
			return (null);
		$r = $db->query("select id from users where ".$conditions.";")->fetchAll();
		$users = [];
		foreach ($r as $v)
			$users[] = new User($v['id']);
		if (count($users) == 1)
			return array_pop($users);
		return $users;
	}

	static function create() {
		global $DATAS_DIR;
		$post = $_POST;
		$user = new User();
		if (User::checkmail($post['mail']) === false)
			return json_encode(['status' => false, 'reason' => 'mail already in use']);
		$user->setusername($post['username']);
		$user->setmail($post['mail']);
		if (strlen($post['pass']) <= 5)
			return json_encode(['status' => false, 'reason' => 'password too weak, see <a href="https://imgs.xkcd.com/comics/password_strength.png">this</a>']);
		$user->sethash($post['pass']);
		$user->setconfirmed(0);
		$user->setnotif_mail(1);
		User::save($user);
		$token = Token::newToken($user->getid());
		//error_log("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/token/".$user->getid()."/".$token);
		mail($user->getmail(), "Welcome to the camagru",
		   	"Confirm your account using this link : http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/token/".$user->getid()."/".$token);
		return json_encode(['status' => true, 'reason' => 'check your mails']);
	}

	public function saveme() {
		global $db;
		if ($this->confirmed != 1)
			$this->confirmed = 0;
		if ($this->notif_mail != 1)
			$this->notif_mail = 0;
		if ($this->id && $this->id != -1) {
			$r = $db->exec("update users set
				confirmed = $this->confirmed,
				username = '$this->username',
				hash = '$this->hash',
				salt = '$this->salt',
				mail = '$this->mail',
				notif_mail = $this->notif_mail
			where id = $this->id;");
		} else {
			$r = $db->exec("insert into users values
				(NULL, '{$this->getnotif_mail()}', '{$this->getconfirmed()}', '{$this->getusername()}', '{$this->getmail()}', '{$this->gethash()}', '{$this->getsalt()}');");
			$this->setid($db->lastInsertId());
		}
		return true;
	}

	static function save(User $c) {
		global $db;
		if ($c->confirmed != 1)
			$c->confirmed = 0;
		if ($c->notif_mail != 1)
			$c->notif_mail = 0;
		if ($c->id && $c->id != -1) {
			$r = $db->exec("update users set
				confirmed = $c->confirmed,
				username = '$c->username',
				hash = '$c->hash',
				salt = '$c->salt',
				mail = '$c->mail',
				notif_mail = $c->notif_mail
			where id = $c->id;");
		} else {
			$r = $db->exec("insert into users values
				(NULL, '{$c->getnotif_mail()}', '{$c->getconfirmed()}', '{$c->getusername()}', '{$c->getmail()}', '{$c->gethash()}', '{$c->getsalt()}');");
			$c->setid($db->lastInsertId());
		}
		return true;
	}
}
