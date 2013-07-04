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
				return true;
			}
		}
		
		static function getDefault() {
			$default = Config::get("engine.lang.default");
			return $default;
		}
	}