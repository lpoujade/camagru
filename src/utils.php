<?php

function printData($data) {
	$ret = "<pre>";
	$ret .= print_r($data, true);
	$ret .= "</pre>";
	echo $ret;
}

function render($template, array $data) {
	$template_engine = new Template("templates/".$template.".html");
	//$template->set(['var' => 'my_data']);
	foreach($data as $k => $v) {
		$template_engine->set($k, $v);
	}
	return $template_engine->output();
}
