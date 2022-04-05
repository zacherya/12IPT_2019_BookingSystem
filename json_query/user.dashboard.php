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

$uuid = $_SESSION['uuid'] ?? "";

//Due Soon Loans
$staffUc = "SELECT COUNT(*) AS staff_upcoming FROM loans WHERE (uuid IN (SELECT uuid FROM users WHERE year_grp IS NULL)) AND ((NOW() > DATE_ADD(borrowed_ts, INTERVAL loan_days-3 DAY)) AND (DATE_ADD(borrowed_ts, INTERVAL loan_days DAY) > NOW())) AND (return_ts IS NULL)";
$studentsUc = "SELECT COUNT(*) AS students_upcoming FROM loans WHERE (uuid IN (SELECT uuid FROM users WHERE year_grp IS NOT NULL)) AND ((NOW() > DATE_ADD(borrowed_ts, INTERVAL loan_days-3 DAY)) AND (DATE_ADD(borrowed_ts, INTERVAL loan_days DAY) > NOW())) AND (return_ts IS NULL)";

//Overdue Loans
$staffOd = "SELECT COUNT(*) AS staff_overdue FROM loans WHERE (uuid IN (SELECT uuid FROM users WHERE year_grp IS NULL)) AND (NOW() > DATE_ADD(borrowed_ts, INTERVAL loan_days DAY)) AND (return_ts IS NULL)"; //staff
$studentsOd = "SELECT COUNT(*) AS students_overdue FROM loans WHERE (uuid IN (SELECT uuid FROM users WHERE year_grp IS NOT NULL)) AND (NOW() > DATE_ADD(borrowed_ts, INTERVAL loan_days DAY)) AND (return_ts IS NULL)";

//FIX BELOW SQL - hire_from and to contains time, remove in query
$bookingsToday = "SELECT COUNT(*) AS bookings_today FROM bookings WHERE (DATE(NOW()) = DATE(hire_from)) OR (DATE(NOW()) = DATE(hire_to)) OR (NOW() BETWEEN hire_from AND hire_to)"; 
$bookingsTodayYou = "SELECT COUNT(*) AS bookings_today FROM bookings WHERE uuid = '$uuid' AND guest IS NULL AND ((DATE(NOW()) = DATE(hire_from)) OR (DATE(NOW()) = DATE(hire_to)) OR (NOW() BETWEEN hire_from AND hire_to))";

$itemCount = "SELECT COUNT(*) AS items_count FROM items";
$roomCount = "SELECT COUNT(*) AS rooms_count FROM rooms";

$borrowableStaff = "SELECT COUNT(*) AS borrowable FROM items WHERE staff_alwd=true";
$borrowableStud = "SELECT COUNT(*) AS borrowable FROM items WHERE stud_alwd=true";
$userBorrowed = "SELECT COUNT(*) AS borrowed_user FROM loans WHERE uuid='$uuid'";

$bookingsTodayYouList = "SELECT b.booking_id, b.recur_id AS recurance_code, r.name, r.location, b.room_code, TIME(b.hire_from) AS fts, TIME(b.hire_to) AS tts, DATE(b.hire_from) AS fds, DATE(b.hire_to) AS tds, b.info AS memo, IF(DATE(b.hire_from) < DATE(NOW()) AND DATE(b.hire_to) > DATE(NOW()),'true','false') AS all_day, IF(DATE(DATE_ADD(b.hire_to, INTERVAL 1 DAY)) = DATE(b.hire_from),'true','false') AS nextday_from_inital_flg FROM bookings b, rooms r WHERE b.room_code = r.room_code AND uuid = '$uuid' AND guest IS NULL AND ((DATE(NOW()) = DATE(b.hire_from)) OR (DATE(NOW()) = DATE(b.hire_to)) OR (NOW() BETWEEN b.hire_from AND b.hire_to)) ORDER BY fts";

$forbiddenItemsCount = "SELECT COUNT(*) AS forbidden FROM loans l, items i WHERE i.item_code = l.item_code AND staff_alwd=false";


