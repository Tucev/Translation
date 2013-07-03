<pre>
<?php
	define("ROOT", dirname(__DIR__) . '/');
	define("WEBROOT", ROOT . 'webroot/');
	
	require_once(ROOT . 'bootstrap.php');
	
	function get_locale($default = 'en') {
		$lang = substr(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2) || $default;
	}
	
	function get_supported_languages() {
		$languages = glob(dirname(__DIR__).'/langs/*', GLOB_ONLYDIR);
		foreach($languages as $key => $lang) {
			$languages[$key] = preg_replace("/".preg_quote(dirname(__DIR__).'/langs/', "/")."/", "", $lang);
		}
		return $languages;
	}
	
	function parse() {
		$url_st = trim($_SERVER['REQUEST_URI'], '/');
		$url = explode('/', $url_st);
		if(count($url) == 1) {
			return $url[0];
		} else {
			if(array_search($url[0], get_supported_languages())) {
			
			} else {
				
			}
		}
	}

	function render($url) {
		
		if(file_exists(langs($url))) {
			ob_start();
			include langs($url);
			$lang = ob_get_contents();
			ob_end_clean();
		}
	
		if(file_exists(classes($url))) {
			include classes($url);
		}

		if(file_exists(templates($url))) {
			ob_start();
			include templates($url);
			$content = ob_get_contents();
			ob_end_clean();
		}
		
		echo $content;
	}
	
	Engine::render();
?>
</pre>