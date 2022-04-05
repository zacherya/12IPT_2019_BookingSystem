<?php
// Initialize the session
session_start();

include('db_vars.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die($conn->connect_error);
} 

$sql = 'DELETE FROM auth_token WHERE token="'.$_SESSION['token'].'"';
if ($conn->query($sql) === TRUE) {
	//success
	
} else {
    //error
    die("Logout error: ".$conn->connect_error);
}
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: login.php");
exit;
?>