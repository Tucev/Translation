<?php
	class Engine {
		static private $uri = null;
		static private $lang = null;
	
		static function locale() {
			echo "Taddaaa !";
		}
	
		static function parse() {
			$url_string = trim($_SERVER['REQUEST_URI'], '/');
			$url_array = explode('/', $url_string);
			$url_length = count($url_array);
			
			if($url_length == 1) {
				return $url_array[0];
			} else {
				if(strlen($url_array[0]) == 2) {
					$url_lang = array_shift($url_array);
					self::$lang = $url_lang;
					return $url_array[0];
				} else {
					return false;
				}
			}
		}
		
		static function render() {
			self::$uri = self::parse();
			var_dump(self::$uri, self::$lang);
			// load the language file
			self::locale();
			// load the "controller" file that manages the rendering template
			
			// load the template, complete it, and render it
		}
	}