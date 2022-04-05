<?php

include('db_vars.php');

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

function BlockSQLInjection($str) {
	return str_replace(array("'","\"","'",'"',"&quot;\"'","&quot;"),"",$str);
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function isTokenValid($token,$conn) {
	$sql = "SELECT * FROM auth_tokens WHERE token='".$token."'";
	$tokenResult = $conn->query($sql);
	if ($tokenResult->num_rows > 0) {
		$row = $tokenResult->fetch_assoc();
		if (getUserIpAddr() == $row['ip_adrs']) {
					$valid = new DateTime($row['valid_to']);
					if($valid > date("Y-m-d H:i:s")) {
						return true;
						
					} else {
						return false;
					}
		} else {
			return false;
		}
	} else {
		return false;
	}
	return false;
}

function tokenRequiresCaptcha($token,$conn) {
	$sql = "SELECT * FROM auth_tokens WHERE token='".$token."'";
	$tokenResult = $conn->query($sql);
	if ($tokenResult->num_rows > 0) {
		$row = $tokenResult->fetch_assoc();
		return $row['captcha_req'];
	} else {
		return false;
	}
	return false;
}

function noIntent() {
	if (!isset($_GET['intent'])) {
		$empty = array("status_code"=>"error","status_desc"=>"No script intent provided","intent"=>"","token"=>"","alert_message"=>"Internal server error occured, intent failed to set.","captcha"=>array("required"=>false,"imgurl"=>""));
    	$json = json_encode($empty);
		echo($json);
		die();
	}
	return "setup";
}


$intent = isset($_POST['intent']) ? $_POST['intent'] : (isset($_GET['intent']) ? $_GET['intent'] : noIntent());

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	$empty = array("status_code"=>"error","status_desc"=>$conn->connect_error,"intent"=>$intent,"token"=>"","alert_message"=>"Failed to create a connection to the database, please contact support","captcha"=>array("required"=>false,"imgurl"=>""));
    $json = json_encode($empty);
	echo($json);
	die();
} 

// Delete any outdated tokens
$sqlDelete = 'DELETE FROM auth_tokens WHERE NOW() > valid_to';
$delResult = $conn->query($sqlDelete);

// Delete any outdated reset requests
$sqlDelete = 'DELETE FROM auth_pw_reset WHERE NOW() > expiry';
$delResult = $conn->query($sqlDelete);

// Delete any old and seen login history info
$sqlDelete = 'DELETE FROM auth_history WHERE NOW() > DATE_ADD(login_ts, INTERVAL 22 DAY) AND seen=true';
$delResult = $conn->query($sqlDelete);

function setupNewSession($conn) {
	//Setup Temp Session
	$generatedUUID = generate_uuid();
	$sql = 'INSERT INTO auth_tokens (token, valid_to, flogin_att, ip_adrs) VALUES ("'.$generatedUUID.'",DATE_ADD(NOW(), INTERVAL 16 HOUR),0,"'.getUserIpAddr().'")';
	
	if ($conn->query($sql) === TRUE) {
		//set session data
		$_SESSION['token'] = $generatedUUID;

    	$empty = array("status_code"=>"success","status_desc"=>"","_intent"=>$_GET['intent'],"_token"=>$generatedUUID,"alert_message"=>"","captcha"=>array("required"=>false,"imgurl"=>""));
    	$json = json_encode($empty);
		echo($json);
		die();
	} else {
		http_response_code(400);
    	$empty = array("status_code"=>"error","status_desc"=>$conn->error,"intent"=>$_GET['intent'],"token"=>"","alert_message"=>"","captcha"=>array("required"=>false,"imgurl"=>""));
    	$json = json_encode($empty);
		echo($json);
		die();
	}
}

