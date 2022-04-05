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
	$string = str_replace("'","\'",$str);
	$string = str_replace('"','\"',$string);
	return str_replace(array("&quot;\"'","&quot;"),"",$string);
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

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.manage.rooms.add","footer_html"=>'<button type="button" class="btn btn-danger" style="float:left;" data-dismiss="modal">Cancel</button><button id="addroombtn" type="button" class="btn btn-success">Add Room</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {
	if(isset($_POST["room_code"],$_POST["name"],$_POST["location"],$_POST["capacity"])) {
		//validation of data time
		$ferr = array();
		if(str_replace(" ","",BlockSQLInjection($_POST["room_code"])) == "") {
			$ferr[] = "Room Code";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["name"])) == "") {
			$ferr[] = "Room Name";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["location"])) == "") {
			$ferr[] = "Room Location";
		}
		if(!is_int(json_decode(BlockSQLInjection($_POST["capacity"])))) {
			$ferr[] = "Capacity";
		}

		if(count($ferr) > 0) {
			$empty = array("status_code"=>"error","status_desc"=>"Field Set Error","DATA"=>$ferr);
    		$json = json_encode($empty);
			echo($json);
			die();
		}

		$sql = "INSERT INTO rooms (room_code, name, location, capacity) VALUES ('".BlockSQLInjection($_POST["room_code"])."','".BlockSQLInjection($_POST["name"])."','".BlockSQLInjection($_POST["location"])."',".BlockSQLInjection($_POST["capacity"]).")";
			//update values in DB
		if ($conn->query($sql) === TRUE) {
    		$empty = array("status_code"=>"success","status_desc"=>"Room Added Successfully","intent"=>$intent,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		} else {
		    $empty = array("status_code"=>"error","status_desc"=>"Unexpected Error Adding Room: ". $conn->error,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}

	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Form Data is invalid!","DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}
} else if ($intent == "update") {
	if(isset($_POST["room_code"],$_POST["name"],$_POST["location"],$_POST["capacity"])) {
		//validation of data time
		$ferr = array();
		if(str_replace(" ","",BlockSQLInjection($_POST["room_code"])) == "") {
			$ferr[] = "Room Code";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["name"])) == "") {
			$ferr[] = "Room Name";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["location"])) == "") {
			$ferr[] = "Room Location";
		}
		if(!is_int(json_decode(BlockSQLInjection($_POST["capacity"])))) {
			$ferr[] = "Capacity";
		}

		if(count($ferr) > 0) {
			$empty = array("status_code"=>"error","status_desc"=>"Field Set Error","DATA"=>$ferr);
    		$json = json_encode($empty);
			echo($json);
			die();
		}

		$sql = "UPDATE rooms SET name='".BlockSQLInjection($_POST["name"])."',location='".BlockSQLInjection($_POST["location"])."',capacity=".BlockSQLInjection($_POST["capacity"])." WHERE room_code='".BlockSQLInjection($_POST["room_code"])."'";
			//update values in DB
		if ($conn->query($sql) === TRUE) {
    		$empty = array("status_code"=>"success","status_desc"=>"Room Updated Successfully","intent"=>$intent,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		} else {
		    $empty = array("status_code"=>"error","status_desc"=>"Error whilst updating room. The room may not exist or you may need to check all form data is valid!","DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}

	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Form Data is invalid!","DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}
} else {
	$empty = array("status_code"=>"error","status_desc"=>"Bad intent provided","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}


$conn->close();
?>
