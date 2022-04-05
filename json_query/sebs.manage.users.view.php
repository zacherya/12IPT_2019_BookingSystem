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
$uuid = $_GET['username'] ?? $_SESSION['uuid'];

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"","footer_html"=>'',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "display") {

	$sql = "SELECT u.*, concat(u.firstname,' (',u.firstname_pref,') ',u.middlename,' ',u.lastname) AS fullname, ua.email FROM users u, users_auth ua WHERE u.uuid = ua.uuid AND u.uuid='".BlockSQLInjection($uuid)."' LIMIT 1";

	//Get the requested booking
	if($result = $conn->query($sql)){
		if ($result->num_rows > 0) {
    		// output data of each row
			$userArray = array();
    		while($row = $result->fetch_assoc()) {

				$row['fullname'] = preg_replace('/\s+/', ' ',str_replace("() ", "", $row['fullname'])); // remove concat if no prefered name is detected

        		$userArray = $row;
    		}

				//Now Get Users bookings
				$sql = "SELECT b.booking_id, b.recur_id AS recurance_code, r.name, r.location, b.room_code, TIME(b.hire_from) AS fts, TIME(b.hire_to) AS tts, DATE(b.hire_from) AS fds, DATE(b.hire_to) AS tds, b.info AS memo, IF(DATE(b.hire_from) < DATE(NOW()) AND DATE(b.hire_to) > DATE(NOW()),'true','false') AS all_day, IF(DATE(DATE_ADD(b.hire_to, INTERVAL 1 DAY)) = DATE(b.hire_from),'true','false') AS nextday_from_inital_flg FROM bookings b, rooms r WHERE b.room_code = r.room_code AND uuid = '$uuid' AND guest IS NULL AND (NOW() < hire_to) ORDER BY fts";

				if($result = $conn->query($sql)){
					if ($result->num_rows > 0) {
			    		// output data of each row
							$bookingArray = array();
							while($row = $result->fetch_assoc()) {
					    	$row['all_day'] = json_decode($row['all_day']);
					    	if ($row['memo'] == NULL) {
					    		$row['memo'] = "";
					    	}
					    	$row['fts'] = date("g:ma", strtotime($row['fts']));
					    	$row['tts'] = date("g:ma", strtotime($row['tts']));



					    	$row['nextday_from_inital_flg'] = json_decode($row['nextday_from_inital_flg']);
								$bookingArray[] = $row;
					    }
					} else {
						$bookingArray = array();
					}
				} else {
					$bookingArray = array();
				}

				//Now Get Users Loans
				$sql = "SELECT l.loan_id, l.item_code, i.name AS item_desc, i.category AS item_category, i.sport AS item_sport, IF(NOW() >= DATE_ADD(l.borrowed_ts, INTERVAL l.loan_days DAY),'OD','OK') AS status_flg, l.borrowed_ts AS borrowed_ts, DATE_ADD(l.borrowed_ts, INTERVAL l.loan_days DAY) AS return_on , l.quantity AS qty_of_item, l.return_ts AS returned_on, l.loan_days FROM loans l, items i WHERE l.item_code=i.item_code AND uuid='$uuid'";

				if($result = $conn->query($sql)){
					if ($result->num_rows > 0) {
			    		// output data of each row
							$loansArray = array();
							while($row = $result->fetch_assoc()) {
					    	$row["loan_days"] = json_decode($row["loan_days"]);
								$row["qty_of_item"] = json_decode($row["qty_of_item"]);

								if($row["loan_days"] >= 7) {
									//Weeks
										// DB_VALUE divide by 7
										$timeval = round($row["loan_days"]/7);
										if($timeval >= 4) {
											//Month
											// TIME VALUE divide by 4
											$timeval = round($timeval/4);

											$rowDate = date_create($row["borrowed_ts"]);
											date_add($rowDate,date_interval_create_from_date_string($timeval." months"));
											$row["return_on"] = date_format($rowDate,"Y-m-d H:i:s");

										} else {
											//Defs weeks
											$rowDate = date_create($row["borrowed_ts"]);
											date_add($rowDate,date_interval_create_from_date_string($timeval." weeks"));
											$row["return_on"] = date_format($rowDate,"Y-m-d H:i:s");
										}
								} else if($row["loan_days"] >= 1) {
									//Days
										// DB_VALUE
										$rowDate = date_create($row["borrowed_ts"]);
										//date_add($rowDate,date_interval_create_from_date_string($timeval." hours"));
										if(is_float($row["loan_days"])) { //check if hour is decimal
											//yes it is split so we can add hours and minutes
											$explodedTv = explode(".", $timeval);
											if (!isset($explodedTv[1])) {
												date_add($rowDate,date_interval_create_from_date_string(sprintf("+%d minutes", round(floatval("0.".$explodedTv[0])*60))));
											} else {
												date_add($rowDate,date_interval_create_from_date_string(sprintf("+%d hours", $explodedTv[0])));
												date_add($rowDate,date_interval_create_from_date_string(sprintf("+%d minutes", round(floatval("0.".$explodedTv[1])*60))));
											}
										} else {
											//do nothing
											//SHOULD OF ALREADY BEEN ADDED PROPERLY
										}
										$row["return_on"] = date_format($rowDate,"Y-m-d H:i:s");
								} else if($row["loan_days"] >= (1/24)) {
									//Hours
										// DB_VALUE times by 24
										$timeval = $row["loan_days"]*24;
										$rowDate = date_create($row["borrowed_ts"]);
										//date_add($rowDate,date_interval_create_from_date_string($timeval." hours"));
										if(is_float($timeval)) { //check if hour is decimal
											//yes it is split so we can add hours and minutes
											$explodedTv = explode(".", $timeval);
											if (!isset($explodedTv[1])) {
												date_add($rowDate,date_interval_create_from_date_string(sprintf("+%d minutes", round(floatval("0.".$explodedTv[0])*60))));
											} else {
												date_add($rowDate,date_interval_create_from_date_string(sprintf("+%d hours", $explodedTv[0])));
												date_add($rowDate,date_interval_create_from_date_string(sprintf("+%d minutes", round(floatval("0.".$explodedTv[1])*60))));
											}

										} else {
											//nope just hours
											date_add($rowDate,date_interval_create_from_date_string(sprintf("+%d hours", round($timeval))));
										}
										$row["return_on"] = date_format($rowDate,"Y-m-d H:i:s");
								} else if($row["loan_days"] >= (1/1446)) {
									//Minutes
										// DB_VALUE times by 24 (Gets Time in hours)
										// HOURS times by 60
										$timeval = round(($row["loan_days"]*24)*60);
										$rowDate = date_create($row["borrowed_ts"]);
										date_add($rowDate,date_interval_create_from_date_string($timeval." minutes"));
										$row["return_on"] = date_format($rowDate,"Y-m-d H:i:s");


								}
								date_default_timezone_set("Australia/Queensland");

								if(new DateTime() >= new DateTime($row["return_on"])) {
									$row["status_flg"] = "OD";
								} else {
									if(date_format(new DateTime(),"Y/m/d") == date_format(date_create($row["return_on"]),"Y/m/d")) {
										$row["status_flg"] = "DT";
									} else {
										$row["status_flg"] = "OK";
									}

								}
								$row["cdt"] = new DateTime($row["return_on"]);

								$loansArray[] = $row;
					    }
					} else {
						$loansArray = array();
					}
				} else {
					$loansArray = array();
				}


    		$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>array("info"=>$userArray,"bookings"=>$bookingArray,"loans"=>$loansArray));
			$json = json_encode($masterArray);
			echo($json);

		} else {
			$empty = array("status_code"=>"error","status_desc"=>"The user requested doesn't exist or was deleted!","DATA"=>array());
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
