<?php

?>

function empProcess(ec) {
	if(ec == "<?php echo($_SESSION["uuid"]); ?>") {
		window.location.href = "index.php?do=sebs.user.profile";
	} else {
		window.location.href = "index.php?do=sebs.manage.users.view&username="+ec;
	}
}

function readyFilters() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		var pars = JSON.parse(this.responseText);
		if (pars["status_code"] == "success") {
			var houseHtml = "<option value='' selected>All Houses</option>";
			for(var i=0; i< pars["DATA"]["houses"].length; i++) {
				houseHtml += "<option>";
  				houseHtml += pars["DATA"]["houses"][i];
  				houseHtml += "</option>";
			}
			$('#filter_house').html(houseHtml);
			
			var gradeHtml = "<option value='' selected>All Grades</option>";
			for(var i=0; i< pars["DATA"]["grades"].length; i++) {
				gradeHtml += "<option>";
  				gradeHtml += pars["DATA"]["grades"][i];
  				gradeHtml += "</option>";
			}
			$('#filter_yrgrp').html(gradeHtml);
		} else {
			$('#filter_house').attr('disabled','true');
			$('#filter_yrgrp').attr('disabled','true');
			console.log("internal error in getting basic user info");
   		}
		
    }
  };
  xhttp.open("GET", "remote-json.php?do=sebs.manage.users", true);
  xhttp.send();
  
  	$("#filter_house").change(function () {
    	getData($(this).val(),$('#filter_yrgrp').val(),$("#userquery").val());
	});
	$("#filter_yrgrp").change(function () {
    	getData($('#filter_house').val(),$(this).val(),$("#userquery").val());
	});
	$("#userquery").on('input',function(e){
    	getData($('#filter_house').val(),$('#filter_yrgrp').val(),$(this).val());
	});
}

function inputDbValues(j) {
		if (j["status_code"] == "success") {
		
			var html = '';
			for (i = 0; i < j["DATA"]["jobs_list"].length; i++) {
				html += "<option value='"+ j["DATA"]["jobs_list"][i]["job_id"] +"'>" + j["DATA"]["jobs_list"][i]["job_name"] + "</option>";
			}
			$("select[name='job_id[]']").html(html);
			
		} else {
			document.getElementById('empcreate').outerHTML = '';
			console.log("error in getting Job List");
   		}
}

function updateDayDropDown() {
	var daysInCurMth = 0;
	var oldDayValue = $("#empcreate select[name='dob-day']").val();
	var dropValue = $("#empcreate select[name='dob-month']").val();
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
	
	
	$("#empcreate select[name='dob-day']").html(html);
}

function createYears() {
	var d = new Date();

	var minYr = 1905;
	var maxYr = d.getFullYear();
	
	var html = '';
	for (i = maxYr; i > minYr-1; i--) {
  		html += "<option>" + (i) + "</option>";
	}
	
	$("#empcreate select[name='dob-year']").html(html);
}
//.eq( 5 )
function createEmp() {
	showWaiting();
	$.post("remote-json.php?do=admin.employee.masterfile.addrecord", {
    emp_code: $("#empcreate input[name='emp_code']").val(),
    emp_id: $("#empcreate input[name='emp_id']").val(),
	first_name: $("#empcreate input[name='first_name']").val(),
	first_name_pref: $("#empcreate input[name='first_name_pref']").val(),
	middle_name: $("#empcreate input[name='middle_name']").val(),
	last_name: $("#empcreate input[name='last_name']").val(),
	dob: $("#empcreate select[name='dob-year']").val() + '-' + $("#empcreate select[name='dob-month']").val() + '-' + $("#empcreate select[name='dob-day']").val(),
	gender: $("#empcreate select[name='gender']").val(),
	mobile_no: $("#empcreate input[name='mobile_no']").val(),
	email: $("#empcreate input[name='email']").val(),
	address: $("#empcreate input[name='address']").val(),
	suburb: $("#empcreate input[name='suburb']").val(),
	postcode: $("#empcreate input[name='postcode']").val(),
	city: $("#empcreate input[name='city']").val(),
	country: $("#empcreate input[name='country']").val(),
	emg1_contact_name: $("#empcreate input[name='contact_name[]']").eq(0).val(),
	emg1_relationship: $("#empcreate input[name='relationship[]']").eq(0).val(),
	emg1_mobile_no: $("#empcreate input[name='mobile_no[]']").eq(0).val(),
	emg1_email: $("#empcreate input[name='email[]']").eq(0).val(),
	emg1_address: $("#empcreate input[name='address[]']").eq(0).val(),
	emg1_suburb: $("#empcreate input[name='suburb[]']").eq(0).val(),
	emg1_postcode: $("#empcreate input[name='postcode[]']").eq(0).val(),
	emg1_city: $("#empcreate input[name='city[]']").eq(0).val(),
	emg1_country: $("#empcreate input[name='country[]']").eq(0).val(),
	emg2_contact_name: $("#empcreate input[name='contact_name[]']").eq(1).val(),
	emg2_relationship: $("#empcreate input[name='relationship[]']").eq(1).val(),
	emg2_mobile_no: $("#empcreate input[name='mobile_no[]']").eq(1).val(),
	emg2_email: $("#empcreate input[name='email[]']").eq(1).val(),
	emg2_address: $("#empcreate input[name='address[]']").eq(1).val(),
	emg2_suburb: $("#empcreate input[name='suburb[]']").eq(1).val(),
	emg2_postcode: $("#empcreate input[name='postcode[]']").eq(1).val(),
	emg2_city: $("#empcreate input[name='city[]']").eq(1).val(),
	emg2_country: $("#empcreate input[name='country[]']").eq(1).val(),
	access: $("#empcreate input[name='access']:checked").val(),
	web_access: $("#empcreate input[name='web_access']:checked").val(),
	can_creategroup: $("#empcreate input[name='can_creategroup']:checked").val(),
	can_communicate: $("#empcreate input[name='can_communicate']:checked").val(),
	additional_info: $("#empcreate textarea[name='additional_info']").val(),
	comm_date: $("#empcreate input[name='comm_date']").val(),
  },
  function(data, status){
  var jsonResponse = JSON.parse(data);
  	if(jsonResponse["status_code"] == "success") {
  		hideWaiting();
  		$("#create h2").before('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="material-icons">close</i></button><span><b> SUCCESS</b><br>The employee has been created succesfully!</span></div>');
  		//alert("\nStatus: " + status + "Data: " + data);
  	} else {
  		hideWaiting();
  		$("#create h2").before('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="material-icons">close</i></button><span><b> There was an error creating the employee!</b><br>'+jsonResponse["status_desc"]+'</span></div>');

  	}
  });
}

$(document).ready(function(){
	//updateDayDropDown();
	//createYears();
	readyFilters();
});
