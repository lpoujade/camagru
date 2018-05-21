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

		$r = $db->query("select token from tokens where user_id = $user_id and token = '$token'")->fetchAll();
		if (count($r) != 1) {
			echo "This link seems already used.";
			die ;
		}
		$res = array_pop($r);
		return strcmp($token, $res['token']);
	}

	static function deleteToken($token) {
		global $db;

		$r = $db->exec("delete from tokens where token = '$token'");
	}

	static function exists($user_id) {
		global $db;

		$r = $db->query("delete from tokens where user_id = '$user_id'")->fetchAll();
		if (count($r) >= 1)
			return true;
		return false;
	}
}
