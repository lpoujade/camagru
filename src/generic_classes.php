<?php

class Template {
	protected $file;
	protected $values = array();

	public function __construct($file) {
		$this->file = $file;
	}

	public function set($key, $value) {
		$this->values[$key] = $value;
	}
	public function output() {
		if (!file_exists($this->file)) {
			return "Error loading template file ($this->file).";
		}
		$output = file_get_contents($this->file);
		foreach ($this->values as $key => $value) {
			$tagToReplace = "[@$key]";
			$output = str_replace($tagToReplace, $value, $output);
		}
		return $output;
	}
}

class Router {
	private $urls = ['get' => [], 'post' => []];

	/*
	public function __construct(array $urls) {
		foreach ($urls as $url => $func) {
			$this->urls[$url] = $func;
		}
	}
	 */

	public function get(array $routes) {
		foreach($routes as $url => $func) {
			$this->urls['get'][$url] = $func;
		}
	}

	public function post(array $routes) {
		foreach($routes as $url => $func) {
			$this->urls['post'][$url] = $func;
		}
	}

	public function respond($req_uri) {
		$method = "";
		if (!strcmp($_SERVER['REQUEST_METHOD'], "GET"))
			$method = "get";
		else if (!strcmp($_SERVER['REQUEST_METHOD'], "POST"))
			$method = "post";
		else {
			echo "unknow method";
			exit ;
		}
		foreach ($this->urls[$method] as $url => $func) {
			if (preg_match($url, $req_uri)) {
				return $func($req_uri);
			}
		}
		echo render('home', ['content' => '<h1>404</h1>']);
	}

	/*
	public function respond($full_req) {
		$method = "";
		if ($_SERVER['REQUEST_METHOD'] === "GET")
			$method = "get";
		else if ($_SERVER['REQUEST_METHOD'] === "POST")
			$method = "post";
		else {
			echo "unknow method";
			exit ;
		}

		$r = explode('/', $full_req);
		foreach ($r as $k => $v) {
			$r[$k] = "/".$v;
		}
		$req = ($r[1] ?: $r[0]);
		if (!array_key_exists($req, $this->urls)) {
			$ret = "<h1>404</h1><br />";
			//$ret .= $this->urls['get']["/getinfo"]($full_req);
			return $ret;
		}
		array_shift($r);
		array_shift($r);
		return $this->urls[$method][$req]($r);
	}
	 */
}
		
