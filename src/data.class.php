<?php

class Data {
	static function jsonify($c) {
		$r = [];
		foreach ($c as $v) {
			$r[] = $v->toArray();
		}
		return json_encode($r);
	}
}
