<?php

class Creation {
	private $id;
	private $name;
	private $image;
	private $user_id;

	public function __construct($id, $name, $image, $user_id) {
		$this->id = $id;
		$this->name = $name;
		$this->image = $image;
		$this->user_id = $user_id;
	}

	public function toArray() {
		return ['id' => $this->id,
			'name' => $this->name,
			'image' => $this->image];
	}

	public function getid() {
		return $this->id;
	}

	public function setid($value) {
		$this->id = $value;
	}

	public function getname() {
		return $this->name;
	}

	public function setname($value) {
		$this->name = $value;
	}

	public function getimage() {
		return $this->image;
	}

	public function setimage($value) {
		$this->image = $value;
	}

	public function getAll($items=5, $index=0) {
		global $db;

		$pdo_statement = $db->query("select * from creations limit $items offset $index");
		$res = $pdo_statement->fetchAll();
		$r = [];
		foreach($res as $c)
			$r[] = new Creation($c['id'], $c['creation_date'], $c['img_path'], $c['user_id']);
		return ($r);
	}

	public function jsonify(array $creations) {
		$r = [];
		foreach ($creations as $c) {
			$r[] = $c->toArray();
		};
		return json_encode($r);
	}
}
