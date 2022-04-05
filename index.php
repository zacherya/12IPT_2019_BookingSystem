<?php
session_start();

	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    	header("location: login.php");
    	exit;
	}

	if (!isset($_GET['do'])) {
		$_GET['do'] = "user.dashboard";
	}

	if ($_GET['do'] == "user.logout") {
		include('db_vars.php');
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die($conn->connect_error);
		}

		$sql = 'DELETE FROM auth_tokens WHERE token="'.$_SESSION['token'].'"';
		if ($conn->query($sql) === TRUE) {
			//success

		} else {
		    //error
		    //die("Logout error: ".$conn->connect_error);
		}

		// Unset all of the session variables
		$_SESSION = array();

		// Destroy the session.
		session_destroy();

		if(isset($_GET['reason'])) {
			$reason = "reason=".$_GET['reason'];
		} else {
			$reason = "reason=user";
		}

		// Redirect to login page
		header("location: login.php?".$reason);
		exit;
	}
?>
<html>

<head>
	<?php
		// Initiate Default Headers
		include('assets/defaultheaddata.php');
	?>
</head>
<body><!--style="overflow:hidden; margin:0"-->
	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
    	<?php include('html/user.navigation.topbar.php'); ?>
      </div>
    </nav>
    <div class="jumbotron dashboard-top">
    	<?php if($_GET['do'] == "user.dashboard") {
        echo('<img class="logo" src="resource/site-logo.png" width="125px">');
        }
        ?>
		<h1 class="site-title" style="margin-top:40px;">St Laurence's College</h1>
		<span class="site-title-sub">Sports Equipment Borrowing System (SEBS)</span>
    </div>
	<div class="container" style="margin-top: 2.5rem;">

		<div class="row">
		<div class="hidden-xs col-sm-4" id="linksgrid">

		<div class="row">
		<div class="slc-overlay col-xs-12">
			<div class="outer">
				<span>Quick Links</span>
			</div>
		</div>
		<div class="col-xs-12">
			<a href="index.php?do=sebs.manage.equiptment" id="sebs.manage.equiptment.plex" class="ZAPlaxLink">
			<div class="ZAPlaxImg">
   				<img src="resource/equiptment.png">
   				<div class="ZAPlaxImg-layer" data-img="resource/equiptment1.png"></div>
   				<div class="ZAPlaxImg-layer" data-img="resource/equiptment2.png"></div>
			</div>
			</a>
		</div>
		<div class="col-xs-12">
			<a href="index.php?do=sebs.manage.rooms" id="sebs.manage.rooms.plex" class="ZAPlaxLink">
			<div class="ZAPlaxImg">
   				<img src="resource/rooms.png">
   				<div class="ZAPlaxImg-layer" data-img="resource/rooms1.png"></div>
   				<div class="ZAPlaxImg-layer" data-img="resource/rooms2.png"></div>
			</div>
			</a>
		</div>
		<div class="col-xs-12">
			<a href="index.php?do=sebs.process.bah" id="sebs.process.bah.plex" class="ZAPlaxLink">
			<div class="ZAPlaxImg">
   				<img src="resource/bookinghire.png">
   				<div class="ZAPlaxImg-layer" data-img="resource/bookinghire1.png"></div>
   				<div class="ZAPlaxImg-layer" data-img="resource/bookinghire2.png"></div>
			</div>
			</a>
		</div>
		</div>

		</div>
		<div class="col-xs-12 col-sm-8" id="windowGrid">

		<div class="wrapper" style="margin-top: 0;height: 66%;" data-id="<?php echo($_GET['do']); ?>">
			<?php

		//CHECK if query from do exist
		// DO queries exist in separate .php files and are included in the master remote-json.php file
		$filename = 'html/'.$_GET['do'].'.php';
		if (file_exists($filename)) {
			include('html/'.$_GET['do'].'.php');
		} else {
			echo('
					<div class="alert alert-danger">

                    <span>
                      <b> Page Error 404 - </b>The item you requested either isn\'t available to you or doesn\'t exist. Please contact your system admin for further assistance</span>
					</div>');
		}

	  ?>
		</div>
		</div>

	</div>
	<div id="remotecatcher" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="mtitle">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Loading Content...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php
		// Initiate Default Footers
		include('assets/defaultfootdata.php');
	?>
</body>
</html>
