<?php
session_start();
header('Content-Type: text/javascript');

$noLogin = array("portal.jquery.latest","portal.zaloading","login.scripts");

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	if (isset($_GET['do'])) {
		if (in_array($_GET['do'], $noLogin)) {
			//allow access to resource
		} else {
			http_response_code(401);
    		echo('console.log("Access to the requested script (\''.$_GET['do'].'\') is denied");');
    		exit;
		}
	} else {
		http_response_code(401);
    	echo('console.log("Access to the requested script is denied");');
    	exit;
	}
	
}

//CHECK if do query is present
if (isset($_GET['do'])) {
	
	//CHECK if query from do exist
	// DO queries exist in separate .php files and are included in the master remote-json.php file
	$filename = 'javascript/'.$_GET['do'].'.php';
	if (file_exists($filename)) {
		include('javascript/'.$_GET['do'].'.php');
	} else {
		http_response_code(404);
		echo('console.log("The requested script (\''.$_GET['do'].'\') doesn\'t exist");');
	}
} else {
	http_response_code(404);
	echo('console.log("The requested script is unavailable");');
}


?>