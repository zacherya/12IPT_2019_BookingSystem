<?php

//CHECK if do query is present
if (isset($_GET['do'])) {
	
	//CHECK if query from do exist
	// DO queries exist in separate .php files and are included in the master remote-json.php file
	$filename = 'html/'.$_GET['do'].'.php';
	if (file_exists($filename)) {
		include('html/'.$_GET['do'].'.php');
	} else {
		echo('<div class="container-fluid">
          <div class="alert alert-danger">
                    
                    <span>
                      <b> Page Error - </b>The item you requested either isn\'t available to you or doesn\'t exist. Please contact your system admin for further assistance</span>
                  </div>
    
        </div>');
	}
} else {
	echo('<div class="container-fluid">
          <div class="alert alert-danger">
                    
                    <span>
                      <b> Page Error - </b>The item you requested either isn\'t available to you or doesn\'t exist. Please contact your system admin for further assistance</span>
                  </div>
    
        </div>');
}


?>