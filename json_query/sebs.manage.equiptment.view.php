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
$ic = $_GET['item_code'] ?? 0;

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.manage.equiptment.view&item_code=$ic","footer_html"=>'<button type="button" class="btn btn-default" style="float:left;" data-dismiss="modal">Cancel</button><button id="deleteitembtn" type="button" class="btn btn-danger">Delete</button><button id="updateitembtn" type="button" class="btn btn-warning">Update</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "display") {

	$sql = "SELECT * FROM items WHERE item_code='".BlockSQLInjection($ic)."' LIMIT 1";

	//Get the requested booking
	if($result = $conn->query($sql)){
		if ($result->num_rows > 0) {
    		// output data of each row
			$dataArray = array();
    		while($row = $result->fetch_assoc()) {
				
				$row['item_code'] = json_decode($row['item_code']);
				
				$row['stock_qty'] = json_decode($row['stock_qty']);
				$row['max_qty'] = json_decode($row['max_qty']);
				$row['max_days'] = json_decode($row['max_days']);
				
				$row['stud_alwd'] = json_decode($row['stud_alwd']);
				$row['staff_alwd'] = json_decode($row['staff_alwd']);
				
        		$dataArray = $row;
    		}
    		
    		$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
			$json = json_encode($masterArray);
			echo($json);

		} else {
			$empty = array("status_code"=>"error","status_desc"=>"The item requested doesn't exist or was deleted!","DATA"=>array());
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