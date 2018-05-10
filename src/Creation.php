<?php

class Creation {
	private $id;
	private $name;
	private $image;

	public function __construct($id, $name, $image) {
		$this->id = $id;
		$this->name = $name;
		$this->image = $image;
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
		$fake[] = new Creation(12, 'godmod', 'path/to/img');
		$fake[] = new Creation(23, 'personne', 'path/to/img');
		$fake[] = new Creation(13, 'go go go', 'path/to/img');
		$fake[] = new Creation(75, 'godmod', 'path/to/img');
		return $fake;
	}

	public function jsonify(array $creations) {
		$r = [];
		foreach ($creations as $c) {
			$r[] = $c->toArray();
		};
		return json_encode($r);
	}
}
