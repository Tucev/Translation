<?php
	define("ROOT", dirname(__DIR__) . '/');
	define("WEBROOT", ROOT . 'webroot/');
	
	require_once(ROOT . 'bootstrap.php');
	
	Engine::init();