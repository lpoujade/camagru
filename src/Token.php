<?php

class Token {
	static function newToken($user_id) {
		global $db;
		$token = bin2hex(openssl_random_pseudo_bytes(50));
		$db->exec("insert into tokens values(NULL, '$user_id', '$token')");
		return $token;
	}

	static function verifyToken($user_id, $token) {
		global $db;

		$r = $db->query("select token from tokens where user_id = ".$user_id)->fetchAll();
		if (count($r) != 1) {
			echo "no token (or more than one token?)";
			die ;
		}
		$res = array_pop($r);
		return strcmp($token, $res['token']);
	}
}
