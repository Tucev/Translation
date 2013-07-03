<?php
	// if any information is sent unssing the POST method to the website
	if($_POST) {
		echo "Okay, somebody is trying to login !";
		// Check that none of the login or password are empty
		if(!empty($_POST['password']) && !empty($_POST['login'])) {
			echo "Login and password present ... Let's keep going";
		} else {
			exit(0);
		}
		// Hash password
		// Check if it matches a record in our database
		// If it does then log the user in
		// Otherwise let the user know that something wrong happened
	}