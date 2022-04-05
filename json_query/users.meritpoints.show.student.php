<?php

include('db_vars.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	$empty = array("status_code"=>"error","status_desc"=>$conn->connect_error);
    $json = json_encode($empty);
	echo($json);
	die();
} 

if (isset($_GET['stud_code'])) {
	//Check if student is specified
	// If method not there use this year
	if (isset($_GET['year'])) {
		if (isset($_GET['totaled'])) {
			if ($_GET['totaled'] == "true") {
				$sql = "SELECT stud_code, SUM(points_awarded) AS TotalPoints FROM merit_points WHERE stud_code='".$_GET['stud_code']."' AND timestamp BETWEEN '".$_GET['year']."-01-01 00:00:00' AND '".$_GET['year']."-12-31 23:59:59' GROUP BY stud_code";
			} else {
				$sql = "SELECT * FROM merit_points WHERE stud_code='".$_GET['stud_code']."' AND timestamp BETWEEN '".$_GET['year']."-01-01 00:00:00' AND '".$_GET['year']."-12-31 23:59:59'";
			}
		} else {
			$sql = "SELECT * FROM merit_points WHERE stud_code='".$_GET['stud_code']."' AND timestamp BETWEEN '".$_GET['year']."-01-01 00:00:00' AND '".$_GET['year']."-12-31 23:59:59'";
		}
		
	} else if (isset($_GET['from'], $_GET['to'])) {
		if (isset($_GET['totaled'])) {
			if ($_GET['totaled'] == "true") {
				$sql = "SELECT stud_code, SUM(points_awarded) AS TotalPoints FROM merit_points WHERE stud_code='".$_GET['stud_code']."'  AND timestamp BETWEEN '".$_GET['from']." 00:00:00' AND '".$_GET['to']." 23:59:59' GROUP BY stud_code";
			} else {
				$sql = "SELECT * FROM merit_points WHERE stud_code='".$_GET['stud_code']."' AND timestamp BETWEEN '".$_GET['from']." 00:00:00' AND '".$_GET['to']." 23:59:59'";
			}
		} else {
			$sql = "SELECT * FROM merit_points WHERE stud_code='".$_GET['stud_code']."' AND timestamp BETWEEN '".$_GET['from']." 00:00:00' AND '".$_GET['to']." 23:59:59'";
		}
	} else {
		if (isset($_GET['totaled'])) {
			if ($_GET['totaled'] == "true") {
				$sql = "SELECT stud_code, SUM(points_awarded) AS TotalPoints FROM merit_points WHERE stud_code='".$_GET['stud_code']."' GROUP BY stud_code";
			} else {
				$sql = "SELECT * FROM merit_points WHERE stud_code='".$_GET['stud_code']."'";
			}
		} else {
			$sql = "SELECT * FROM merit_points WHERE stud_code='".$_GET['stud_code']."'";
		}

	}
	
	
} else {
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
	$resultArray["status_code"] = "success";
	$resultArray["status_desc"] = "Executed Succesfully";
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