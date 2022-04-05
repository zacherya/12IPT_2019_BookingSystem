<?php

?>

function readyHouses() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		var pars = JSON.parse(this.responseText);
		if (pars["status_code"] == "success") {
			var houseHtml = "<option selected>Other</option>";
			for(var i=0; i< pars["DATA"]["houses"].length; i++) {
				houseHtml += "<option>";
  				houseHtml += pars["DATA"]["houses"][i];
  				houseHtml += "</option>";
			}
			$('#hse_name').html(houseHtml);
			updateHouse();
			
		} else {
			$('#filter_house').attr('disabled','true');
			console.log("internal error in getting basic user info");
   		}
		
    }
  };
  xhttp.open("GET", "remote-json.php?do=sebs.manage.users", true);
  xhttp.send();
}

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
		if($("#utype").val() == "S") {
			$("#hse_name_other").attr("required", true);
		}
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
	readyHouses();
	createYears();
	updateDayDropDown();
	
	$('#showpwd').on('mousedown', function() {
    	$("#password").attr("type","text");
	}).on('mouseup mouseleave', function() {
    	$("#password").attr("type","password");
	});
	
	$('#goback').on('click', function() {
    	switch ($(this).data("goto")) {
    		case "students":
    			window.location.href = "index.php?do=sebs.manage.users&filter=students";
    			break;
    		case "teachers":
    			window.location.href = "index.php?do=sebs.manage.users&filter=teachers";
    			break;
    		default:
    			window.location.href = "index.php?do=sebs.manage.users&filter=students";
    			break;
    	}
	});
	
	// Variable to hold request
var request;

// Bind to the submit event of our form
$("#user_viewedit").submit(function(event){
	showWaiting('Submitting Changes...');
    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    // Abort any pending request
    if (request) {
        request.abort();
    }
    // setup some local variables
    var $form = $(this);

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();
    
    if (serializedData["password"] == "") {
    	serializedData["password"] = null;
    }

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    $inputs.prop("disabled", true);
    
    // Fire off the request to remote json
    $.ajax({
            type:"POST",
            url: "remote-json.php?do=sebs.manage.users.add&intent=update",
            data:serializedData,
            success: function (response){
            	if (response['status_code'] == "success") {
					hideWaiting();
					$('#alert').remove();
					$('div.panel').first().before('<div class="alert alert-success" id="alert" role="alert">'+ response['status_desc'] +'</div>');
					$('#password').val("");
					$inputs.prop("disabled", false);
					updateHouse();
				} else if (response['status_code'] == "warning") {
					hideWaiting();
    				$('#alert').remove();
  					var fieldsText = "<ul>";
					for(var i=0; i<response["DATA"].length; i++) {
						fieldsText += "<li>"+response["DATA"][i]+"</li>";
					}
					fieldsText += "</ul>";
					$("div.panel").first().before('<div class="alert alert-warning" id="alert" role="alert"><span><b>There was a warning while updating the user profile!</b><br>'+response['status_desc']+'<br>'+fieldsText+'</span></div>');
       				$inputs.prop("disabled", false);
					updateHouse();
				} else {
					hideWaiting();
					$('#alert').remove();
					$('div.panel').first().before('<div class="alert alert-danger" id="alert" role="alert"><b>There was an error updating the user profile!</b><br>'+ response['status_desc'] +'</div>');
				
					$inputs.prop("disabled", false);
					updateHouse();
				}
            },
            error: function ($xhr){
            	var response = $xhr.responseJSON;
				hideWaiting();
				$('#alert').remove();
				if (!response) {
					$('div.panel').first().before('<div class="alert alert-danger" id="alert" role="alert"><b>Network Error</b><br>Please check your internet connection and try again!</div>');
				} else {
					$('div.panel').first().before('<div class="alert alert-danger" id="alert" role="alert">'+ response['status_desc'] +'</div>');
				}
				
				$inputs.prop("disabled", false);
				updateHouse();
            }
        });

	});
	
	
});
