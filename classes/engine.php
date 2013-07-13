<?php
	class Engine {
		static private $uri = null;
		static private $lang = null;
		static private $content = null;
		static private $name = null;
	
		static function init() {
			self::$uri = self::parse();
			self::locale();
			if(self::$uri) {
				self::loadController();
				
				$v = self::loadView();
				$pv = self::processView($v, true);
				self::$content = Language::process($pv, self::$lang);
			}
			self::render();
		}
	
		static function locale() {
			if(self::$lang != null && Language::isSupported(self::$lang)) {
				return true;
			} else if(Language::isSupported(Language::getBrowser())) {
				self::$lang = Language::getBrowser();
			} else {
				self::$lang = Language::getDefault();
			}
		}
		static function parse() {
			$url_string = trim($_SERVER['REQUEST_URI'], '/');
			$url_array = explode('/', $url_string);
			
			if(strlen($url_array[0]) == 2) {
				$url_lang = array_shift($url_array);
				self::$lang = $url_lang;
			}
			
			if(count($url_array) >= 1) {
				return $url_array[0];
			} else {
				return false;
			}
		}
		static function getURI() {
			return self::$uri;
		}
		static function getName() {
			return self::$name;
		}
		static function setName($name) {
			self::$name = $name;
		}
		
		static function render() {
			$v = self::loadView("default");
			$data = self::getData();
			$pv = self::processView($v, $data);
			echo $pv;
		}
		
		static function getData() {
			$data = [];
			
			$data[Config::get("engine.content.placeholder")] = self::getContent();
			$data["css"] = self::getCSS();
			$data["title"] = self::getTitle();
			$data["js"] = self::getJS();
			
			return $data;
		}
		
		static function getContent() {
			if(self::$content != null) {
				return self::$content;
			} else {
				return false;
			}
		}
		
		static function getCSS() {
			$css = glob(Config::get("paths.webroot").Config::get("engine.css.paths").Config::get("engine.css.wildcard"));
			return false;	
		}
		
		static function setCSS() {
			return false;	
		}
		
		static function addCSS() {
			return false;
		}
		
		static function getTitle() {
			return false;
		}
		
		static function setTitle() {
			return false;
		}
		
		static function getJS() {
			return false;
		}
		
		static function setJS() {
			return false;
		}
		
		static function addJS() {
			return false;
		}
		
		static function loadController($name = false) {
			if($name == false) {
				$name = self::$uri;
			} else {
				self::$name = $name;
			}
			
			$path = Config::get("paths.controllers") . $name . ".php";
			if(file_exists($path)) {
				require_once($path);
			}
		}
		static function loadView($name = false) {
			if($name == false) {
				$name = self::$uri;
			}
			
			$path = Config::get("paths.views") . $name . ".html";
			if(file_exists($path)) {
				$content = get_file_contents($path);					
				return $content;
			}
			return false;
		}
		static function processView($markup, $data = true, $reg = "/{{ ((?!lgs\.)[\w|\.]*) }}/") {
			$processed = preg_replace($reg . "e", 'Data::get("$1", $data)', $markup);
			return $processed;
		}
	}