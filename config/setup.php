<?php

if (file_exists(__DIR__."/../datas")) {
	echo 'Database already initialized';
	exit ;
}
mkdir(__DIR__."/../datas");
include_once("database.php");

if ($db->exec("create table users(
	id integer primary key,
	notif_mail integer,
	confirmed integer,
	username text,
	mail text,
	hash text,
	salt text);") === FALSE) {

	echo "failed to create user table : ".print_r($db->errorInfo(), true);
	die ;
}

if ($db->exec("create table creations(
	id integer primary key,
	user_id integer,
	img_path text,
	creation_date integer);") === FALSE) {

	echo "failed to create creations table : ".print_r($db->errorInfo(), true);
	die ;
}

if ($db->exec("create table comments(
	id integer primary key,
	user_id integer,
	creation_id integer,
	creation_date text,
	content text);") === FALSE) {

	echo "failed to create comments table : ".print_r($db->errorInfo(), true);
	die ;
}

if ($db->exec("create table likes(user_id int, creation_id int);") === FALSE) {
	echo "failed to create likes table : ".print_r($db->errorInfo(), true);
	die ;
}

if ($db->exec("create table tokens(
	id integer primary key,
	user_id integer,
	token text);") === FALSE) {

	echo "failed to create tokens table : ".print_r($db->errorInfo(), true);
	die ;
}

echo "Database initialized !".PHP_EOL;
