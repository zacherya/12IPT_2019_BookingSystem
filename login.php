<?php
session_start(); 

	if(isset($_SESSION["loggedin"])){
		if ($_SESSION["loggedin"] === true) {
    		header("location: index.php");
    		exit;
    	}
	}
	
	if(isset($_GET['do'])) {
		if($_GET['do'] == "recovery") {
			
		}
	}
	
	$reason = $_GET['reason'] ?? "user"
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        
        <link rel="icon" type="image/x-icon" href="favicon.png"/>

        <title>SLC Sports Equiptment Borrowing System</title>
        
        <!-- Initiate Core CSS -->
        <link type="text/css" rel="stylesheet" href="include-css.php?do=ui.login">
        <link type="text/css" rel="stylesheet" href="include-css.php?do=portal.zaloading">
        
        <!-- Initiate Core Login JS Script -->
        <script src="remote-js.php?do=portal.jquery.latest"></script>
        <script src="remote-js.php?do=portal.zaloading"></script>
  		<script src="remote-js.php?do=login.scripts"></script>

        <!-- Initiate Custom Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>
    <body class="auth-body">

        <div class="container">
            <div class="row">
            
                <div class="col-md-4 col-md-offset-4 gridLogin">
                
				<img class="logo" src="resource/site-logo.png" width="125px"/>
				
				<form name=login id="flogin">
                    <div class="login-panel panel panel-default" id="login">
                        <div class="panel-heading">
                            <h3 class="panel-title">SEBS - Login</h3>
                        </div>
                        <div class="panel-body">
                        		<?php
                        		if($reason == "timeout") {
                        			echo('<div class="alert alert-warning" id="alert" role="alert">Your session has expired, please login again!</div>');
                        		}
                            	?>
                                <fieldset>
                                    <div class="input-group">
    									<span class="input-group-addon">Username</span>
                                        <input class="form-control" placeholder="" name="username" type="text" autofocus value="" required="true">
                                    </div>
                                    <div class="input-group">
    									<span class="input-group-addon">Password</span>
                                        <input class="form-control" placeholder="" name="password" type="password" value="" required="true">
                                    </div>
                                    <div class="input-group" id="captcha"></div>
                                    <input type="hidden" name="token" value="">
                                    <input type="hidden" name="intent" value="save">
                                </fieldset>
                            
                        </div>
                        <div class="panel-footer">
                        	<button id="resetPswdBtn" class="btn btn-link" type="button" tabindex="9">Forgot Password?</button>
                        	<button id="loginBtn" class="btn btn-primary" tabindex="8">Login</button>
                        </div>
                    </div>
                </form>
				<form id="fpasswordreset">
                    <div class="login-panel panel panel-default" id="passwordreset" style="display:none;">
                        <div class="panel-heading">
                            <h3 class="panel-title">SEBS - Password Reset</h3>
                        </div>
                        <div class="panel-body">
                            <p><b>Please enter the email address linked to your account</b><br>If there is an account linked to that email address you will recieve an email with a link.</p>
                            
                                <fieldset>
                                    <div class="input-group">
    									<span class="input-group-addon">Email</span>
                                        <input class="form-control" placeholder="" name="email" type="email" autofocus>
                                    </div>
                                    <div class="input-group" id="captcha"></div>
                                    <input type="hidden" name="intent" value="reset">
                                </fieldset>
                                
                        </div>
                        <div class="panel-footer">
                        	<button id="back-button" class="btn btn-primary" type="button" tabindex="9"><i class="fa fa-chevron-left fa-1x"></i></i> Back</button>
                        	<button id="send-reset" class="btn btn-danger" tabindex="8">Reset Password</button>
                        </div>
                    </div>
                </form>
                    
                    <div class="login-panel panel panel-success" id="infoSupplied" style="display:none;">
                        <div class="panel-heading">
                            <h3 class="panel-title">SEBS - Password Reset</h3>
                        </div>
                        <div class="panel-body">
                        	<h3>Success</h3>
                            <p></p>
                        </div>
                        <div class="panel-footer">
                        	<a href="login.php" class="btn btn-primary">Go back to Login</a>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
        
        

        

    </body>
</html>
