<?php
session_start();

$noLogin = array("ui.login.scripting.functions","ui.login.background","ui.login.captcha");

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	if (isset($_GET['do'])) {
		if (in_array($_GET['do'], $noLogin)) {
			//allow access to resource
		} else {
			http_response_code(401);
    		echo("<h1>Resource Extraction</h1><br><hr><h4>401-1 Access Denied due to unauthorised credentials</h4>");
    		exit;
		}
	} else {
		http_response_code(401);
    	echo("<h1>Resource Extraction</h1><br><hr><h4>401-2 Access Denied due to unauthorised credentials</h4>");
    	exit;
	}
	
}
include('db_vars.php');

//CHECK if do query is present
if (isset($_GET['do'])) {
	
	//CHECK if query from do exist
	// DO queries exist in separate .php files and are included in the master remote-json.php file
	$filename = 'resource/'.$_GET['do'].'.php';
	if (file_exists($filename)) {
		include('resource/'.$_GET['do'].'.php');
	} else {
		http_response_code(404);
        echo("<h1>Resource Extraction</h1><br><hr><h4>The file you requested couldn't be found</h4>");
		die();
	}
} else {
	http_response_code(404);
    echo("<h1>Resource Extraction</h1><br><hr><h4>The file you requested isn't valid</h4>");
	die();
}


?>