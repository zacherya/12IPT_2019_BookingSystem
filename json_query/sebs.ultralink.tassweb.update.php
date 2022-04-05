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

$intent = $_GET['intent'] ?? "setup"; //setup(d) - save
$type = $_GET['type'] ?? "student"; //student(d) - teacher
$source = $_GET['source'] ?? "manual";

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.ultralink.tassweb.update&for=$type","footer_html"=>'<button type="button" class="btn btn-danger" style="float:left;" data-dismiss="modal">Close</button><button id="updateusersbtn" type="button" class="btn btn-success" onclick="updateDatabase()">Update Database</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {
	if ($source == "tass") {
		if(isset($_POST["tassdata"])) {
			if($_POST["tassdata"] != "") {
				if(json_decode($_POST["tassdata"]) === NULL) {
					$empty = array("status_code"=>"error","status_desc"=>"Provided Data is invalid. Ensure correct TASS data is used!","intent"=>$intent,"DATA"=>array());
    				$json = json_encode($empty);
					echo($json);
				} else {
					$decoded = json_decode($_POST["tassdata"], true);
					if (isset($decoded["DATA"])) {
						$person = $decoded["DATA"];
					
						$studAdded = array();
					
						foreach ($person as $student) {
						
							$uuid = $student['STUD_CODE'] ?? "";
							
							$givenname = explode(" ", $student["GIVEN_NAME"] ?? "");
							$firstname = $givenname[0];
							$middlename = $givenname[1] ?? "";
							if ($givenname[0] == $student["PREFERRED_NAME"] ?? "") {
								$prefname = "";
							} else {
								$prefname = $student["PREFERRED_NAME"] ?? "";
							}
							$lastname = $student['SURNAME'] ?? "";
						
							$gender = $student['SEX'] ?? "";
							$dob = $student['DOB'] ?? "";
							$yrgrp = $student['YEAR_GRP'] ?? "";
							$house = $student['HSE_NAME'] ?? "";
						
							$email = $student["E_MAIL"] ?? "";
							
							$dobStamp=date_create($dob);
							
							$pw = $result = substr(strtolower($firstname), 0, 1).substr(strtolower($lastname), 0, 1).substr(strtolower(date_format($dobStamp,"Y")), 0, 4);
							$saltedPassword = "D69@F17)2C!55EA2-F0E5F2&A-4%AEB#184F(D107".$pw."C@29-A61&01239))2-892#A0D1@C6B(F%0B6A@CBB%5A%";
							$hashedPassword = hash('sha512',$saltedPassword);
						
							//Add Student To Database
							$sql = 'INSERT INTO users (uuid, firstname, firstname_pref, middlename, lastname, gender, dob, year_grp, hse_name) VALUES ("'.$uuid.'", "'.$firstname.'", "'.$prefname.'", "'.$middlename.'", "'.$lastname.'", "'.$gender.'", "'.$dob.'", "'.$yrgrp.'", "'.$house.'")';
							
							if ($conn->query($sql) === TRUE) {
							
								$sql2 = 'INSERT INTO users_auth (uuid, email, password) VALUES ("'.$uuid.'","'.$email.'","'.$hashedPassword.'")';
								
								if ($conn->query($sql2) === TRUE) {
    								//Ready Array for Pass back of confirmation data
									$studAdded[$student["STUD_CODE"] ?? ""] = $student["STUDENT_DISPLAY"] ?? "";
    							} else {
    								//Ready Array for Pass back of confirmation data
									$studAdded[$student["STUD_CODE"] ?? ""] = "FAILED AT Q2: " . $conn->error . " ||| " . $sql;
    							}
    						
    						} else {
    							//Ready Array for Pass back of confirmation data
								$studAdded[$student["STUD_CODE"] ?? ""] = "FAILED AT Q1: " . $conn->error . " ||| " . $sql;
    						}
						}
						
						$emailstatus = json_decode($_POST["emailinfo"]) ?? false;
						
						if ($emailstatus) {
							
						}
						
						$empty = array("status_code"=>"success","status_desc"=>"Database updated successfully!","intent"=>$intent,"source"=>$source,"emails_sent"=>$emailstatus,"DATA"=>array("students_added"=>$studAdded));
    					$json = json_encode($empty);
						echo($json);
					} else {
						$empty = array("status_code"=>"error","status_desc"=>"The data provided is invalid TASS data. Check the data and try again.","intent"=>$intent,"DATA"=>array());
    					$json = json_encode($empty);
						echo($json);
					}
				}
				
			} else {
				$empty = array("status_code"=>"error","status_desc"=>"Provided Data is empty","intent"=>$intent,"DATA"=>array());
    			$json = json_encode($empty);
				echo($json);
			}
		} else {
			$empty = array("status_code"=>"error","status_desc"=>"Source is set to TASS but no data was provided","intent"=>$intent,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}
	} else if ($source == "manual") {
		$empty = array("status_code"=>"error","status_desc"=>"Use API query sebs.manage.users.add","redirect"=>"remote-json.php?do=sebs.manage.users.add","intent"=>$intent,"DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Source is invalid","intent"=>$intent,"DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}
	/*$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	$dataArray = array();
    while($row = $result->fetch_assoc()) {
		$row['fullname'] = preg_replace('/\s+/', ' ',str_replace("() ", "", $row['fullname'])); // remove concat if no prefered name is detected
        $dataArray[] = $row;
    }
	$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
	$json = json_encode($masterArray);
	echo($json);
} else {
	$empty = array("status_code"=>"success","status_desc"=>"No employee data was returned!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}*/
} else {
	$empty = array("status_code"=>"error","status_desc"=>"Bad Intent Provided","intent"=>$intent,"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}


$conn->close();
?>