<?php
include('db_vars.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	$empty = array("status_code"=>"error","status_desc"=>$conn->connect_error,"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
} 
$dataArray = array();

$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$_POST);
$json = json_encode($masterArray);
echo($json);

$conn->close();
?>