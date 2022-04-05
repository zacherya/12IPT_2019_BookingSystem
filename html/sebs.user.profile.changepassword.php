<?php
include('db_vars.php');
session_start();

$type = $_GET['type'] ?? "student"; //student(d) - teacher

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo("<center><h3>There was an internal error this function may not work as expected - ".$conn->connect_error."</h3></center>");
} 

$uuid = $_SESSION['uuid'] ?? "";

function generate_uuid() {
    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);
    $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
    return $uuid;
}

if(isset($_SESSION["profile_pw_token"])) {
	$_SESSION["profile_pw_token"] = generate_uuid();
	$tmpOldPwdTkn = $_SESSION["profile_pw_token"];
} else {
	$_SESSION["profile_pw_token"] = generate_uuid();
	$tmpOldPwdTkn = $_SESSION["profile_pw_token"];
}

?>

<form class="form-horizontal" id="user_change_pwd" data-submit_type="json" data-submit="true">

    <div class="form-group">
      <label class="control-label col-sm-4" for="uuid">Old Password</label>
      <div class="col-sm-5">
        <input type="password" class="form-control" id="old_password" value="<?php echo($_SESSION["profile_pw_token"]); ?>" name="old_password" disabled required="true" autocomplete="off">
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-4" for="firstname">New Password</label>
      <div class="col-sm-6 input-group" style="padding-right:15;padding-left:15;">          
        <input type="password" class="form-control" id="new_password" value="" placeholder="New Password" name="new_password" required="true" autofocus autocomplete="off">
        <span class="btn btn-default input-group-addon" id="show_new"><i class="fa fa-eye"></i></span>
      </div>
      <label class="control-label col-sm-4" for="firstname">Confirm New Password</label>
      <div class="col-sm-6 input-group" style="padding-right:15;padding-left:15;">          
        <input type="password" class="form-control" id="new_password_c" value="" placeholder="Confirm New Password" name="new_password_c" required="true" autocomplete="off">
      	<span class="btn btn-default input-group-addon" id="show_new_c"><i class="fa fa-eye"></i></span>
      </div>
    </div>
    

	
  </form>
  
  <script>
  
  function rearmBtns() {
  	$("#changepwdbtn").click(function() {
		showWaiting("Updating Login Information...");

	if($("#new_password").val() == $("#new_password_c").val()) {
	
  		$.ajax({

    url : 'remote-json.php?do=sebs.user.profile.changepassword&intent=save',
    type : 'POST',
    data : {
    	password: $("#new_password").val(),
    	old_password: $("#old_password").val()
    },
    dataType:'json',
    success : function(resp) {  
  			if(resp["status_code"] == "success") {   
  				hideWaiting();
  				$('div.modal-body .alert').remove();
  				$(".modal-title").html("Password Updated");
  				$(".modal-body").html("<h3 style='text-align:center;'><span class='label label-success'>Your password has been updated successfully!</span></h3>");
  				$(".modal-footer").html('<button type="button" class="btn btn-default" data-dismiss="modal">Done</button>');

       		} else if(resp["status_code"] == "warning") {  
       			hideWaiting();
    			$('div.modal-body .alert').remove();
  				var fieldsText = "<ul>";
				for(var i=0; i<resp["DATA"].length; i++) {
					fieldsText += "<li>"+resp["DATA"][i]+"</li>";
				}
				fieldsText += "</ul>";
				$("#user_change_pwd").before('<div class="alert alert-warning"><span><b>There was an error changing the password!</b><br>'+resp['status_desc']+'<br>'+fieldsText+'</span></div>');
       		} else {
       			hideWaiting();
    			$('div.modal-body .alert').remove();
  				var fieldsText = "<ul>";
				for(var i=0; i<resp["DATA"].length; i++) {
					fieldsText += "<li>"+resp["DATA"][i]+"</li>";
				}
				fieldsText += "</ul>";
				$("#user_change_pwd").before('<div class="alert alert-danger"><span><b>There was an error changing the password!</b><br>'+resp['status_desc']+'<br>'+fieldsText+'</span></div>');
       		}
    },
    error : function(request,error) {
    			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#user_change_pwd").before('<div class="alert alert-danger"><span><b>There was an error changing the password!</b><br>Unexplained</span></div>');

    	    }
		});
	} else {
		hideWaiting();
		$('div.modal-body .alert').remove();
  		$("#user_change_pwd").before('<div class="alert alert-danger"><span><b>The new passwords provided do not match</b><br>Please use the peak function to determine which password is incorrect!</span></div>');
	}
	});
	
	$('#show_new').on('mousedown', function() {
    	$("#new_password").attr("type","text");
	}).on('mouseup mouseleave', function() {
    	$("#new_password").attr("type","password");
	});
	
	$('#show_new_c').on('mousedown', function() {
    	$("#new_password_c").attr("type","text");
	}).on('mouseup mouseleave', function() {
    	$("#new_password_c").attr("type","password");
	});
  }


$(document).ready(function(){
	rearmBtns();	
});
  </script>
