<?php

include_once("database.php");

$datas = ['users' =>
   	"(NULL, 1, 'jean', 'jean@nowhere.fr', 'xwe12', 'a'),
	(NULL, 0, 'jane', 'hop@la', 'aaab', 'c')",
	'creations' =>
	"(NULL, 1, 'lol', '2018-01-03 12:12:12'),
	(NULL, 1, 'hey', '2018-01-03 12:12:12'),
	(NULL, 2, 'hop', '2018-01-03 12:12:12')"];

foreach ($datas as $table => $values) {
	$c = $db->exec("insert into $table values $values;");
	if ($c === FALSE)
		db_error();
	else
		echo "$c rows inserted" . PHP_EOL;
}
