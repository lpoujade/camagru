<?php

class Data {
	public function jsonify($c) {
		$r = [];
		foreach ($c as $v) {
			$r[] = $v->toArray();
		}
		return json_encode($r);
	}
}
