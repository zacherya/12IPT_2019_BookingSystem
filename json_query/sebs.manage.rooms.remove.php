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
$rc = $_GET['room_code'] ?? "errr8482830294875";

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"No Modal Available for this Query","intent"=>$intent,"body_html"=>"","footer_html"=>'',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {
	$sql = "DELETE FROM rooms WHERE room_code='".$rc."'";
	if($result = $conn->query($sql)){
		$sql = "DELETE FROM bookings WHERE room_code='".$rc."'";
		if($result = $conn->query($sql)){
			$empty = array("status_code"=>"success","status_desc"=>"Room $rc has been deleted and all accociated bookings!","intent"=>$intent,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		} else {
			$empty = array("status_code"=>"error","status_desc"=>"Err2: Bad query provided, please try again!","DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Err1: Room doesn't exist!","DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}

} else {
	$empty = array("status_code"=>"error","status_desc"=>"Bad Intent Provided!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}


$conn->close();
?>