$query1 = $conn->query($staffUc);
$query2 = $conn->query($studentsUc);

$query3 = $conn->query($staffOd);
$query4 = $conn->query($studentsOd);

$query5 = $conn->query($bookingsToday);
$query6 = $conn->query($bookingsTodayYou);

$query7 = $conn->query($itemCount);
$query8 = $conn->query($roomCount);

$query9 = $conn->query($borrowableStaff);
$query10 = $conn->query($borrowableStud);
$query11 = $conn->query($userBorrowed);

$query12 = $conn->query($bookingsTodayYouList);

$query13 = $conn->query($forbiddenItemsCount);

$dataArray = array();

if ($query1->num_rows > 0) {
    // output data of each row
	
    while($row = $query1->fetch_assoc()) {
		$dataArray['upcoming_loans']['staff'] = (int) $row['staff_upcoming'];
    }
}
if ($query2->num_rows > 0) {
    // output data of each row
	
    while($row = $query2->fetch_assoc()) {
		$dataArray['upcoming_loans']['students'] = (int) $row['students_upcoming'];
    }
}
if ($query3->num_rows > 0) {
    // output data of each row
	
    while($row = $query3->fetch_assoc()) {
		$dataArray['overdue_loans']['staff'] = (int) $row['staff_overdue'];
    }
}
if ($query4->num_rows > 0) {
    // output data of each row
	
    while($row = $query4->fetch_assoc()) {
		$dataArray['overdue_loans']['students'] = (int) $row['students_overdue'];
    }
}
if ($query5->num_rows > 0) {
    // output data of each row
	
    while($row = $query5->fetch_assoc()) {
		$dataArray['bookings']['today'] = (int) $row['bookings_today'];
    }
}
if ($query6->num_rows > 0) {
    // output data of each row
	
    while($row = $query6->fetch_assoc()) {
		$dataArray['bookings']['today_user'] = (int) $row['bookings_today'];
    }
}

if ($query7->num_rows > 0) {
    // output data of each row
	
    while($row = $query7->fetch_assoc()) {
		$dataArray['total_items'] = (int) $row['items_count'];
    }
}
if ($query8->num_rows > 0) {
    // output data of each row
	
    while($row = $query8->fetch_assoc()) {
		$dataArray['total_rooms'] = (int) $row['rooms_count'];
    }
}

if ($query9->num_rows > 0) {
    // output data of each row
	
    while($row = $query9->fetch_assoc()) {
		$dataArray['loan_info']['staff_borrowable'] = (int) $row['borrowable'];
    }
}

if ($query10->num_rows > 0) {
    // output data of each row
	
    while($row = $query10->fetch_assoc()) {
		$dataArray['loan_info']['stud_borrowable'] = (int) $row['borrowable'];
    }
}
if ($query11->num_rows > 0) {
    // output data of each row
	
    while($row = $query11->fetch_assoc()) {
		$dataArray['loan_info']['borrowed_user'] = (int) $row['borrowed_user'];
    }
}

if ($query12->num_rows > 0) {
    // output data of each row
	
    while($row = $query12->fetch_assoc()) {
    	$row['all_day'] = json_decode($row['all_day']);
    	if ($row['memo'] == NULL) {
    		$row['memo'] = "";
    	}
    	$row['fts'] = date("g:ma", strtotime($row['fts']));
    	$row['tts'] = date("g:ma", strtotime($row['tts']));
    	$row['nextday_from_inital_flg'] = json_decode($row['nextday_from_inital_flg']);
		$dataArray['bookings']['today_user_list'][] = $row;
    }
} else {
	$dataArray['bookings']['today_user_list'] = array();
}

if ($query13->num_rows > 0) {
    // output data of each row
	
    while($row = $query13->fetch_assoc()) {
		$dataArray['loan_info']['forbidden_staff_loans'] = (int) $row['forbidden'];
    }
}


$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
$json = json_encode($masterArray);
echo($json);

$conn->close();
?>