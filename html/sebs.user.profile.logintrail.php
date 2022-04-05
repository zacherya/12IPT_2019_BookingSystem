<?php
include('db_vars.php');
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo("<center><h3>There was an internal error - ".$conn->connect_error."</h3></center>");
} 

$uuid = $_SESSION['uuid'] ?? "";

$ll = $_GET['lazyload'] ?? true;
if (json_decode($ll) == true) {
	$lazyload = " LIMIT 12";
} else {
	$lazyload = "";
}

$sql = "SELECT login_ts AS timestamp, login_flg AS status, user_agent, remote_addr AS ip_address, referrer, seen AS presented FROM auth_history WHERE uuid='$uuid' ORDER BY timestamp desc".$lazyload;

			$totalCountSql = "SELECT COUNT(*) AS count FROM auth_history WHERE uuid='$uuid' ORDER BY login_ts desc";
			$totalCount = 0;
			$res = $conn->query($totalCountSql);
			while($row = $res->fetch_assoc()) {
				$totalCount = $row['count'];
			} 
?>

<p><i>All login information is retained for 7 days unless the historic login item is a failed attempt and is unacknoledged (seen) by you!</i></p>
<h4><span class="label label-primary">Showing Results 
		<?php 
			if (json_decode($ll) == true) {
				
				if($totalCount < 12){
					echo($totalCount);
				} else {
					echo("12");
				}
			} else {
				echo($totalCount);
			}
		?> out of 
		<?php
			echo($totalCount);
		?></span></h4>
<table id="sebs.user.profile.logintrail.list" class="table table-striped table-condensed">
<?php
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
   		// output data of each row
		echo("<thead><tr><th>Timestamp</th><th>IP Address</th><th>Status</th></tr></thead><tbody>");
    	while($row = $result->fetch_assoc()) {
    		$ts=str_replace(' ', ' at ', date_format(date_create($row['timestamp']),"d/m/Y h:ia"));
    		$ua=$row['user_agent'];
    		$ip=$row['ip_address'];
    		$st=$row['status'];
    		echo("<tr><td>$ts</td><td>$ip</td>");
    			if ($st == "S") {
    				echo("<td class='success'>Success</td>"); //column
    			} else if ($st == "F") {
    				echo("<td class='danger'>Failed</td>"); //column
    			} else {
    				echo("<td>Unknown</td>"); //column
    			}
    		echo("</tr>");
    	}
    	echo("</tbody>"); //end row
		
		$doUpdate = $_GET['presented'] ?? false;
		if (json_decode($doUpdate) == true) {
			$updateSeen = "UPDATE auth_history SET seen=true WHERE uuid='$uuid'";
			$conn->query($updateSeen);
		}
		
		// Delete all login history if the historic item is either (SEEN and a FAILED attempt OR the login attempt was a SUCCESS) 
		//		and the current date take a week is greater than the timestamp of the login item
		$removeSeen = "DELETE FROM auth_history WHERE ((seen=true AND login_flg='F') OR (login_flg='S')) AND (login_ts < DATE_ADD(NOW(), INTERVAL -1 WEEK))";
		$conn->query($removeSeen);
		
		
		
		
		
		
	} else {
		echo("<center><h3>There is no login history for the current user!</h3></center>");
	}

?>
</table>

<script>
var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		var data = JSON.parse(this.responseText);
		if (data["status_code"] == "success") {
			if( /AndUser IDroid|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				var tableHtml = "<thead class=\'\'><tr><th>Timestamp</th><th>User Agent</th><th>IP Address</th><th>Status</th></tr></thead><tbody>";
			} else {
				var tableHtml = "<thead class=\'\'><tr><th>Timestamp</th><th>User Agent</th><th>IP Address</th><th>Status</th></tr></thead><tbody>";
			}
			for(i = 0; i < data["DATA"].length; i++){
				if (data["DATA"][i]["status"] == "S") {
					var statusHtml = "<td class='success'>"+data["DATA"][i]["status"]+"</td>";
				} else if (data["DATA"][i]["status"] == "F") {
					var statusHtml = "<td class='danger'>"+data["DATA"][i]["status"]+"</td>";
				} else {
					var statusHtml = "<td>"+data["DATA"][i]["status"]+"</td>";
				}
				tableHtml += "<tr><td>"+data["DATA"][i]["timestamp"]+"</td><td>"+data["DATA"][i]["user_agent"]+"</td><td>"+data["DATA"][i]["ip_address"]+"</td>"+statusHtml+"</tr>";
			}
			tableHtml += "</tbody>";
			document.getElementById('sebs.user.profile.logintrail.list').innerHTML = tableHtml;
		} else {
			var tableHtml = "<h3 id=\'tableerror\'>";
			tableHtml += data["status_desc"];
			tableHtml += "</h3>";
			document.getElementById('sebs.user.profile.logintrail.list').innerHTML = tableHtml;
   		}
  	};
  	xhttp.open("GET", "remote-json.php?do=sebs.user.profile.logintrail.list&presented=true", true);
  	xhttp.send();

</script>
