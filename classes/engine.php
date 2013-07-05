<?php
	class Engine {
		static private $uri = null;
		static private $lang = null;
	
		static function init() {
			self::$uri = self::parse();
			self::locale();
			if(self::$uri) {
				self::loadController();
				
				$v = self::loadView();
				self::processView($v);
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
		
		static function render() {
			$v = self::loadView("default");
			$pv = self::processView($v);
			echo $pv;
		}
		
		static function loadController($name = false) {
			if($name == false) {
				$name = self::$uri;
			}
			
			$path = ROOT . Config::get("paths.controllers") . $name . ".php";
			if(file_exists($path)) {
				require_once($path);
			}
		}
		
		static function loadView($name = false) {
			if($name == false) {
				$name = self::$uri;
			}
			
			$path = ROOT . Config::get("paths.views") . $name . ".html";
			if(file_exists($path)) {
				$content = get_file_contents($path);					
				return $content;
			}
			return false;
		}
		
		static function processView($markup) {
			$processed = preg_replace("/{{ (.*) }}/", "", $markup);
			return $processed;
		}
	}