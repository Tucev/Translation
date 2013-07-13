<?php
	Engine::setTitle("{{ lgs.login.title }}");
	Engine::addCSS("login.css");
	Engine::addJS("login.js");

	$data["login"] = array();
	$login = &$data["login"];
	// if any information is sent unssing the POST method to the website
	if($_POST) {
		$login["status"] = "{{ lgs.login.status.post_sent }}";
		// Check that none of the login or password are empty
		if(!empty($_POST['password']) && !empty($_POST['login'])) {
			$login["status"] = "{{ lgs.login.status.credentials_entered }}";
		} else {
			$login["status"] = "{{ lgs.login.status.no_credentials }}";
		}
		// Hash password
		// Check if it matches a record in our database
		// If it does then log the user in
		// Otherwise let the user know that something wrong happened
	}