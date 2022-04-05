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

$sql = "SELECT DISTINCT(hse_name) AS house FROM users";

?>

<h4><span class="label label-danger">* Indicates a required field</span></h4>
<br>
<form class="form-horizontal" id="user_create" data-submit_type="json" data-submit="true">
    <div class="form-group">
      <label class="control-label col-sm-2" for="uuid">User ID *</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" id="uuid" value="" placeholder="<?php if($type=="student"){echo("123456");}else if($type=="teacher"){echo("jsmith");} ?>" name="uuid" autofocus required="true">
      </div>
      <div class="col-sm-5">
      	<h5>@slc.qld.edu.au</h5>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="firstname">Name</label>
      <div class="col-sm-5">          
        <input type="text" class="form-control" id="firstname" value="" placeholder="* First Name (Mitchel)" name="firstname" required="true">
      </div>
      <div class="col-sm-4">          
        <input type="text" class="form-control" id="firstname_pref" value="" placeholder="Pref Name (Mitch)" name="firstname_pref">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="middlename"></label>
      <div class="col-sm-9">          
        <input type="text" class="form-control" id="middlename" value="" placeholder="Middle Name (James)" name="middlename">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="lastname"></label>
      <div class="col-sm-9">          
        <input type="text" class="form-control" id="lastname" value="" placeholder="* Last Name (Foreman)" name="lastname" required="true">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="dob">DOB *</label>
      <div class="col-sm-2">          
        <select class="form-control" id="dob_day" name="dob_day">
    		<option>1</option>
  		</select>
      </div>
      <div class="col-sm-4">          
        <select class="form-control" id="dob_month" name="dob_month" onchange="updateDayDropDown()">
    		<option value="01">January</option>
      		<option value="02">Febuary</option>
      		<option value="03">March</option>
      		<option value="04">April</option>
      		<option value="05">May</option>
      		<option value="06">June</option>
      		<option value="07">July</option>
      		<option value="08">August</option>
      		<option value="09">September</option>
      		<option value="10">October</option>
      		<option value="11">November</option>
      		<option value="12">December</option>
  		</select>
      </div>
      <div class="col-sm-3">          
        <select class="form-control" id="dob_year" name="dob_year">
    		<option>2019</option>
  		</select>
      </div>
    </div>
    <div class="form-group">
  		<label class="control-label col-sm-2" for="gender">Gender *</label>
  		<div class="col-sm-3"> 
  			<select class="form-control" id="gender" name="gender">
    			<option value="M">Male</option>
    			<option value="F">Female</option>
  			</select>
  		</div>
  		<?php
  		if ($type=="student") {
  		echo('
  		<label class="control-label col-sm-3" for="year_grp">Year Level *</label>
  		<div class="col-sm-2"> 
  			<select class="form-control" id="year_grp" name="year_grp">
    			<option>5</option>
    			<option>6</option>
    			<option>7</option>
    			<option>8</option>
    			<option>9</option>
    			<option>10</option>
    			<option>11</option>
    			<option>12</option>
  			</select>
  		</div>
  		');
  		}
  		?>
	</div>
	<div class="form-group">
  		<label class="control-label col-sm-2" for="hse_name"><?php if($type=="student"){echo("House *");}else if($type=="teacher"){echo("House");} ?></label>
  		<div class="col-sm-4"> 
  			<select class="form-control" id="hse_name" name="hse_name" onchange="updateHouse()">
  				<?php
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
   		// output data of each row
    	while($row = $result->fetch_assoc()) {
    		if ($row['house'] != NULL) {
    			echo("<option>".$row['house']."</option>");
    		}
    	}
		echo("<option selected>Other</option>");
	} else {
		echo("<option selected>Other</option>");
	}

