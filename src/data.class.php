<?php

class Data {
	private $db;

	public function __construct() {
		global $db;
		$this->db = $db;
	}

	public function jsonify($c) {
		$r = [];
		foreach ($c as $v) {
			$r[] = $v->toArray();
		}
		return json_encode($r);
	}
}
