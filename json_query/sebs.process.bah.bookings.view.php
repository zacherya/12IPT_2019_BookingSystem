<?php
include('db_vars.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
	//header("Location: index.php?do=user.dashboard"); 
	$empty = array("status_code"=>"error","status_desc"=>"You do not have permission to view this data!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
}

function BlockSQLInjection($str) {
	return str_replace(array("'","\"","'",'"',"&quot;\"'","&quot;"),"",$str);
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

$intent = $_GET['intent'] ?? "setup"; //setup(d) - save
$passedBid = $_GET['booking_id'] ?? ""; //student(d) - teacher

$bookingId = explode("-",$passedBid)[0];
$recurId = explode("-",$passedBid)[1] ?? "0";


if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.process.bah.bookings.view&booking_id=$passedBid","footer_html"=>'<button type="button" class="btn btn-default" style="float:left;" data-dismiss="modal">Cancel</button><button id="deletebookingbtn" type="button" class="btn btn-danger">Delete</button><button id="updatebookingbtn" type="button" class="btn btn-warning">Update</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "display") {

	if (explode("-",$passedBid)[0] === NULL) {
		$empty = array("status_code"=>"error","status_desc"=>"The booking requested doesn't exist or was deleted!","DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
		die();
	}
	$sqlReq = "SELECT b.recur_id, b.room_code, b.hire_from, b.hire_to, r.name, r.location, r.capacity FROM bookings b, rooms r, users u, users_auth ua WHERE b.uuid=u.uuid AND b.room_code=r.room_code AND b.uuid=ua.uuid AND booking_id='$bookingId' AND recur_id=$recurId LIMIT 1";
	$sqlRec = "SELECT b.recur_id, b.room_code, b.hire_from, b.hire_to, r.name, r.location, r.capacity FROM bookings b, rooms r, users u, users_auth ua WHERE b.uuid=u.uuid AND b.room_code=r.room_code AND b.uuid=ua.uuid AND booking_id='$bookingId' AND recur_id!=$recurId AND recur_id>$recurId ORDER BY b.recur_id";
	//$sqlReq = "SELECT b.booking_id, b.recur_id, b.uuid AS organiser_uuid, IF(b.guest IS NULL,'false','true') AS guest_booking, b.guest AS guest_name, b.room_code, b.hire_from, b.hire_to, b.info, r.name, r.location, r.capacity, CONCAT(u.firstname, ' ', u.lastname) AS organiser_name, ua.email FROM bookings b, rooms r, users u, users_auth ua WHERE b.uuid=u.uuid AND b.room_code=r.room_code AND b.uuid=ua.uuid AND booking_id='$bookingId' AND recur_id=$recurId";
	//$sqlRec = "SELECT b.booking_id, b.recur_id, b.uuid AS organiser_uuid, IF(b.guest IS NULL,'false','true') AS guest_booking, b.guest AS guest_name, b.room_code, b.hire_from, b.hire_to, b.info, r.name, r.location, r.capacity, CONCAT(u.firstname, ' ', u.lastname) AS organiser_name, ua.email FROM bookings b, rooms r, users u, users_auth ua WHERE b.uuid=u.uuid AND b.room_code=r.room_code AND b.uuid=ua.uuid AND booking_id='$bookingId' AND recur_id!=$recurId AND recur_id>$recurId ORDER BY b.recur_id";

	$sqlBas = "SELECT b.booking_id, b.uuid AS organiser_uuid, IF(b.guest IS NULL,'false','true') AS guest_booking, b.guest AS guest_name, b.info, CONCAT(u.firstname, ' ', u.lastname) AS organiser_name, ua.email FROM bookings b, rooms r, users u, users_auth ua WHERE b.uuid=u.uuid AND b.room_code=r.room_code AND b.uuid=ua.uuid AND booking_id='$bookingId' AND recur_id=0 LIMIT 1";

	//Get the requested booking
	if($result = $conn->query($sqlReq)){
		if ($result->num_rows > 0) {
    		// output data of each row
			$dataArray = array();
    		while($row = $result->fetch_assoc()) {
				
				$hf=date_create($row['hire_from']);
				$ht=date_create($row['hire_to']);
				$row['fd_desc'] = date_format($hf,"d/m/Y");
				$row['td_desc'] = date_format($ht,"d/m/Y");
				$row['ft_desc'] = date_format($hf,"g:ma");
				$row['tt_desc'] = date_format($ht,"g:ma");
				
				$row['fd'] = date_format($hf,"Y-m-d");
				$row['td'] = date_format($ht,"Y-m-d");
				$row['ft'] = date_format($hf,"H:m:s");
				$row['tt'] = date_format($ht,"H:m:s");
				
				$row['capacity'] = json_decode($row['capacity']);
				
        		$dataArray = $row;
    		}
    		
    		// Get all future Occurances
    		if($result = $conn->query($sqlRec)){
				if ($result->num_rows > 0) {
    				// output data of each row
					$recurArray = array();
    				while($row = $result->fetch_assoc()) {
				
						$hf=date_create($row['hire_from']);
						$ht=date_create($row['hire_to']);
						$row['fd_desc'] = date_format($hf,"d/m/Y");
						$row['td_desc'] = date_format($ht,"d/m/Y");
						$row['ft_desc'] = date_format($hf,"g:ma");
						$row['tt_desc'] = date_format($ht,"g:ma");
						
						$row['fd'] = date_format($hf,"Y-m-d");
						$row['td'] = date_format($ht,"Y-m-d");
						$row['ft'] = date_format($hf,"H:m:s");
						$row['tt'] = date_format($ht,"H:m:s");
				
						$row['capacity'] = json_decode($row['capacity']);
				
        				$recurArray[] = $row;
    				}
    			} else {
    				$recurArray = array();
    			}
    		} else {
    			$recurArray = array();
    		}
    		
    		//Get the basic information from occurance 0
    		if($result = $conn->query($sqlBas)){
				if ($result->num_rows > 0) {
    				// output data of each row
					$basicArray = array();
					while($row = $result->fetch_assoc()) {
    					$row['guest_booking'] = json_decode($row['guest_booking']);
						
        				$basicArray = $row;
    				}
    			} else {
    				$recurArray = array();
    			}
    		} else {
    			$recurArray = array();
    		}
    		
			$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>array("root_info"=>$basicArray,"requested"=>$dataArray,"future_occurrences"=>$recurArray));
			$json = json_encode($masterArray);
			echo($json);
		} else {
			$empty = array("status_code"=>"error","status_desc"=>"The booking requested doesn't exist or was deleted!","DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Bad query: ".$conn->error,"DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}

} else if ($intent == "save") {
	
} else {
	$empty = array("status_code"=>"error","status_desc"=>"Bad intent provided","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}


$conn->close();
?>