?>
  			</select>
  		</div>
  		<div class="col-sm-5"> 
  			<input type="text" class="form-control" id="hse_name_other" placeholder="Other House Name" name="hse_name_other" disabled>
  		</div>
	</div>
	<p>If you choose to not send the user their login information, they will all be distributed with a password which follows the pattern of initals in lowercase followed by their year of birth. Therefore if the users name is Zachery James Adams born in 2001 then the password will be "za2001"</p>

	<div class="form-group">
		<div class="col-sm-12"> 
			<div class="checkbox">
 				<label><input type="checkbox" id="emailinfo" value=""><b>Email Login Information?</b></label>
			</div>
		</div>
	</div>

	
  </form>
  
  <script>
  function updateDayDropDown() {
	var daysInCurMth = 0;
	var oldDayValue = $("select[name='dob_day']").val();
	var dropValue = $("select[name='dob_month']").val();
	if (dropValue == "02") {
		daysInCurMth = 29;
	} else if (dropValue == "01" || dropValue == "03" || dropValue == "05" || dropValue == "07" || dropValue == "08" || dropValue == "10" || dropValue == "12") {
		daysInCurMth = 31;
	} else {
		daysInCurMth = 30;
	}
	
	var html = '';
	for (i = 0; i < daysInCurMth; i++) {
		if (i > 8) {
			if (i+1 == oldDayValue) {
				html += "<option value='"+ (i+1) +"' selected>" + (i+1) + "</option>";
			} else {
				html += "<option value='"+ (i+1) +"'>" + (i+1) + "</option>";
			}
		} else {
			if ("0"+(i+1) == oldDayValue) {
				html += "<option value='0"+ (i+1) +"' selected>" + (i+1) + "</option>";
			} else {
				html += "<option value='0"+ (i+1) +"'>" + (i+1) + "</option>";
			}
		}
	}
	
	
	$("select[name='dob_day']").html(html);
}

function updateHouse() {
	if($("select[name='hse_name']").val() == "Other") {
		$("#hse_name_other").attr("disabled", false);
		$("#hse_name_other").attr("required", true);
	} else {
		$("#hse_name_other").attr("disabled", true);
		$("#hse_name_other").attr("required", false);
	}
}

function createYears() {
	var d = new Date();

	var minYr = 1905;
	var maxYr = d.getFullYear()-9;
	
	var html = '';
	for (i = maxYr; i > minYr-1; i--) {
  		html += "<option>" + (i) + "</option>";
	}
	
	$("select[name='dob_year']").html(html);
	
	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				console.log(data["setup"]);
			} else {
				console.log("ERROR GETTING TASS");
			}
  		};
  		//xhttp.open("GET", "https://tass.slc.qld.edu.au/kiosk/remote-json.cfm?do=kiosk.pastoralcare.entries.grid", true);
  		//xhttp.send();
}

$(document).ready(function(){
	updateDayDropDown();
	createYears();
	updateHouse();
	
	$("#adduserbtn").click(function() {
		showWaiting("Creating User...");
		
  		if ($("#user_create select[name='hse_name']").val() == "Other") {
			var houseName = $("#user_create input[name='hse_name_other']").val();
		} else {
			var houseName = $("#user_create select[name='hse_name']").val();
		}
  		$.ajax({

    url : 'remote-json.php?do=sebs.manage.users.add&intent=save',
    type : 'POST',
    data : {
    	un: $("#user_create input[name='uuid']").val(),
    		fn: $("#user_create input[name='firstname']").val(),
			fnp: $("#user_create input[name='firstname_pref']").val(),
			mn: $("#user_create input[name='middlename']").val(),
			ln: $("#user_create input[name='lastname']").val(),
			g: $("#user_create select[name='gender']").val(),
			dob: $("#user_create select[name='dob_year']").val() + '-' + $("#user_create select[name='dob_month']").val() + '-' + $("#user_create select[name='dob_day']").val(),
			ygrp: $("#user_create select[name='year_grp']").val(),
			hsen: houseName,
			em: $("#user_create input[name='uuid']").val()+"@slc.qld.edu.au"
    },
    dataType:'json',
    success : function(resp) {  
  			if(resp["status_code"] == "success") {   
  				hideWaiting();
  				$('div.modal-body .alert').remove();
  				$(".modal-title").html("Action Successful");
  				$(".modal-body").html("<h3 style='text-align:center;'><span class='label label-success'>The user has been successfully added to the SEBS system!</span><br><br><i>The account is active effective immediately!</i></h3>");
  				$(".modal-footer").html('<button type="button" class="btn btn-default" onclick="location.reload();">Done</button>');

       		} else {
       			hideWaiting();
    			$('div.modal-body .alert').remove();
  				var fieldsText = "<ul>";
				for(var i=0; i<resp["DATA"].length; i++) {
					fieldsText += "<li>"+resp["DATA"][i]+"</li>";
				}
				fieldsText += "</ul>";
				$("#user_create").before('<div class="alert alert-danger"><span><b> There was an error creating the user!</b><br>'+resp['status_desc']+'<br>'+fieldsText+'</span></div>');
       		}
    },
    error : function(request,error) {
    			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#user_create").before('<div class="alert alert-danger"><span><b> There was an error creating the user!</b><br>Unexplained</span></div>');

    	    }
		});
	});
	
});
  </script>
