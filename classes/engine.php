<?php
	class Engine {
		static private $uri = null;
		static private $lang = null;
	
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
		
		static function render($nestedContent) {
			self::loadTemplate();
		}
		
		static function init() {
			self::$uri = self::parse();
			self::locale();
			if(self::$uri) {
				$c = self::loadController();
				
				$t = self::loadTemplate();
				$pt = self::processTemplate($t);
			}
			self::render($pt);
		}
	}