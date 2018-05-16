<?php
$DB_DSN = 'sqlite:datas/db_camagru';
$DB_USER = null;
$DB_PASSWORD = null;
$DATAS_DIR = __DIR__."/../datas/";

$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function db_error() {
	global $db;
	echo "db error: ".PHP_EOL;
	print_r($db->errorInfo());
}
