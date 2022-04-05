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

$uuid = $_SESSION['uuid'] ?? "";

$ll = $_GET['lazyload'] ?? false;
if (json_decode($ll) == true) {
	$lazyload = " LIMIT 12";
} else {
	$lazyload = "";
}

$sql = "SELECT login_ts AS timestamp, login_flg AS status, user_agent, remote_addr AS ip_address, referrer, seen AS presented FROM auth_history WHERE uuid='$uuid' ORDER BY timestamp desc".$lazyload;

if($result = $conn->query($sql)){

	if ($result->num_rows > 0) {
   		// output data of each row
		$dataArray = array();
    	while($row = $result->fetch_assoc()) {
    		$row['presented'] = (bool) $row['presented'];
        	$dataArray[] = $row;
    	}
    		$totalCountSql = "SELECT COUNT(*) AS count FROM auth_history WHERE uuid='$uuid' ORDER BY login_ts desc";
			$totalCount = 0;
			$res = $conn->query($totalCountSql);
			while($row = $res->fetch_assoc()) {
				$totalCount = $row['count'];
			} 
			
		$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray,"showing_records"=>COUNT($dataArray),"total_records"=>json_decode($totalCount));
		$json = json_encode($masterArray);
		echo($json);
		
		$doUpdate = $_GET['presented'] ?? false;
		if (json_decode($doUpdate) == true) {
			$updateSeen = "UPDATE auth_history SET seen=true WHERE uuid='$uuid'";
			$conn->query($updateSeen);
		}
		
		// Delete all login history for all users if the historic item is either (SEEN and a FAILED attempt OR the login attempt was a SUCCESS) 
		//		and the current date take a week is greater than the timestamp of the login item
		$removeSeen = "DELETE FROM auth_history WHERE ((seen=true AND login_flg='F') OR (login_flg='S')) AND (login_ts < DATE_ADD(NOW(), INTERVAL -1 WEEK))";
		$conn->query($removeSeen);
		
		
	} else {
		$empty = array("status_code"=>"success","status_desc"=>"No login history for the current user!","DATA"=>array());
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