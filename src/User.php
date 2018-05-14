<?php

class User extends Data {
	private $id;
	private $notif_mail;
	private $username;
	private $hash;
	private $salt;
	private $confirmed;

	public function __construct(int $id = -1) {
		global $db;
		$this->id = $id;
		if ($id != -1) {
			$r = $db->query("select username,mail,confirmed from users where id=$id")->fetchAll()[0];
			$this->username = $r['username'];
			$this->mail = $r['mail'];
			$this->confirmed = $r['confirmed'];
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
		$this->salt = 'lol';
		$this->hash =  hash('whirlpool', $pass.$this->salt);
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

	public function getCurrentUser() {
		if (isset($_SESSION['is_connected'])
			&& $_SESSION['is_connected'] === true
			&& $_SESSION['user'] != null)
			return $_SESSION['user'];
	}

	public function checkpass($id, $pass) {
		global $db;
		$r = $db->query("select hash,salt from users where id='$id'");
		$res = $r->fetchAll();
		if (count($r) != 1) {
			echo "fail?";
			die ;
		}
		$hash = $res[0]['hash'];
		$salt = $res[0]['salt'];
		if (!strcmp($hash, hash('whirlpool', $pass.$salt)))
			return true;
		return false;
	}

	public function connect($mail, $pass) {
		global $db;
		$id = false;
		$r = $db->query("select id from users where mail='$mail'");
		$res = $r->fetchAll();
		$id = $res[0]['id'];
		if (User::checkpass($id, $pass) === true)
			return new User($id);
		return null;
	}

	public function checkmail($mail) {
		global $db;

		$r = $db->query("select id from users where mail='$mail'")->fetchAll();
		if (count($r) > 0) {
			return false;
		}
		return true;

	}

	public function create($mail, $pass, $username) {
		$user = new User();
		if (User::checkmail($mail) === false)
			return null;
		$user->setusername($username);
		$user->setmail($mail);
		$user->sethash($pass);
		$user->setconfirmed(0);
		return $user;
	}

	public function save(User $c) {
		global $db;
		if ($c->id && $c->id != -1) {
			$r = $db->exec("update users set
				id = $c->id,
				username = '$c->username',
				mail = '$c->mail'
			where id = $c->id;");
		} else {
			$r = $db->exec("insert into users values
				(NULL, '{$c->getnotif_mail()}', '{$c->getconfirmed()}', '{$c->getusername()}', '{$c->getmail()}', '{$c->gethash()}', '{$c->getsalt()}');");
			$c->setid($db->lastInsertId());
		}
		return true;
	}
}
