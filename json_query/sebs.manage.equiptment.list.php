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

if (json_decode($distinctLoad) == true) {
	//Distinguish Stage
	switch ($stage) {
		case "1":
			//Load all available Sports
			$sql = "SELECT DISTINCT(sport) FROM items";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0) {
    				// output data of each row
					$dataArray = array();
   					while($row = $result->fetch_assoc()) {
   						$dataArray[] = $row["sport"];
   					}
   					$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
					$json = json_encode($masterArray);
					echo($json);
   				} else {
   					//No Sports Found
   					$empty = array("status_code"=>"error","status_desc"=>"There are no items setup which means there are no sport category's set. Please create some items first!","DATA"=>array());
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
			//Load all available categories in chosen sport
			if (isset($_GET['sport'])) {
				$sql = "SELECT DISTINCT(category) AS category FROM items WHERE sport='".BlockSQLInjection($_GET['sport'])."'";
				if($result = $conn->query($sql)){
					if ($result->num_rows > 0) {
  		  				// output data of each row
						$dataArray = array();
   						while($row = $result->fetch_assoc()) {
   							$dataArray[] = $row["category"];
   						}
	   					$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
						$json = json_encode($masterArray);
						echo($json);
	   				} else {
	   					//No Sports Found
	   					$empty = array("status_code"=>"error","status_desc"=>"We had trouble finding any categories under that sport. Please setup some items first!","DATA"=>array());
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
				// Warn That Sport Was NOT Set
				$empty = array("status_code"=>"error","status_desc"=>"Bad filter request: Sport was not set and stage 2 requires it!","DATA"=>array());
   				$json = json_encode($empty);
				echo($json);
			}
			break;
		case "3":
			if (isset($_GET['sport'],$_GET['category'])) {
				$sql = "SELECT item_code, name AS item_desc, stock_qty, max_qty, max_qty, stud_alwd AS students_permitted, staff_alwd AS staff_permitted FROM items WHERE sport='".BlockSQLInjection($_GET['sport'])."' AND category='".BlockSQLInjection($_GET['category'])."'";
				if($result = $conn->query($sql)){
					if ($result->num_rows > 0) {
  		  				// output data of each row
						$dataArray = array();
   						while($row = $result->fetch_assoc()) {
   							$row['item_code'] = json_decode($row['item_code']);
   							$row['staff_permitted'] = json_decode($row['staff_permitted']);
   							$row['students_permitted'] = json_decode($row['students_permitted']);
   							$row['stock_qty'] = json_decode($row['stock_qty']);
   							$row['max_qty'] = json_decode($row['max_qty']);
   							$row['max_qty'] = json_decode($row['max_qty']);
   							$dataArray[] = $row;
   						}
	   					$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
						$json = json_encode($masterArray);
						echo($json);
	   				} else {
	   					//No Sports Found
	   					$empty = array("status_code"=>"error","status_desc"=>"We had trouble finding any categories under that sport. Please setup some items first!","DATA"=>array());
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
				$empty = array("status_code"=>"error","status_desc"=>"Bad filter request: Sport And Category was not set and stage 3 requires it!","DATA"=>array());
   				$json = json_encode($empty);
				echo($json);
			}
			break;
		default:
			//Load Everything
			break;
	}
} else {
	$sql = "SELECT item_code, category, sport, name AS item_desc, stock_qty, max_qty, max_qty, stud_alwd AS students_permitted, staff_alwd AS staff_permitted FROM items";
				if($result = $conn->query($sql)){
					if ($result->num_rows > 0) {
  		  				// output data of each row
						$dataArray = array();
   						while($row = $result->fetch_assoc()) {
   							$row['item_code'] = json_decode($row['item_code']);
   							$row['staff_permitted'] = (bool)$row['staff_permitted'];
   							$row['students_permitted'] = (bool)$row['students_permitted'];
   							$row['stock_qty'] = json_decode($row['stock_qty']);
   							$row['max_qty'] = json_decode($row['max_qty']);
   							$row['max_qty'] = json_decode($row['max_qty']);

								$stockLeftSql="SELECT SUM(quantity) AS onloan_qty FROM loans WHERE item_code=".$row['item_code'];
								if($slResult = $conn->query($stockLeftSql)){
									if ($slResult->num_rows > 0) {
										while($rowOfRemainQty = $slResult->fetch_assoc()) {
											$row['taken_qty'] = json_decode($rowOfRemainQty["onloan_qty"]);
											if($row['taken_qty'] === NULL) {
												$row['taken_qty'] = 0;
											}
											$row['remaining_qty'] = json_decode($row['stock_qty']-$row['taken_qty']);
										}
									} else {
										$row['taken_qty'] = 0;
										$row['remaining_qty'] = json_decode($row['stock_qty']-$row['taken_qty']);
									}
								} else {
									$row['taken_qty'] = 0;
									$row['remaining_qty'] = json_decode($row['stock_qty']-$row['taken_qty']);
								}

   							$dataArray[] = $row;
   						}
	   					$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
						$json = json_encode($masterArray);
						echo($json);
	   				} else {
	   					//No Sports Found
	   					$empty = array("status_code"=>"error","status_desc"=>"We had trouble finding any categories under that sport. Please setup some items first!","DATA"=>array());
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

$conn->close();
?>
