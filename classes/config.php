<?php
	function object_to_array($object) {
		$array = [];
		foreach($object as $key => $value) {
			$type = gettype($value);
			if($type == "object" || $type == "array") {
				$array[$key] = object_to_array($value);
			} else {
				$array[$key] = $value;
			}
		}
		return $array;
	}

	class Config {
		static private $path = 'config/';
		static private $wildcard = '*.json';
		static private $config = [];
	
		static private function load() {
			$config_files = glob(ROOT . self::$path . self::$wildcard);
			
			$wildcard_reg = str_replace('*', '(.*)', self::$wildcard);
			$config_reg = preg_quote(ROOT . self::$path, '/') . $wildcard_reg;
			
			foreach($config_files as $file) {
				$config = json_decode(self::loadFile($file));
				$gkey = preg_replace("/" . $config_reg . "/", '$1', $file);
				self::$config[$gkey] = object_to_array($config);
			}
		}
		
		static private function loadFile($file) {
			ob_start();
			include $file;
			$config = ob_get_contents();
			ob_end_clean();
			
			return $config;
		}
		
		static private function set($selector, $data, $config = false) {
			if($config == false) {
				$config = self::$config;
			}
			
			$query = explode(".", $selector);
			$key = array_shift($query);
			
			if(count($query) >= 1) {
				
			} else {
				
			}
		}
		
		// Thumbs up for the recursive function :)
		static public function get($selector, $config = false) {
			if($config == false) {
				$config = self::$config;
			}
			
			$query = explode(".", $selector);
			$key = array_shift($query);
			
			if(count($query) >= 1) {
				if(isset($config[$key])) {
					return self::get(implode(".", $query), $config[$key]);
				}
			} else {
				if(isset($config[$key])) {
					return $config[$key];
				}
			}
			return false;
		}
		
		static public function init($path) {
			if(isset($path) && $path != self::$path) {
				self::$path = $path;
			}
			self::load();
		}
	}