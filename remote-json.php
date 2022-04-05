<?php
header('Content-Type: application/json');
session_start();

$noLogin = array("user.login");

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	if (isset($_GET['do'])) {
		if (in_array($_GET['do'], $noLogin)) {
			//allow access to resource
		} else {
			http_response_code(401);
    		$empty = array("status_code"=>"error","status_desc"=>"401 Access Denied");
			$json = json_encode($empty);
			echo($json);
    		die;
		}
	} else {
		http_response_code(401);
    		$empty = array("status_code"=>"error","status_desc"=>"401 Access Denied");
			$json = json_encode($empty);
			echo($json);
    		die;
	}
	
}

//CHECK if do query is present
if (isset($_GET['do'])) {
	
	//CHECK if query from do exist
	// DO queries exist in separate .php files and are included in the master remote-json.php file
	$filename = 'json_query/'.$_GET['do'].'.php';
	if (file_exists($filename)) {
		include('json_query/'.$_GET['do'].'.php');
	} else {
		http_response_code(400);
		$empty = array("status_code"=>"error","status_desc"=>"Invalid query specified");
		$json = json_encode($empty);
		echo($json);
	}
} else {
	http_response_code(400);
	$empty = array("status_code"=>"error", "status_desc"=>"No query was specified");
    $json = json_encode($empty);
	echo($json);
}


?>