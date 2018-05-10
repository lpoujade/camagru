<?php

class User {
	private $id;
	private $username;
	private $hash;
	private $salt;
	private $confirmed;

	public $msg = "";

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

	public function getusername() {
		return $this->username;
	}

	public function setusername($value) {
		$this->username = $value;
	}

	public function connect($mail, $pass) {
		global $db;
		$r = $db->query("select id,hash from users where mail='$mail'");
		$res = $r->fetchAll();
		if (count($r) != 1) {
			echo "fail?";
			die ;
		}
		$hash = $res[0]['hash'];
		$id = $res[0]['id'];
		if (!strcmp($hash, $pass))
			return new User($id);
		return false;
	}
}
