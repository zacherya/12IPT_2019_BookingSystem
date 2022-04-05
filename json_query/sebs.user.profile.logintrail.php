<?php
include('db_vars.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
	//header("Location: index.php?do=user.dashboard"); 
	$empty = array("status_code"=>"error","status_desc"=>"You do not have permission to view this data!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
}

$intent = $_GET['intent'] ?? "setup"; //setup(d) - save

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.user.profile.logintrail&presented=true","footer_html"=>'<button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else {
	$empty = array("status_code"=>"success","status_desc"=>"Bad Intent Provided","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}
?>