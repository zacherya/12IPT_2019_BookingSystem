<?php
include('db_vars.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
	//header("Location: index.php?do=user.dashboard"); 
	$empty = array("status_code"=>"error","status_desc"=>"You do not have permission to view this data!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	$empty = array("status_code"=>"error","status_desc"=>$conn->connect_error,"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
} 

$query = $_GET['query'] ?? "";

if (isset($_GET['filter_yrgrp'])) {
	if ($_GET['filter_yrgrp'] != "") {
		$filterYrGrp = "AND e.year_grp = ".$_GET['filter_yrgrp'];
	} else {
		$filterYrGrp = "";
	}
} else {
	$filterYrGrp = "";
}

if (isset($_GET['filter_house'])) {
	if ($_GET['filter_house'] != "") {
		$filterHouse = "AND e.hse_name = '".$_GET['filter_house']."'";
	} else {
		$filterHouse = "";
	}
} else {
	$filterHouse = "";
}

$sql = "SELECT e.*, 
				concat(e.firstname,' (',e.firstname_pref,') ',e.middlename,' ',e.lastname) AS fullname, 
				ed.email
				FROM users e, users_auth ed 
				WHERE e.uuid = ed.uuid 
				AND (e.uuid LIKE '".$query."%' 
					OR e.firstname LIKE '".$query."%' 
					OR e.firstname_pref LIKE '".$query."%' 
					OR e.middlename LIKE '".$query."%' 
					OR e.lastname LIKE '".$query."%' 
					OR concat(e.firstname,' (',e.firstname_pref,') ',e.middlename,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname,' ',e.middlename,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname_pref,' ',e.lastname) LIKE '%".$query."%')
				".$filterYrGrp."
				".$filterHouse." 
				ORDER BY e.lastname asc";
				
if (isset($_GET['filter'])) {
	if ($_GET['filter'] == "students") {
		$sql = "SELECT e.*, 
				concat(e.firstname,' (',e.firstname_pref,') ',e.middlename,' ',e.lastname) AS fullname, 
				ed.email
				FROM users e, users_auth ed 
				WHERE e.uuid = ed.uuid 
				AND (e.uuid LIKE '".$query."%' 
					OR e.firstname LIKE '".$query."%' 
					OR e.firstname_pref LIKE '".$query."%' 
					OR e.middlename LIKE '".$query."%' 
					OR e.lastname LIKE '".$query."%' 
					OR concat(e.firstname,' (',e.firstname_pref,') ',e.middlename,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname,' ',e.middlename,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname_pref,' ',e.lastname) LIKE '%".$query."%')
				AND year_grp IS NOT NULL
				".$filterYrGrp."
				".$filterHouse."  
				ORDER BY e.lastname asc";
	} else if ($_GET['filter'] == "teachers") {
		$sql = "SELECT e.*, 
				concat(e.firstname,' (',e.firstname_pref,') ',e.middlename,' ',e.lastname) AS fullname, 
				ed.email
				FROM users e, users_auth ed 
				WHERE e.uuid = ed.uuid 
				AND (e.uuid LIKE '".$query."%' 
					OR e.firstname LIKE '".$query."%' 
					OR e.firstname_pref LIKE '".$query."%' 
					OR e.middlename LIKE '".$query."%' 
					OR e.lastname LIKE '".$query."%' 
					OR concat(e.firstname,' (',e.firstname_pref,') ',e.middlename,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname,' ',e.middlename,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname,' ',e.lastname) LIKE '%".$query."%'
					OR concat(e.firstname_pref,' ',e.lastname) LIKE '%".$query."%')
				AND year_grp IS NULL
				".$filterHouse." 
				ORDER BY e.lastname asc";
	}
}


if($result = $conn->query($sql)){

if ($result->num_rows > 0) {
    // output data of each row
	$dataArray = array();
    while($row = $result->fetch_assoc()) {
		$row['fullname'] = preg_replace('/\s+/', ' ',str_replace("() ", "", $row['fullname'])); // remove concat if no prefered name is detected
		if($row['year_grp'] === NULL) {
			$row['user_flg'] = "T";
		} else {
			$row['user_flg'] = "S";
		}
        $dataArray[] = $row;
    }
	$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
	$json = json_encode($masterArray);
	echo($json);
} else {
	$empty = array("status_code"=>"success","status_desc"=>"No students or teacher data was returned!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}
} else {
	$empty = array("status_code"=>"success","status_desc"=>"Bad query: ".$conn->error,"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}
$conn->close();
?>