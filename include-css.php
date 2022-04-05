<?php
header('Content-Type: text/css');

session_start();

$noLogin = array("ui.login","portal.zaloading");

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	if (isset($_GET['do'])) {
		if (in_array($_GET['do'], $noLogin)) {
			//allow access to resource
		} else {
			http_response_code(401);
    		echo("/* ACCESS TO STYLE SHEET DENIED */");
    		exit;
		}
	} else {
		http_response_code(401);
    	echo("/* ACCESS TO STYLE SHEET DENIED */");
    	exit;
	}
	
}

//CHECK if do query is present
if (isset($_GET['do'])) {
	
	//CHECK if query from do exist
	// DO queries exist in separate .php files and are included in the master remote-json.php file
	$filename = 'style/'.$_GET['do'].'.css';
	if (file_exists($filename)) {
		include('style/'.$_GET['do'].'.css');
	} else {
		http_response_code(404);
		echo("/* QUERIED SCRIPT COULD NOT BE FOUND */");
	}
} else {
	http_response_code(404);
	echo("/* NO QUERY SPECIFIED */");
}


?>