function validateCredentials($un,$pw,$conn) {
	$saltedPassword = "D69@F17)2C!55EA2-F0E5F2&A-4%AEB#184F(D107".BlockSQLInjection($pw)."C@29-A61&01239))2-892#A0D1@C6B(F%0B6A@CBB%5A%";
	$hashedPassword = hash('sha512',$saltedPassword);
					
	$sql = "SELECT u.*,ua.email,ua.password FROM users u, users_auth ua WHERE u.uuid = ua.uuid AND (u.uuid = '".BlockSQLInjection($un)."' OR ua.email = '".BlockSQLInjection($un)."') AND (u.year_grp IS NULL)";
	$userResult = $conn->query($sql);
	if ($userResult->num_rows > 0) {
		$row = $userResult->fetch_assoc();
		if ($row['password'] == $hashedPassword) {
			//Credentials Supplied are correct
			//check for web access
				$_POST['username'] = $row['uuid'];
				//does have access
				return true;
		} else {
			//Invalid Credentials
			$sql = 'INSERT INTO auth_history(uuid, login_flg, user_agent, remote_addr, referrer) VALUES ("'.$row['uuid'].'","F","'.$_SERVER["HTTP_USER_AGENT"].'","'.getUserIpAddr().'","'.(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "unknown").'")';
			$conn->query($sql);
			return false;
		}
	} else {
		//user doesn't exist
		/*$empty = array("status_code"=>"error","status_desc"=>"users_auth data or permission data invalid/doesn't exist","_intent"=>$_GET['intent'],"_token"=>$_GET['token'],"alert_message"=>"There was an internal server error when processing your request","captcha"=>array("required"=>"false","imgurl"=>""));
    	$json = json_encode($empty);
		echo($json);
		die();*/
		return false;
	}
	return false;
}

