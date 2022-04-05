<?php

include('db_vars.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	$empty = array("status"=>$conn->connect_error);
    $json = json_encode($empty);
	echo($json);
	die();
} 

if (isset($_GET['stud_code'])) {
	//Check if student is specified
	// If method not there use this year
	if (isset($_GET['year'])) {
		
	} else if (isset($_GET['from'], $_GET['to'])) {
		
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Filtration data missing, supply either the year or period.","DATA"=>array());
		$json = json_encode($empty);
		echo($json);
		die();
	}
	
	
} else if (isset($_GET['stud_code'])) {
	$empty = array("status_code"=>"error","status_desc"=>"Student Code wasn't specified","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	$resultArray = array();
	$dataArray = array();
	$resultArray["status"] = "successful";
	if (isset($_GET['kvs'])) {
		$resultArray["showing_kvs"] = $_GET['kvs'];
	} else {
		$resultArray["showing_kvs"] = "Showing all KVS Data";
	}
    while($row = $result->fetch_assoc()) {
        $dataArray[] = $row;
    }
	$resultArray["DATA"] = $dataArray;
	$json = json_encode($resultArray);
	echo($json);
} else {
	$empty = array("status"=>"No data was returned");
    $json = json_encode($empty);
	echo($json);
}
$conn->close();
?>