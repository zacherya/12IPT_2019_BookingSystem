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
	echo("<center><h3>There was an internal error this function may not work as expected - ".$conn->connect_error."</h3></center>");
}

function BlockSQLInjection($str) {
	$string = str_replace("'","\'",$str);
	$string = str_replace('"','\"',$string);
	return str_replace(array("&quot;\"'","&quot;"),"",$string);
}

$intent = $_GET['intent'] ?? "setup"; //setup(d) - save
$rc = $_GET['room_code'] ?? "empty8548539953982324234987";

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.manage.rooms.view&room_code=$rc","footer_html"=>'<button type="button" class="btn btn-default" style="float:left;" data-dismiss="modal">Cancel</button><button id="deleteroombtn" type="button" class="btn btn-danger">Delete</button><button id="updateroombtn" type="button" class="btn btn-warning">Update</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "display") {

	$sql = "SELECT * FROM rooms WHERE room_code='".BlockSQLInjection($rc)."' LIMIT 1";

	//Get the requested booking
	if($result = $conn->query($sql)){
		if ($result->num_rows > 0) {
    		// output data of each row
			$dataArray = array();
    		while($row = $result->fetch_assoc()) {
					$row['capacity'] = json_decode($row['capacity']);
        	$dataArray = $row;
    		}

    		$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
			$json = json_encode($masterArray);
			echo($json);

		} else {
			$empty = array("status_code"=>"error","status_desc"=>"The room requested doesn't exist or was deleted!","DATA"=>array());
    	$json = json_encode($empty);
			echo($json);
		}
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Bad query: ".$conn->error,"DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}
} else {
	$empty = array("status_code"=>"success","status_desc"=>"Bad Intent Provided","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}
?>
