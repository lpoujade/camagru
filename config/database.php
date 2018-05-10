<?php
$DB_DSN = 'sqlite:db_camagru';
#$DB_USER = ...;
#$DB_PASSWORD = ...

$db = new PDO($DB_DSN, null, null);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function db_error() {
	global $db;
	echo "db error: ".PHP_EOL;
	print_r($db->errorInfo());
}
