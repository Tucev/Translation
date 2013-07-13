<?php
	class Engine {
		static private $uri = null;
		static private $lang = null;
		static private $content = null;
		static private $name = null;
		static private $title = null;
		static private $css = [];
		static private $js = [];
	
		static function init() {
			self::$uri = self::parse();
			self::locale();
			self::loadCSS();
			self::loadJS();
			
			if(self::$js == null) {
				self::$js = Config::get("engine.js.files");
			}
			
			if(self::$uri) {
				require_once(self::getController());
				
				if(!isset($data) || !is_array($data)) {
					$data = true;
				}
				
				$v = "\n" . self::loadView();
				$pv = self::processView($v, $data);
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
		
		static function addCSS($file) {
			array_push(self::$css, Config::get("paths.webroot") . Config::get("engine.css.path") . $file);
		}
		static function getCSS() {
			self::loadCSS();
			$css = glob(Config::get("paths.webroot") . Config::get("engine.css.path") . Config::get("engine.css.wildcard"));
			$css = array_intersect(self::$css, $css);
			$cv = self::loadView("css");
			
			foreach($css as $key => $path) {
				$reg = "/" . preg_quote(Config::get("paths.webroot"), "/") . "/";
				$pcv = self::processView($cv, [ "path" => preg_replace($reg, "/", $path) ]);
				$css[$key] = "\n" . $pcv;
			}
			return implode("", $css);	
		}
		static function loadCSS() {
			if(self::$css == null) {
				$css = Config::get("engine.css.files");
				foreach($css as $file) {
					self::addCSS($file);
				}
			}
		}
		
		static function getTitle() {
			if(self::$title != null) { 
				$separator = Config::get("engine.title.separator");
				$title = $separator . self::$title;
				return $title;
			}
			return false;
		}
		static function setTitle($title) {
			self::$title = Language::process($title, self::$lang);
		}
		
		static function addJS($file) {
			array_push(self::$js, Config::get("paths.webroot") . Config::get("engine.js.path") . $file);
		}
		static function getJS() {
			self::loadJS();
			$js = glob(Config::get("paths.webroot") . Config::get("engine.js.path") . Config::get("engine.js.wildcard"));
			$js = array_intersect(self::$js, $js);
			$jv = self::loadView("js");
			
			foreach($js as $key => $path) {
				$reg = "/" . preg_quote(Config::get("paths.webroot"), "/") . "/";
				$pjv = self::processView($jv, [ "path" => preg_replace($reg, "/", $path) ]);
				$js[$key] = "\n" . $pjv;
			}
			return implode("", $js);
		}
		static function loadJS() {
			if(self::$js == null) {
				$js = Config::get("engine.js.files");
				foreach($js as $file) {
					self::addJS($file);
				}
			}
		}

		static function loadController($name = false) {
			require_once(self::getController($name));
		}
		static function getController($name = false) {
			if($name == false) {
				$name = self::$uri;
			} else {
				self::$name = $name;
			}
			
			$path = Config::get("paths.controllers") . $name . ".php";
			echo $path;
			if(file_exists($path)) {
				return $path;
			} else {
				return false;
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