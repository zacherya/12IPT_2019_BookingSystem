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

$query = $_GET['query'] ?? "";
$distinctLoad = $_GET['distinct'] ?? false;
$stage = $_GET['stage'] ?? "0";

function goLoadAll($conn) {
	$sql = "SELECT room_code, name AS room_name, location, capacity FROM rooms";
	if($result = $conn->query($sql)){
		if ($result->num_rows > 0) {
					// output data of each row
			$dataArray = array();
				while($row = $result->fetch_assoc()) {
					$row['capacity'] = json_decode($row['capacity']);
					$dataArray[] = $row;
				}
				$masterArray = array("status_code"=>"success","status_desc"=>"Stage may have been unclear or building not set so all data was loaded!","DATA"=>$dataArray);
				$json = json_encode($masterArray);
				echo($json);
			} else {
				//No Sports Found
				$empty = array("status_code"=>"error","status_desc"=>"We had trouble finding any rooms in the system. Please setup some rooms first!","DATA"=>array());
				$json = json_encode($empty);
			echo($json);
			}
		} else {
			//Query Failed
			$empty = array("status_code"=>"error","status_desc"=>"Bad query: ".$conn->error,"DATA"=>array());
			$json = json_encode($empty);
			echo($json);
		}
}

if (json_decode($distinctLoad) == true) {
	//Distinguish Stage
	switch ($stage) {
		case "1":
			//Load all available Sports
			$sql = "SELECT DISTINCT(location) FROM rooms";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0) {
    				// output data of each row
					$dataArray = array();
   					while($row = $result->fetch_assoc()) {
   						$dataArray[] = $row["location"];
   					}
   					$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
					$json = json_encode($masterArray);
					echo($json);
   				} else {
   					//No Sports Found
   					$empty = array("status_code"=>"error","status_desc"=>"There are no buildings setup which means there are no rooms setup either. Please create some rooms first!","DATA"=>array());
    				$json = json_encode($empty);
					echo($json);
   				}
   			} else {
   				//Query Failed
   				$empty = array("status_code"=>"error","status_desc"=>"Bad query: ".$conn->error,"DATA"=>array());
   				$json = json_encode($empty);
				echo($json);
   			}
			break;
		case "2":
			if (isset($_GET['building'])) {
				$sql = "SELECT room_code, name AS room_name, location, capacity FROM rooms WHERE location='".BlockSQLInjection($_GET['building'])."'";
				if($result = $conn->query($sql)){
					if ($result->num_rows > 0) {
  		  				// output data of each row
						$dataArray = array();
   						while($row = $result->fetch_assoc()) {
   							$row['capacity'] = json_decode($row['capacity']);
   							$dataArray[] = $row;
   						}
	   					$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
						$json = json_encode($masterArray);
						echo($json);
	   				} else {
	   					//No Sports Found
	   					$empty = array("status_code"=>"error","status_desc"=>"We had trouble finding any rooms in that location. Please setup some rooms first!","DATA"=>array());
	    				$json = json_encode($empty);
						echo($json);
	   				}
	   			} else {
	   				//Query Failed
 	  				$empty = array("status_code"=>"error","status_desc"=>"Bad query: ".$conn->error,"DATA"=>array());
 	  				$json = json_encode($empty);
					echo($json);
 	  			}
			} else {
				// Warn That Sport Or Category Was NOT Set
				$empty = array("status_code"=>"error","status_desc"=>"Bad filter request: Building was not set and stage 2 requires it!","DATA"=>array());
   				$json = json_encode($empty);
				echo($json);
			}
			break;
		default:
			//Load Everything
			goLoadAll($conn);
			break;
	}
} else {
	//Load Everything
	goLoadAll($conn);
}


$conn->close();
?>
