<?php

class Creation {
	private $id;
	private $image;
	private $user_id;

	public function __construct($id=-1, $image="", $user_id=-1, $creation_date="") {
		global $db;
		$this->id = $id;
		if ($id != -1 && $user_id == -1) {
			$r = $db->query("select * from creations where id=$id")->fetchAll();
			$r = $r[0];
			$this->image = $r['img_path'];
			$this->user_id = $r['user_id'];
			$this->creation_date = $r['creation_date'];
		} else {
			$this->image = $image;
			$this->user_id = $user_id;
			$this->creation_date = $creation_date;
		}
	}

	public function toArray() {
		return ['id' => $this->id,
			'user_id' => $this->user_id,
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
		return $this->user_id;
	}

	public function setuserid($value) {
		$this->user_id = $value;
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
	public function getAll($offset=0, $items=5) {
		global $db;

		$pdo_statement = $db->query("select * from creations limit $items offset $offset");
		$res = $pdo_statement->fetchAll();
		$creations = [];
		foreach($res as $c)
			$creations[] = new Creation($c['id'], $c['img_path'], $c['user_id'], $c['creation_date']);
		return ($creations);
	}

	public function jsonify(array $creations) {
		$r = [];
		foreach ($creations as $c) {
			$r[] = $c->toArray();
		};
		return json_encode($r);
	}

	public function create($image) {
		$c = new Creation(-1);
		$c->setimage($image);
		$c->setuserid($_SESSION['user']->getid());
		Creation::save($c);
		return $c;
	}

	public function save(Creation $c) {
		global $db;
		if ($c->id > -1) {
			$r = $db->exec("update creations set
				id = {$c->getid()},
				img_path = '{$c->getimage()}', ''
			where id= {$c->getid()};");
		} else {
			$r = $db->exec("insert into creations values
				(NULL, '{$c->getuserid()}', '{$c->getimage()}', '');");
			$c->setid($db->lastInsertId());
		}
		return $r;
	}

	public function remove(Creation $c) {
		global $db;
		$r = $db->exec("delete from creations where id={$c->getid()}");
		return $r;
	}
}