function completeLogin($intent,$conn,$row) {
		//  now lets assign token to user
				//process login info
					if (validateCredentials($_POST['username'],$_POST['password'],$conn)) {
						//valid
						//great credentials are valid and user has web access
						
						$sql = 'UPDATE auth_tokens SET uuid="'.BlockSQLInjection($_POST['username']).'" WHERE token="'.BlockSQLInjection($_POST['token']).'"';
						$tokenResult = $conn->query($sql);
						
						//now let's setup the session
						//  well first get the user vaiables.
						$sql = 'SELECT e.uuid,e.firstname,e.firstname_pref,e.middlename,e.lastname,lt.token,lt.valid_to FROM users e, auth_tokens lt WHERE e.uuid = lt.uuid AND e.uuid = "'.$_POST['username'].'" AND lt.token = "'.$_POST['token'].'"';
						$tokenResult = $conn->query($sql);
						if ($tokenResult->num_rows > 0) {
							$row = $tokenResult->fetch_assoc();
							$_SESSION['loggedin'] = true;
							$_SESSION['sessionexp'] = $row['valid_to'];
	
							$_SESSION['uuid'] = $row['uuid'];
							$_SESSION['firstname'] = $row['firstname'];
							$_SESSION['pref_name'] = $row['firstname_pref'];
							$_SESSION['middlename'] = $row['middlename'];
							$_SESSION['lastname'] = $row['lastname'];
							
							$sql = 'INSERT INTO auth_history(uuid, login_flg, user_agent, remote_addr, referrer) VALUES ("'.BlockSQLInjection($_POST['username']).'","S","'.$_SERVER["HTTP_USER_AGENT"].'","'.getUserIpAddr().'","'.(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "unknown").'")';
							$conn->query($sql);
							
							$empty = array("status_code"=>"success","status_desc"=>"login successful, session created","_intent"=>$intent,"_token"=>$_POST['token'],"alert_message"=>"","captcha"=>array("required"=>false,"imgurl"=>"",));
    						$json = json_encode($empty);
							echo($json);
							die();
						} else {
							http_response_code(400);
							$empty = array("status_code"=>"error","status_desc"=>"Internal server error - session and database failed to comply. This should never happen.","_intent"=>$intent,"_token"=>$_POST['token'],"alert_message"=>"An severe internal error has occured, please clear your cookies and cache and restart your browser. If the problem persists contact support.","captcha"=>array("required"=>false,"imgurl"=>"",));
    						$json = json_encode($empty);
							echo($json);
							die();
						}
						/**/
					} else {
						//invalid
						//now lets add a failed attempt to the token
						$sql = 'UPDATE auth_tokens SET flogin_att = flogin_att + 1 WHERE token="'.BlockSQLInjection($_POST['token']).'"';
						$conn->query($sql);
						
						//check if this is the third login attempt
						if($row['flogin_att'] == 3) {
							//setup database for captcha
							//looking at old retrieved data, yes we've added one but if attempt is 3 in old data it means it'll be 4 now
							$sql = 'UPDATE auth_tokens SET captcha_req = true WHERE token="'.BlockSQLInjection($_POST['token']).'"';
							$conn->query($sql);
						}
						
						if($row['captcha_req'] == true || $row['flogin_att'] > 3) {
							//we would add failed login attempts to the log here but we don't wan't invalid users
							http_response_code(401);
							$empty = array("status_code"=>"error","status_desc"=>"Invalid username or password","_intent"=>$intent,"_token"=>$_POST['token'],"alert_message"=>"The username or password supplied was invalid, please try again!","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_POST['token']));
    						$json = json_encode($empty);
							echo($json);
							die();
						} else {
							//we would add failed login attempts to the log here but we don't wan't invalid users
							http_response_code(401);
							$empty = array("status_code"=>"error","status_desc"=>"Invalid username or password","_intent"=>$intent,"_token"=>$_POST['token'],"alert_message"=>"The username or password supplied was invalid, please try again!","captcha"=>array("required"=>false,"imgurl"=>""));
    						$json = json_encode($empty);
							echo($json);
							die();
						}
						
						
					}		
}

if ($intent == "setup") {
	//Check for existing session
	if(isset($_SESSION['token'])) {
		//validate token
		if (isTokenValid($_SESSION['token'],$conn)) {
			//token is valid
			if (tokenRequiresCaptcha($_SESSION['token'],$conn)) {
				//captcha is required
    			$empty = array("status_code"=>"success","status_desc"=>"","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_SESSION['token']));
    			$json = json_encode($empty);
				echo($json);
				die();
			} else {
				//captcha isn't required
				$empty = array("status_code"=>"success","status_desc"=>"","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"","captcha"=>array("required"=>false,"imgurl"=>""));
    			$json = json_encode($empty);
				echo($json);
				die();
			}
		} else {
			unset($_SESSION['token']);
			setupNewSession($conn);
		}
	} else {
		setupNewSession($conn);
	}
	
} else if ($intent == "check") {
	if(isset($_SESSION['token'])) {
		if(isTokenValid($_SESSION['token'],$conn)){
			$increase = $_GET['inc'] ?? false;
			if (json_decode($increase) == true) {
				// update exipry 
				//$expUp = 'UPDATE auth_tokens SET valid_to = DATE_ADD(valid_to, INTERVAL 1 HOUR) WHERE token = "'.$_SESSION['token'].'"';
				
				$updatedSeshExp = new DateTime(date("Y-m-d H:i:s"));
				$updatedSeshExp->add(new DateInterval('PT16H'));
				$result = $updatedSeshExp->format('Y-m-d H:i:s');
				
				$expUp = 'UPDATE auth_tokens SET valid_to = "'.$result.'" WHERE token = "'.$_SESSION['token'].'"';
				$expUpResult = $conn->query($expUp);
				
				$_SESSION['sessionexp'] = $result;
			}
			$empty = array("status_code"=>"success","status_desc"=>"session good","_intent"=>$intent,"_token"=>"","alert_message"=>"","logout"=>false,"session_expiry"=>$_SESSION['sessionexp']);
    		$json = json_encode($empty);
			echo($json);
			http_response_code(200);
			die();
		} else {
			$empty = array("status_code"=>"error","status_desc"=>"session bad - api token","_intent"=>$intent,"_token"=>"","alert_message"=>"Session is invalid as it no longer exists!","logout"=>true,"session_expiry"=>"");
    		$json = json_encode($empty);
			echo($json);
			die();
		}
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"session bad","_intent"=>$intent,"_token"=>"","alert_message"=>"Session is invalid","logout"=>true,"session_expiry"=>"");
    	$json = json_encode($empty);
		echo($json);
		die();
	}
	
} else if ($intent == "save") {
	// Check ISSET
	if (isset($_POST['username'],$_POST['password'],$_POST['token'])) {
		if($_POST['token'] != $_SESSION['token']) {
			$empty = array("status_code"=>"error","status_desc"=>"token mismatch from session","_intent"=>$intent,"_token"=>"","alert_message"=>"An internal error has occured whilst trying to validate your session, please refresh the site.","captcha"=>array("required"=>false,"imgurl"=>""));
    		$json = json_encode($empty);
			echo($json);
			http_response_code(403);
			die();
		}
		//Get token data
		$sql = 'SELECT * FROM auth_tokens WHERE token="'.BlockSQLInjection($_POST['token']).'"';
		$tokenResult = $conn->query($sql);
		if ($tokenResult->num_rows > 0) {
			$row = $tokenResult->fetch_assoc();
			
				if($row['captcha_req'] == true || $row['flogin_att'] > 3) {
					//captcha is required
					if (isset($_POST['captcha'])) {
						//process captcha
						if($row['captcha_val'] == $_POST['captcha']) {
							completeLogin($intent,$conn,$row);
						} else {
							http_response_code(401);
							$empty = array("status_code"=>"error","status_desc"=>"captcha incorrect","_intent"=>$intent,"_token"=>$_POST['token'],"alert_message"=>"The captcha entered was incorrect, please try again!","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_POST['token']));
    						$json = json_encode($empty);
							echo($json);
							die();
						}
					} else {
						// no captcha provided
						// check if token requires captcha
						$empty = array("status_code"=>"warning","status_desc"=>"captcha required","_intent"=>$intent,"_token"=>$_POST['token'],"alert_message"=>"Too many failed attempts, to continue enter the captcha!","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_POST['token']));
    					$json = json_encode($empty);
						echo($json);
						die();
					}
				} else {
					//captcha isnt required
					//process login info
					completeLogin($intent,$conn,$row);
				}
			
		} else {
			$empty = array("status_code"=>"error","status_desc"=>"Invalid token provided, access denied","_intent"=>$intent,"_token"=>"invalid","alert_message"=>"There was an users_auth error, please close down your browser and try again!","captcha"=>array("required"=>false,"imgurl"=>""));
    		$json = json_encode($empty);
			echo($json);
			http_response_code(403);
			die();
		}
		
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Form data was invalid, access denied","_intent"=>$intent,"_token"=>"","alert_message"=>"The form data provided was invalid thus access is denied, please contact support!","captcha"=>array("required"=>false,"imgurl"=>""));
    	$json = json_encode($empty);
		echo($json);
		http_response_code(403);
		die();
	}
	//Check passed token
	
	//Validate timestamp
	
	//Validate IP
	
} else if ($intent == "reset") {

	if(isset($_SESSION['token'])) {
		if (isset($_POST['email'], $_POST['captcha'])) { 
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  				$empty = array("status_code"=>"error","status_desc"=>"Email format bad","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"The email provided isn't a valid email address, please try again!","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_SESSION['token']));
    			$json = json_encode($empty);
				echo($json);
				http_response_code(400);
				die();
			} else {
				//Get token data
				$sql = 'SELECT * FROM auth_tokens WHERE token="'.$_SESSION['token'].'"';
				$tokenResult = $conn->query($sql);
				if ($tokenResult->num_rows > 0) {
					$row = $tokenResult->fetch_assoc();
					if($row['captcha_val'] == $_POST['captcha']) {
						$sql = 'SELECT ed.*, e.firstname, e.firstname_pref FROM users_auth ed, users e WHERE ed.uuid = e.uuid AND email="'.$_POST['email'].'"';
						$userResult = $conn->query($sql);
						if ($userResult->num_rows > 0) {
							$tuple = $userResult->fetch_assoc();
							
							$empcode = $tuple['uuid'];
							$email = $_POST['email'];
							$token = bin2hex(random_bytes(100));
							
							$injectBay = "INSERT INTO auth_pw_reset(uuid,token,email,expiry) VALUES ('".$empcode."', '".$token."', '".$email."',DATE_ADD(NOW(), INTERVAL 12 HOUR))";
							$conn->query($injectBay);
							
							$to = $_POST['email'];

							$subject = 'Marmaduke - Password Reset Request';

							$headers = "From: donotreply@marmaduke.com.au\r\n";
							$headers .= "Reply-To: support@adtechsoftware.com.au\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
							
							$name = (isset($tuple['firstname_pref']) ? $tuple['firstname_pref'] : $tuple['firstname']);
							$ipaddr = getUserIpAddr();
							
							$date = new DateTime();
							$date->modify("+12 hours");
							$expstamp = $date->format('d/M/y g:i A');

							$linkToReset = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/login.php?do=recovery&tka=$token";
							
							include('./reset_tpl.php');
							
							if(mail($to, $subject, $htmlMessage, $headers)) {
								//success
								$empty = array("status_code"=>"success","status_desc"=>"reset sent to waiting bay","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"If an account with that email exist then you will recieve an email in your Inbox, Junk or Spam boxes with a link.","captcha"=>array("required"=>false,"imgurl"=>""));
    							$json = json_encode($empty);
								echo($json);
								die();
							} else {
								//failed
								
								// Delete that token
								$sqlDelete = 'DELETE FROM auth_pw_reset WHERE token = "'.$token.'"';
								$delResult = $conn->query($sqlDelete);
								
								http_response_code(500);
								$empty = array("status_code"=>"error","status_desc"=>"mail failed to send","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"An internal server error has occured whilst sending your reset email. Please try again later or contact suppport.","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_SESSION['token']));
    							$json = json_encode($empty);
								echo($json);
								die();
							}
							
							
						} else {
							$empty = array("status_code"=>"success","status_desc"=>"reset sent to waiting bay","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"If an account with that email exist then you will recieve an email in your Inbox, Junk or Spam boxes with a link.","captcha"=>array("required"=>false,"imgurl"=>""));
    						$json = json_encode($empty);
							echo($json);
							die();
						}
					} else {
						http_response_code(401);
						$empty = array("status_code"=>"error","status_desc"=>"captcha incorrect","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"The captcha entered was incorrect, please try again!","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_SESSION['token']));
    					$json = json_encode($empty);
						echo($json);
						die();
					}
				} else {
					$empty = array("status_code"=>"error","status_desc"=>"bad token, doesn't exist","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"The token provided by the session is invalid! Please restart your browser to request a new session.","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_SESSION['token']));
    				$json = json_encode($empty);
					echo($json);
					http_response_code(400);
					die();
				}
			}
		} else {
			$empty = array("status_code"=>"error","status_desc"=>"Form data was invalid, request denied","_intent"=>$intent,"_token"=>$_SESSION['token'],"alert_message"=>"The form data provided was invalid thus request is denied, please contact support!","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_SESSION['token']));
    		$json = json_encode($empty);
			echo($json);
			http_response_code(400);
			die();
		}
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"session invalid","_intent"=>$intent,"_token"=>"","alert_message"=>"An internal server error has occured!","captcha"=>array("required"=>true,"imgurl"=>"get-resource.php?do=ui.login.captcha&token=".$_SESSION['token']));
    	$json = json_encode($empty);
		echo($json);
		http_response_code(400);
		die();
	}
	

} else {
	http_response_code(400);
	$empty = array("status_code"=>"error","status_desc"=>"the server was unable to validate the scripts intent-invalid intent provided","_intent"=>"invalid","_token"=>(isset($_SESSION['token']) ? $_SESSION['token'] : ""),"alert_message"=>"An internal server error has occured","captcha"=>array("required"=>false,"imgurl"=>""));
	$json = json_encode($empty);
	echo($json);
	die();
}

//if ($_POST['username']) {

//}


$conn->close();
?>