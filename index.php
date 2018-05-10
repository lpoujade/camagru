<?php

include_once("config/database.php");

include_once("src/utils.php");
include_once("src/generic_classes.php");
include_once("src/routes.php");
include_once("src/Creation.php");

if (session_start() === false) {
	echo "failed to start session ?";
	exit ;
}


$website['router'] = new Router();
$website['router']->get([
	"/^\/$/" => $gallery,
	"/^\/user$/" => $userPage,
	"/^\/log$/" => $logPage]);

$website['router']->respond($_SERVER['REQUEST_URI']);
