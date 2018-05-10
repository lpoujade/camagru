<?php

include_once("database.php");

$datas = ['users' =>
   	"(1, 1, 'jean', 'jean@nowhere.fr', 'xwe12', 'a'),
	(2, 0, 'jane', 'hop@la', 'aaab', 'c')"];

foreach ($datas as $table => $values) {
	$c = $db->exec("insert into $table values
		$values;");
	if ($c === FALSE)
		db_error();
	else
		echo "$c rows inserted" . PHP_EOL;
}
