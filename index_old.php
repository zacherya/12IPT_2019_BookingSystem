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

		$sql = 'DELETE FROM login_token WHERE token="'.$_SESSION['token'].'"';
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
 
		// Redirect to login page
		header("location: login.php");
		exit;
	}
?>
<html>

<head>
	<title>Marmaduke Resturant Roster Management</title>
	<?php
		// Initiate Default Headers
		include('assets/defaultheaddata.php');
	?>
</head>
<body class="dark-edition">

	<div class="wrapper">
	
    <div class="sidebar" data-color="orange" data-background-color="black" data-image="">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo">
        <a href="http://zacadams.net" class="simple-text logo-normal">
          Marmaduke
        </a>
      </div>
      <div class="sidebar-wrapper">
        <?php include('html/user.navigation.sidebar.php'); ?>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
	  <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top " id="navigation-example">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="index.php?do=user.profile"><?php
			if(isset($_SESSION['pref_name'],$_SESSION['lastname'])){
				echo($_SESSION['pref_name']." ".$_SESSION['lastname']);
			} else if(isset($_SESSION['firstname'],$_SESSION['lastname'])){
				echo($_SESSION['firstname']." ".$_SESSION['lastname']);
			}
			?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation" data-target="#navigation-example">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
      <?php
	  if(isset($_SESSION["manager"])) {
				if ($_SESSION["manager"] == true) {
					echo('<form id="empsearch" class="navbar-form" method="get" action="index.php">
              <div class="input-group no-border">
				<input type="hidden" name="do" value="admin.employee.masterfile">
                <input type="text" name="query" value="" class="form-control" placeholder="Search Employee Name or ID">
                <button type="submit" class="btn btn-default btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
				
              </div>
            </form>');
				}
			}
			
		?>
			
			<ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="javscript:void(0)" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 10px;background-color: rgba(200, 200, 200, 0.2);">
                  <i class="material-icons">notifications</i>
                  <span class="notification">5</span>
                  <p class="d-lg-none d-md-block">Notifications</p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="javascript:void(0)"><i class="material-icons" style="padding-right:10px;">today</i>Next weeks roster hasn't been published</a>
                  <a class="dropdown-item" href="javascript:void(0)"><i class="material-icons" style="padding-right:10px;">error</i>There are errors with the upcoming roster</a>
                  <a class="dropdown-item" href="javascript:void(0)"><i class="material-icons" style="padding-right:10px;">gavel</i>There are labour breaches that require attention</a>
                  <a class="dropdown-item" href="javascript:void(0)"><i class="material-icons" style="padding-right:10px;">person</i>Employee John Smith (223517) has worked more than 10 hours in a school week</a>
                  <a class="dropdown-item" href="javascript:void(0)"><i class="material-icons" style="padding-right:10px;">person</i>Employee John Smith (223517) worked more than 4 hours on a school night</a>
                </div>
              </li>
			  <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:void(0)" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 10px;background-color: rgba(200, 200, 200, 0.2);">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    <?php
						if(isset($_SESSION['pref_name'],$_SESSION['lastname'])){
							echo($_SESSION['pref_name']." ".$_SESSION['lastname']);
						} else if(isset($_SESSION['firstname'],$_SESSION['lastname'])){
							echo($_SESSION['firstname']." ".$_SESSION['lastname']);
						}
					?>
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="index.php?do=user.profile">My Profile</a>
                  <a class="dropdown-item" href="javascript:void(0)">My Availabilities</a>
				  <hr>
                  <a class="dropdown-item exit" href="index.php?do=user.logout">Sign Out</a>
                </div>
              </li>
            </ul>
            
          </div>
        </div>
      </nav>
	  
      <!-- End Navbar -->
      
      <?php 
	
		//CHECK if query from do exist
		// DO queries exist in separate .php files and are included in the master remote-json.php file
		$filename = 'html/'.$_GET['do'].'.php';
		if (file_exists($filename)) {
			include('html/'.$_GET['do'].'.php');
		} else {
			echo('<div class="container-fluid" style="padding-top:100px;">
					<div class="alert alert-danger">
                    
                    <span>
                      <b> Page Error - </b>The item you requested either isn\'t available to you or doesn\'t exist. Please contact your system admin for further assistance</span>
					</div>
    
			</div>');
		}
	  include('html/user.navigation.footer.php'); 
	  echo('<script>document.getElementById("'.$_GET["do"].'").classList.add("active");</script>');
	  ?>
      <script>
        const x = new Date().getFullYear();
        let date = document.getElementById('date');
        date.innerHTML = '&copy; ' + x + date.innerHTML;
      </script>
    </div>
  </div>
  
  <?php include('html/plugin.page.scripts.php'); ?>
  
  
</body>
</html>