<?php

include_once("database.php");

if ($db->exec("create table users(
	id integer primary key,
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
	creation_date text);") === FALSE) {

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

if ($db->exec("create table token(
	id integer primary key,
	user_id integer,
	token text);") === FALSE) {

	echo "failed to create token table : ".print_r($db->errorInfo(), true);
	die ;
}

echo "Database initialized !".PHP_EOL;
