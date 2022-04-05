<?php

include('db_vars.php');

function BlockSQLInjection($str) {
	return str_replace(array("'","\"","'",'"',"&quot;\"'","&quot;"),"",$str);
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo('Connection Error - '+$conn->connect_error);
	die();
} 

if (isset($_GET['username'])) {
	$uuid = BlockSQLInjection($_GET['username']);
	$sql = "SELECT * FROM users WHERE uuid='$uuid'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
    	// output data of each row
    	while($row = $result->fetch_assoc()) {
    		$fn = $row['firstname'];
    		$ln = $row['lastname'];
    		$un = $row['uuid'];
    		
			echo("<h3 style='text-align:center;'>Are you sure you want to delete<br><br><span class='label label-danger'>$fn $ln ($un)</span><br><br><h4 style='text-align:center;'><i>This action can't be undone and all data in accordance with this user will be deleted, inlcluding current loans and bookings!</i></h4></h3>");
    	}
	} else {
		echo('<h3 style="text-align:center;">The user $uuid doesn\'t exist, please select another user!</h3>');
	}
} else {
	echo('<h3 style="text-align:center;">No User ID passed, this may be an internal system error. Please refresh the page or contact your administrator!</h3>');
}

?>

  
  <script>


function pdu() {
	$("#removeuserbtn").click(function() {
		showWaiting("Removing User Data...");
		var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {   
					hideWaiting();
  					$('div.modal-body .alert').remove();
  					$(".modal-title").html("Action Successful");
  					$(".modal-body").html("<h3 style='text-align:center;'><span class='label label-success'>The user has been successfully removed from the SEBS system!</span></h3>");
  					$(".modal-footer").html('<button type="button" class="btn btn-default" onclick="location.reload();">Done</a=button>');
				} else {
					hideWaiting();
    				$('div.modal-body .alert').remove();
  					$("div.modal-body h3").first().before('<div class="alert alert-danger"><span><b> There was an error removing the user!</b><br>'+resp['status_desc']+'</span></div>');
				}
				
			} else {
				hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("div.modal-body h3").first().before('<div class="alert alert-danger"><span><b> There was an error removing the user!</b><br>unexplained</span></div>');
			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.users.remove&intent=save&username=<?php if (isset($_GET['username'])) {echo(BlockSQLInjection($_GET['username']));}else{echo("");} ?>", true);
  		xhttp.send();
	});
	
}

$(document).ready(function(){
	pdu();
});
  </script>
