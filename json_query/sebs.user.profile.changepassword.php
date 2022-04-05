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

function generate_uuid() {
    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);
    $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
    return $uuid;
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
	$_SESSION["profile_pw_token"] = generate_uuid();
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.user.profile.changepassword","footer_html"=>'<button type="button" class="btn btn-default" style="float:left;" data-dismiss="modal">Cancel</button><button id="changepwdbtn" type="button" class="btn btn-warning">Change Password</button>',"DATA"=>array("old_password"=>$_SESSION["profile_pw_token"]));
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {
	
	if(isset($_POST['password'],$_POST['old_password'])) {
	
	$ferr = array();
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
 	
 	if($_POST['old_password'] != $_SESSION['profile_pw_token']) {
 		$ferr[] = "Old Password Token doesn't match. It may have been tampered or replaced with another value!";
 	}
	
	if(count($ferr) > 0) {
		$empty = array("status_code"=>"warning","status_desc"=>"There are errors with your new password matching the policy","DATA"=>$ferr);
   		$json = json_encode($empty);
		echo($json);
		die();
	}
	
	$uuid = $_SESSION['uuid'];				
	$password = BlockSQLInjection($_POST['password']);
	
	$saltedPassword = "D69@F17)2C!55EA2-F0E5F2&A-4%AEB#184F(D107".$password."C@29-A61&01239))2-892#A0D1@C6B(F%0B6A@CBB%5A%";
	$hashedPassword = hash('sha512',$saltedPassword);
						
	//Add Student To Database
	$sql = 'UPDATE users_auth SET password="'.$hashedPassword.'" WHERE uuid="'.$uuid.'"';							
	if ($conn->query($sql) === TRUE) {			
		$empty = array("status_code"=>"success","status_desc"=>"User password updated succesfully!","intent"=>$intent,"emails_sent"=>false,"DATA"=>array());
   		$json = json_encode($empty);
		echo($json);			
    						
    } else {
	    $empty = array("status_code"=>"error","status_desc"=>"Bad internal query: ". $conn->error,"DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}
    
    
    
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