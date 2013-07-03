<?php
	require_once(ROOT . 'classes/' . 'config.php');
	
	Config::init('config/');
	
	function __autoload($class_name) {
		$name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));
		require_once(ROOT . Config::get("paths.classes") . $name . ".php");
	}