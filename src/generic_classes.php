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
	private $pre = ['get' => null, 'post' => null];

	public function get(array $routes, $prepost = null) {
		if ($prepost)
			$this->pre['get'] = $prepost;
		foreach($routes as $url => $func) {
			$this->urls['get'][str_replace("/", "\/", $url)] = $func;
		}
	}

	public function post(array $routes, $prepost = null) {
		if ($prepost)
			$this->pre['post'] = $prepost;
		foreach($routes as $url => $func) {
			$this->urls['post'][str_replace("/", "\/", $url)] = $func;
		}
	}

	public function respond($req_uri) {
		$method = "";
		if (!strcmp($_SERVER['REQUEST_METHOD'], "GET"))
			$method = "get";
		else if (!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
			$method = "post";
		}
		else {
			echo "unknow method";
			exit ;
		}
		foreach ($this->urls[$method] as $url => $func) {
			if (preg_match("/^\/$url$/", $req_uri)) {
				if ($this->pre[$method])
					$this->pre[$method]();
				if (!strcmp($_SERVER['HTTP_ACCEPT'], "application/json"))
					header("Content-type:application/json");
				echo $func($req_uri);
				return ;
			}
		}
		http_response_code(404);
		echo json_encode('not found');
		//echo render('home', ['content' => '<h1>404</h1>']);
	}
}
