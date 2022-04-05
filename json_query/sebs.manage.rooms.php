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


$allHouses = "SELECT DISTINCT(hse_name) AS house FROM users WHERE hse_name IS NOT NULL ORDER BY hse_name asc";
$allGenders = "SELECT DISTINCT(gender) AS gender FROM users";
$allGrades = "SELECT DISTINCT(year_grp) AS year_grp FROM users WHERE year_grp IS NOT NULL ORDER BY year_grp asc";
$sqlTeacherCount = "SELECT COUNT(*) as teacher_count FROM users WHERE uuid IN (SELECT uuid FROM users WHERE year_grp IS NULL)";
$sqlStudentCount = "SELECT COUNT(*) as student_count FROM users WHERE uuid IN (SELECT uuid FROM users WHERE year_grp IS NOT NULL)";

$allHousesResult = $conn->query($allHouses);
$allGendersResult = $conn->query($allGenders);
$allGradesResult = $conn->query($allGrades);
$sqlTeacherCount = $conn->query($sqlTeacherCount);
$sqlStudentCount = $conn->query($sqlStudentCount);

$dataArray = array();

$houseArray = array();
if ($allHousesResult->num_rows > 0) {
	while($row = $allHousesResult->fetch_assoc()) {
		$houseArray[] = $row['house'];
    }
    $dataArray['houses'] = $houseArray;
} else {
	$dataArray['houses'] = array();
}

$genderArray = array();
if ($allGendersResult->num_rows > 0) {
	while($row = $allGendersResult->fetch_assoc()) {
		$genderArray[] = $row['gender'];
    }
    $dataArray['genders'] = $genderArray;
} else {
	$dataArray['genders'] = array();
}

$gradesArray = array();
if ($allGradesResult->num_rows > 0) {
	while($row = $allGradesResult->fetch_assoc()) {
		$gradesArray[] = $row['year_grp'];
    }
    $dataArray['grades'] = $gradesArray;
} else {
	$dataArray['grades'] = array();
}

if ($sqlTeacherCount->num_rows > 0) {
    // output data of each row
	
    while($row = $sqlTeacherCount->fetch_assoc()) {
		$dataArray['total_teachers'] = (int) $row['teacher_count'];
    }
}

if ($sqlStudentCount->num_rows > 0) {
    // output data of each row
	
    while($row = $sqlStudentCount->fetch_assoc()) {
		$dataArray['total_students'] = (int) $row['student_count'];
    }
}


$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
$json = json_encode($masterArray);
echo($json);

$conn->close();
?>