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
$uuid = $_GET['username'] ?? "";

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.manage.users.remove?username=$uuid","footer_html"=>'<button type="button" class="btn btn-success" style="float:left;" data-dismiss="modal">Cancel</button><button id="removeuserbtn" data-uid="'.$uuid.'" type="button" class="btn btn-danger">Confirm Action</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {
	$sql = "DELETE FROM users WHERE uuid='".BlockSQLInjection($uuid)."'";
	if($result = $conn->query($sql)){
		$sql = "DELETE FROM users_auth WHERE uuid='".BlockSQLInjection($uuid)."'";
		if($result = $conn->query($sql)){
			$empty = array("status_code"=>"success","status_desc"=>"User ($uuid) Deleted Successfully","intent"=>$intent,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		} else {
			$empty = array("status_code"=>"error","status_desc"=>"Bad query provided at p2, please try again!","DATA"=>array());
 		   	$json = json_encode($empty);
			echo($json);
		}
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Bad query provided at p1, please try again!","DATA"=>array());
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