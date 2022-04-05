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

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"","footer_html"=>'',"DATA"=>array());
  $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {

	if(isset($_POST["loan_data"])) {

		if (count($_POST['loan_data']) > 0) {
			$ferr = array();
			$loanRef = 0;
			$completedItms = array();
			foreach ($_POST['loan_data'] as $row) {
				if(isset($row['itemcode'],$row['qty'],$row['period'],$row['periodfactor'],$row['uuid'])) {
					if(str_replace(" ","",BlockSQLInjection($row["uuid"])) == "") {
						$ferr[] = "Error at index (USERNAME) {{".$row."}}";
					}
					if(!is_int(json_decode(BlockSQLInjection($row["itemcode"])))) {
						$ferr[] = "Error at index (ITEMCODE) {{".$row."}}";
					}
					if(!is_int(json_decode(BlockSQLInjection($row["qty"])))) {
						$ferr[] = "Error at index (QTY) {{".$row."}}";
					}
					if(!is_float(json_decode(BlockSQLInjection($row["period"]))) && !is_int(json_decode(BlockSQLInjection($row["period"])))) {
						$ferr[] = "Error at index (PERIOD) {{".$row."}}";
					}
					if(BlockSQLInjection($row["periodfactor"]) != "m" && BlockSQLInjection($row["periodfactor"]) != "h" && BlockSQLInjection($row["periodfactor"]) != "d" && BlockSQLInjection($row["periodfactor"]) != "w" && BlockSQLInjection($row["periodfactor"]) != "mth") {
						$ferr[] = "Error at index (PERIOD FACTOR) {{".$row."}}";
					}
					if(count($ferr) > 0) {
						$empty = array("status_code"=>"error","status_desc"=>"Field Set Error","DATA"=>$ferr);
			   		$json = json_encode($empty);
						echo($json);
						die();
					}
					//do time calculations
					switch (BlockSQLInjection($row["periodfactor"])) {
						case "m":
							$dayval = json_decode(BlockSQLInjection($row["period"]))/1440;
							break;
						case "h":
							$dayval = json_decode(BlockSQLInjection($row["period"]))/24;
							break;
						case "d":
							$dayval = json_decode(BlockSQLInjection($row["period"]));
							break;
						case "w":
							$dayval = json_decode(BlockSQLInjection($row["period"]))*7;
							break;
						case "mth":
							$dayval = (json_decode(BlockSQLInjection($row["period"]))*28);
							break;
						default:
							$empty = array("status_code"=>"error","status_desc"=>"TIME CALCULATION ERROR!!!! Aborted to avoid database curruption","DATA"=>$ferr);
			    		$json = json_encode($empty);
							echo($json);
							die();
							break;
					}
					$un = $row['uuid'];
					$ic = $row['itemcode'];
					$qy = $row['qty'];
					if($loanRef != 0) {
						$sql = "INSERT INTO loans (loan_id, item_code, uuid, quantity, loan_days) VALUES ($loanRef, $ic, '$un', $qy, $dayval)";
					} else {
						$sql = "INSERT INTO loans (item_code, uuid, quantity, loan_days) VALUES ($ic, '$un', $qy, $dayval)";
					}

					if ($conn->query($sql)) {
						$loanRef = $conn->insert_id;

					} else {
						$empty = array("status_code"=>"error","status_desc"=>"Failed to insert data ".$conn->error,"DATA"=>$ferr);
			   		$json = json_encode($empty);
						echo($json);
						die();
					}
					//run here
				} else {
					$empty = array("status_code"=>"error","status_desc"=>"There was invalid data headings in form data and writing to the database was prevented!","DATA"=>$ferr);
		   		$json = json_encode($empty);
					echo($json);
					die();
				}
			}

			//success
			$empty = array("status_code"=>"success","status_desc"=>"User added succesfully!","intent"=>$intent,"emails_sent"=>false,"DATA"=>array());
	    $json = json_encode($empty);
			echo($json);

		} else {
		  $empty = array("status_code"=>"error","status_desc"=>"The form provided is empty","DATA"=>array());
			$json = json_encode($empty);
		 	echo($json);
		}

	/*Add Student To Database
	$sql = 'INSERT INTO users (uuid, firstname, firstname_pref, middlename, lastname, gender, dob, year_grp, hse_name) VALUES ("'.$uuid.'", "'.$firstname.'", "'.$prefname.'", "'.$middlename.'", "'.$lastname.'", "'.$gender.'", "'.$dob.'", '.$yrgrp.', "'.$house.'")';

	if ($conn->query($sql) === TRUE) {

		$sql2 = 'INSERT INTO users_auth (uuid, email, password) VALUES ("'.$uuid.'","'.$email.'","'.$hashedPassword.'")';

		if ($conn->query($sql2) === TRUE) {
    		//Ready Array for Pass back of confirmation data
			$studAdded[$_POST["un"]] = $_POST["fn"]." ".$_POST["ln"];
    	} else {
    		//Ready Array for Pass back of confirmation data
			$studAdded[$_POST["un"]] = "FAILED AT Q2: " . $conn->error . " ||| " . $sql;
    	}

    } else {
    	//Ready Array for Pass back of confirmation data
		$studAdded[$_POST["un"]] = "FAILED AT Q1: " . $conn->error . " ||| " . $sql;
    }

    $empty = array("status_code"=>"success","status_desc"=>"User added succesfully!","intent"=>$intent,"source"=>"manual","emails_sent"=>false,"DATA"=>array("students_added"=>$studAdded));
    $json = json_encode($empty);
	echo($json);*/

    } else {
    	$empty = array("status_code"=>"error","status_desc"=>"The form provided is invalid!","DATA"=>array());
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
