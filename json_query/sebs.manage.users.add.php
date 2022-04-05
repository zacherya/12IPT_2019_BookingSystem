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
$type = $_GET['type'] ?? "student"; //student(d) - teacher

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.manage.users.add&type=$type","footer_html"=>'<button type="button" class="btn btn-danger" style="float:left;" data-dismiss="modal">Cancel</button><button id="adduserbtn" type="button" class="btn btn-success">Add User</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {
	
	if(isset($_POST['un'],$_POST['fn'],$_POST['fnp'],$_POST['mn'],$_POST['ln'],$_POST['g'],$_POST['dob'],$_POST['hsen'],$_POST['em'])) {
	
	$ferr = array();
	if(!preg_match("/^[a-zA-Z]*$/",str_replace(" ","",BlockSQLInjection($_POST["un"])))) { //letters only
		$ferr[] = "User ID";
	}
	if(str_replace(" ","",BlockSQLInjection($_POST["un"])) == "") { // empty
		$ferr[] = "User ID";
	}
	if(str_replace(" ","",BlockSQLInjection($_POST["fn"])) == "") {
		$ferr[] = "First Name";
	}
	if(str_replace(" ","",BlockSQLInjection($_POST["ln"])) == "") {
		$ferr[] = "Last Name";
	}
	if(str_replace(" ","",BlockSQLInjection($_POST["g"])) != "M" && str_replace(" ","",BlockSQLInjection($_POST["g"])) != "F" && str_replace(" ","",BlockSQLInjection($_POST["g"])) != "O") {
		$ferr[] = "Gender";
	}
	if(str_replace(" ","",BlockSQLInjection($_POST["dob"])) == "" && DateTime::createFromFormat('Y-m-d H:i:s', str_replace(" ","",BlockSQLInjection($_POST["dob"]))) !== FALSE) {
		$ferr[] = "Date Of Birth";
	}
	if(str_replace(" ","",BlockSQLInjection($_POST["hsen"])) == "") {
		$ferr[] = "House Name";
	}
	if(!filter_var(str_replace(" ","",BlockSQLInjection($_POST["em"])), FILTER_VALIDATE_EMAIL)) {
		$ferr[] = "Email";
	}
	
	if(count($ferr) > 0) {
		$empty = array("status_code"=>"error","status_desc"=>"Field Set Error","DATA"=>$ferr);
   		$json = json_encode($empty);
		echo($json);
		die();
	}
	
	$uuid = BlockSQLInjection($_POST['un']);
							
	$firstname = BlockSQLInjection($_POST['fn']);
	$middlename = BlockSQLInjection($_POST['mn']);
	$prefname = BlockSQLInjection($_POST['fnp']);
	$lastname = BlockSQLInjection($_POST['ln']);
					
	$gender = BlockSQLInjection($_POST['g']);
	$dob = BlockSQLInjection($_POST['dob']);
	$house = BlockSQLInjection($_POST['hsen']);
	
	if(isset($_POST['ygrp'])) {
		if(BlockSQLInjection($_POST['ygrp']) == "") {
			$yrgrp = json_encode(NULL);
		} else {
			$yrgrp = '"'.BlockSQLInjection($_POST['ygrp']).'"';
		}	
	} else {
		$yrgrp = json_encode(NULL);
	}
			
	$email = BlockSQLInjection($_POST['em']);
							
	$dobStamp=date_create($dob);
							
	$pw = $result = substr(strtolower($firstname), 0, 1).substr(strtolower($lastname), 0, 1).substr(strtolower(date_format($dobStamp,"Y")), 0, 4);
	$saltedPassword = "D69@F17)2C!55EA2-F0E5F2&A-4%AEB#184F(D107".$pw."C@29-A61&01239))2-892#A0D1@C6B(F%0B6A@CBB%5A%";
	$hashedPassword = hash('sha512',$saltedPassword);
						
	//Add Student To Database
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
	echo($json);
    
    } else {
    	$empty = array("status_code"=>"error","status_desc"=>"The form provided is invalid!","DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
    }
} else if ($intent == "update") {
	if(isset($_POST['username'],$_POST['email'],$_POST['firstname'],$_POST['lastname'],$_POST['dob_day'],$_POST['dob_month'],$_POST['dob_year'],$_POST['gender'],$_POST['hse_name'])) {
		//validation of data time
		$ferr = array();
		if(str_replace(" ","",BlockSQLInjection($_POST["username"])) == "") {
			$ferr[] = "Username";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["email"])) == "") {
			$ferr[] = "Email";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["firstname"])) == "") {
			$ferr[] = "First Name";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["lastname"])) == "") {
			$ferr[] = "Last Name";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["hse_name"])) == "") {
			$ferr[] = "House Name";
		}
		if(!is_int(json_decode(str_replace("0","",BlockSQLInjection($_POST["dob_day"]))))) {
			$ferr[] = "DOB - Day";
		}
		if(!is_int(json_decode(str_replace("0","",BlockSQLInjection($_POST["dob_month"]))))) {
			$ferr[] = "DOB - Month";
		}
		if(!is_int(json_decode(str_replace("0","",BlockSQLInjection($_POST["dob_year"]))))) {
			$ferr[] = "DOB - Year";
		}
		if(BlockSQLInjection($_POST["gender"]) != "M" && BlockSQLInjection($_POST["gender"]) != "F" && BlockSQLInjection($_POST["gender"]) != "O") {
			$ferr[] = "Gender";
		}

		if(BlockSQLInjection($_POST["hse_name"]) == "Other") {
			if(isset($_POST["hse_name_other"])) {
				if(BlockSQLInjection($_POST["hse_name_other"]) == "") {
					$housename = NULL;
				} else {
					$housename = BlockSQLInjection($_POST["hse_name_other"]);
				}
			} else {
				$ferr[] = "Other House Name (Indicated Other But House Name Not Set)";
			}
		} else {
			$housename = BlockSQLInjection($_POST["hse_name"]);
		}
		
		if(isset($_POST["year_grp"])) {
			if(!is_int(json_decode(BlockSQLInjection($_POST["year_grp"])))) {
				$ferr[] = "Year Level";
			} else {
				$yeargrp = BlockSQLInjection($_POST["year_grp"]);
			}
		} else {
			$yeargrp = NULL;
		}
		
		if(isset($_POST["password"])) {
			if(BlockSQLInjection($_POST["password"]) != "") {
				//check if password mets complexity
				$passToSet = BlockSQLInjection($_POST["password"]);
				if(strlen($passToSet) < 6) {
					$ferr[] = "Password Length - Must be 6 or more characters";
				}
				if(!preg_match("#[0-9]+#",$passToSet)) {
					$ferr[] = "Password Complexity - Must contain at least one number";
				}
				if(!preg_match("#[A-Z]+#",$passToSet)) {
        	    	$ferr[] = "Password Complexity - Must Contain At Least 1 Capital Letter";
        		}
        		if(!preg_match("#[a-z]+#",$passToSet)) {
         		   	$ferr[] = "Password Complexity - Must Contain At Least 1 Lowercase Letter!";
        		}
        	}
        }
		
		if(count($ferr) > 0) {
			$empty = array("status_code"=>"warning","status_desc"=>"There are errors in the following fields","DATA"=>$ferr);
    		$json = json_encode($empty);
			echo($json);
			die();
		}
		
		if(isset($passToSet)) {
			$pw = $passToSet;
			$saltedPassword = "D69@F17)2C!55EA2-F0E5F2&A-4%AEB#184F(D107".$pw."C@29-A61&01239))2-892#A0D1@C6B(F%0B6A@CBB%5A%";
			$hashedPassword = hash('sha512',$saltedPassword);
			$passToSet = ", password='".$hashedPassword."'";
		} else {
			$passToSet="";
		}
		
		$un = BlockSQLInjection($_POST["username"]);
		$em = BlockSQLInjection($_POST["email"]);
		$fn = BlockSQLInjection($_POST["firstname"]);
		$fnp = isset($_POST["firstname_pref"]) ? BlockSQLInjection($_POST["firstname_pref"]) : ""; // Optional
		$mn = isset($_POST["middlename"]) ? BlockSQLInjection($_POST["middlename"]) : ""; // Optional
		$ln = BlockSQLInjection($_POST["lastname"]);
		$dob = BlockSQLInjection($_POST["dob_year"])."-".BlockSQLInjection($_POST["dob_month"])."-".BlockSQLInjection($_POST["dob_day"]); //concat
		$g = BlockSQLInjection($_POST["gender"]);
		$hn = isset($housename) ? "'".$housename."'" : "null"; // Optional
		$yg = isset($yeargrp) ? $yeargrp : "null"; // Optional
		
		$sql = "UPDATE users SET firstname='$fn',firstname_pref='$fnp',middlename='$mn',lastname='$ln',gender='$g',dob='$dob',year_grp=$yg,hse_name=$hn WHERE uuid='$un'";
			//update values in DB
		if ($conn->query($sql) === TRUE) {
    		$sql = "UPDATE users_auth SET email='$em' $passToSet WHERE uuid='$un'";
			//update values in DB
			if ($conn->query($sql) === TRUE) {
    			$empty = array("status_code"=>"success","status_desc"=>$fn."'s account has been updated!","intent"=>$intent,"DATA"=>array());
    			$json = json_encode($empty);
				echo($json);
			} else {
		    	$empty = array("status_code"=>"error","status_desc"=>"Bad internal query p2: ". $conn->error,"DATA"=>array());
    			$json = json_encode($empty);
				echo($json);
			}
		} else {
		    $empty = array("status_code"=>"error","status_desc"=>"Bad internal query p1: ". $conn->error,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}
			
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"The form provided has missing data that is required!","DATA"=>array());
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