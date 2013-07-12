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
	
	function get_file_contents($path) {
		ob_start();
		require($path);
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}

	require_once(ROOT . 'classes/' . 'config.php');
	
	Config::init('config/');
	
	function __autoload($class_name) {
		$name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));
		require_once(Config::get("paths.classes") . $name . ".php");
	}