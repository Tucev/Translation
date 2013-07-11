<?php
	class Language {
		static private $languages = null;
		static function getBrowser() {
			$default = self::getDefault();
			$lang = ($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2) : $default;
			return $lang;
		}
		
		static function getSupported() {
			if(self::$languages == null) {
				$languages = glob(dirname(__DIR__).'/langs/*', GLOB_ONLYDIR);
				foreach($languages as $key => $lang) {
					$languages[$key] = preg_replace("/".preg_quote(dirname(__DIR__).'/langs/', "/")."/", "", $lang);
				}
				self::$languages = $languages;
			}
			return self::$languages;
		}
		
		static function isSupported($lang) {
			if(array_search($lang, self::getSupported()) === false) {
				return false;
			} else {
				if(file_exists(ROOT. Config::get("paths.languages") . $lang . "/" . Engine::getURI() . ".json")) {
					return true;	
				}
			}
			return false;
		}
		
		static function getDefault() {
			$default = Config::get("engine.lang.default");
			return $default;
		}
		
		static function get($lang) {
			$file = get_file_contents(ROOT. Config::get("paths.languages") . $lang . "/" . Engine::getURI() . ".json");
			$lang_object = json_decode($file);
			$lang_array["lgs"][Engine::getURI()] = object_to_array($lang_object);
			
			return $lang_array;
		}
		
		static function process($view, $lang = false) {
			$content = Engine::processView($view, self::get($lang), "/{{ ([lgs].*) }}/");
			return $content;
		}
	}