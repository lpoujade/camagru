<?php

class Creation {
	private $id;
	private $image;
	private $user_id;

	public function __construct($id=-1, $image="", $user_id=-1, $creation_date="") {
		$this->id = $id;
		$this->image = $image;
		$this->user_id = $user_id;
		$this->creation_date = $creation_date;
	}

	public function toArray() {
		return ['id' => $this->id,
			'name' => $this->image,
			'image' => $this->image];
	}

	public function getid() {
		return $this->id;
	}

	public function setid($value) {
		$this->id = $value;
	}

	public function getuserid() {
		return $this->userid;
	}

	public function setuserid($value) {
		$this->userid = $value;
	}

	public function getimage() {
		return $this->image;
	}

	public function setimage($value) {
		$this->image = $value;
	}

	/*
	 * return @array of Creations
	*/
	public function getAll($items=5, $index=0) {
		global $db;

		$pdo_statement = $db->query("select * from creations limit $items offset $index");
		$res = $pdo_statement->fetchAll();
		$r = [];
		foreach($res as $c)
			$r[] = new Creation($c['id'], $c['img_path'], $c['user_id'], $c['creation_date']);
		return ($r);
	}

	public function jsonify(array $creations) {
		$r = [];
		foreach ($creations as $c) {
			$r[] = $c->toArray();
		};
		return json_encode($r);
	}

	public function create($image) {
		$c = new Creation();
		$c->setimage($image);
		$c->setuserid($_SESSION['user']->getid());
		Creation::save($c);
		return $c;
	}

	public function save(Creation $c) {
		global $db;
		$r = $db->exec("insert into creations values
		   	(NULL, '{$c->getuserid()}', '{$c->getimage()}', '');");
		$c->setid($db->lastInsertId());
		return $r;
	}
}
