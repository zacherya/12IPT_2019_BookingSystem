<?php
include('db_vars.php');
if ($_SESSION['manager'] == false) {
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

if (isset($_GET['query'])) {
	$query=$_GET['query'];
} else {
	$query="";
}

if (isset($_GET['terminated'])) {
	if ($_GET['terminated'] == "true") {
		$termin=true;
	} else {
		$termin=false;
	}
} else {
	$termin=false;
}

	if ($termin==true) {
		$sql = "SELECT e.*, 
						concat(e.first_name,' (',e.first_name_pref,') ',e.middle_name,' ',e.last_name) AS full_name, 
						ed.mobile_no, 
						ed.email, 
						ed.address, 
						ed.suburb, 
						ed.state, 
						ed.postcode, 
						ed.country, 
						ed.dob, 
						ed.gender 
				FROM employees e, employee_details ed 
				WHERE e.emp_code = ed.emp_code 
				AND (e.emp_code LIKE '".$query."%' 
					OR e.first_name LIKE '".$query."%' 
					OR e.first_name_pref LIKE '".$query."%' 
					OR e.middle_name LIKE '".$query."%' 
					OR e.last_name LIKE '".$query."%' 
					OR concat(e.first_name,' (',e.first_name_pref,') ',e.middle_name,' ',e.last_name) LIKE '%".$query."%') 
				AND e.emp_code IN (SELECT emp_code FROM employee_termination)
				ORDER BY e.last_name asc";
	} else {
		//$sql = "SELECT e.*, concat(e.first_name,' (',e.first_name_pref,') ',e.middle_name,' ',e.last_name) AS full_name, ed.mobile_no, ed.email, ed.address, ed.suburb, ed.state, ed.postcode, ed.country, ed.dob, ed.gender FROM employees e, employee_details ed WHERE e.emp_code = ed.emp_code AND (e.emp_code LIKE '".$_GET['query']."%' OR e.first_name LIKE '".$_GET['query']."%' OR e.first_name_pref LIKE '".$_GET['query']."%' OR e.middle_name LIKE '".$_GET['query']."%' OR e.last_name LIKE '".$_GET['query']."%' OR concat(e.first_name,' (',e.first_name_pref,') ',e.middle_name,' ',e.last_name) LIKE '%".$_GET['query']."%') AND (e.archived = false) ORDER BY e.last_name asc";
		$sql = "SELECT e.*, 
						concat(e.first_name,' (',e.first_name_pref,') ',e.middle_name,' ',e.last_name) AS full_name, 
						ed.mobile_no, 
						ed.email, 
						ed.address, 
						ed.suburb, 
						ed.state, 
						ed.postcode, 
						ed.country, 
						ed.dob, 
						ed.gender 
				FROM employees e, employee_details ed 
				WHERE e.emp_code = ed.emp_code 
				AND (e.emp_code LIKE '".$query."%' 
					OR e.first_name LIKE '".$query."%' 
					OR e.first_name_pref LIKE '".$query."%' 
					OR e.middle_name LIKE '".$query."%' 
					OR e.last_name LIKE '".$query."%' 
					OR concat(e.first_name,' (',e.first_name_pref,') ',e.middle_name,' ',e.last_name) LIKE '%".$query."%') 
				AND e.emp_code NOT IN (SELECT emp_code FROM employee_termination)
				ORDER BY e.last_name asc";
	}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	$dataArray = array();
    while($row = $result->fetch_assoc()) {
		$row['full_name'] = preg_replace('/\s+/', ' ',str_replace("() ", "", $row['full_name']));
		$row['terminated_flg'] = $termin;
        $dataArray[] = $row;
    }
	$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
	$json = json_encode($masterArray);
	echo($json);
} else {
	$empty = array("status_code"=>"error","status_desc"=>"No employee data was returned!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}
$conn->close();
?>