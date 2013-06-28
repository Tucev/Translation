<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Translation</title>
		<link rel="stylesheet" media="screen" type="text/css" href="/css/dashboard.css"/>
	</head>
	<body>
<?php
	function template($url) {
		return dirname(__DIR__).'/templates/'.$url.'.html';
	}

	$url = trim($_SERVER['REQUEST_URI'], '/');
	
	ob_start();
	include template($url);
	$content = ob_get_contents();
	ob_end_clean();
	
	echo $content;
?>
	
	</body>
</html>