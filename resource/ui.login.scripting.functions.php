<?php 
	if (isset($_GET['do'])) {
		if ($_GET['do'] == "ui.login.scripting.functions") {
			echo("<h1>Resource Extraction</h1><br><hr><h4>Access is denied to this resource, it must be requested from server side!</h4>");
			http_response_code(400);
			die();
		}
	}
	
?>