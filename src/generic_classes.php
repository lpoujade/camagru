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
			if (preg_match("/^\/$url$/", $req_uri)) {
				return $func($req_uri);
			}
		}
		http_response_code(404);
		echo render('home', ['content' => '<h1>404</h1>']);
	}
}
		
