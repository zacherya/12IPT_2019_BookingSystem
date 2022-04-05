<?php
include('db_vars.php');
if ($_SESSION['manager'] == false) {
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

if(isset($_GET['terminated'])) {
	if($_GET['terminated']) {
		$terminated = 'emp_code = emp_code';
	} else {
		$terminated = 'emp_code NOT IN (SELECT emp_code FROM employee_termination)';
	}
} else {
	$terminated = 'emp_code = emp_code';
}

if(isset($_GET['current'])) {
	if($_GET['current'] == "true") {
		$current = 'expiry > CURDATE()';
	} else {
		$current = '(expiry = expiry OR expiry IS NULL)';
	}
} else {
	$current = '(expiry = expiry OR expiry IS NULL)';
}



//Get count of non delivered warnings
$nd = 'SELECT COUNT(*) AS count FROM issued_warnings WHERE (delivery_ts IS NULL) AND '.$terminated.' AND '.$current;

//Get count of delivered warnings
$d = 'SELECT COUNT(*) AS count FROM issued_warnings WHERE (delivery_ts IS NOT NULL) AND '.$terminated.' AND '.$current;

//Get count of complient warnings
$c = 'SELECT COUNT(*) AS count FROM issued_warnings WHERE (delivery_ts IS NOT NULL) AND (emp_ackd_ts IS NOT NULL) AND '.$terminated.' AND '.$current;

//Get count of unackowledged warnings
	//don't include Termination (Termination of Employment Advice)
$ua = 'SELECT COUNT(*) AS count FROM issued_warnings WHERE (emp_ackd_ts IS NULL) AND (brief_desc != "Termination of Employment Advice") AND '.$terminated.' AND '.$current;
	
//Get count of all warnings by brief category (including terminated)
$bc = 'SELECT brief_desc, COUNT(*) AS warning_count FROM issued_warnings WHERE '.$current.' AND '.$terminated.' GROUP BY brief_desc';

$dataArray = array();

$r_nd = $conn->query($nd);
$d_nd = $r_nd->fetch_assoc();
$dataArray['not_delivered'] = (int) $d_nd['count'];

$r_d = $conn->query($d);
$d_d = $r_d->fetch_assoc();
$dataArray['delivered'] = (int) $d_d['count'];

$r_c = $conn->query($c);
$d_c = $r_c->fetch_assoc();
$dataArray['complient'] = (int) $d_c['count'];

$r_ua = $conn->query($ua);
$d_ua = $r_ua->fetch_assoc();
$dataArray['not_acknowledged'] = (int) $d_ua['count'];

$innerArray = array();
$r_bc = $conn->query($bc);
if ($r_bc->num_rows > 0) {
    while($d_bc = $r_bc->fetch_assoc()) {
    	$innerArray[] = array("category"=>$d_bc['brief_desc'],"slip_count"=> (int) $d_bc['warning_count']);
		//$dataArray['category_count'][$d_bc['brief_desc']] = (int) $d_bc['warning_count'];
    }
}
$dataArray["category_count"] = $innerArray;


$masterArray = array("status_code"=>"success","status_desc"=>"","DATA"=>$dataArray);
$json = json_encode($masterArray);
echo($json);	

$conn->close();
?>