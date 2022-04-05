<?php
$type = $_GET['type'] ?? "student"; //student(d) - teacher
?>

<h3>Step 1</h3>
<p>Ensure you have the Teacher Kiosk open in a separate window and it is logged in.</p>
<hr>
<h3>Step 2</h3>
<p>Select a year group to update</p>        
        <select class="form-control" id="tass_yrgroup" name="tass_yrgroup" onchange="updateLink()">
    		<option value="5">Year 5</option>
    		<option value="6">Year 6</option>
    		<option value="7">Year 7</option>
    		<option value="8">Year 8</option>
    		<option value="9">Year 9</option>
    		<option value="10">Year 10</option>
    		<option value="11">Year 11</option>
    		<option value="12">Year 12</option>
  		</select>
<hr>
<h3>Step 3</h3>
<p>Click <b><a id="redirectToTass" href="https://tass.slc.qld.edu.au/kiosk/remote-json.cfm?do=kiosk.students.student.search.results.list&year_grp=5" target="_blank">HERE</a></b> and copy all the text from the page to your clipboard</p>
<p><i>NOTE: This can be done clicking on the text on the next page, then pressing CONTROL and C at the same time. By clicking the here button you will be sent to another page without distrupting this one</i></p>
<hr>
<h3>Step 4</h3>
<p>Paste the text from the page in the box below</p>
<textarea data-gramm_editor="true" class="form-control" style="width:100%;height:50px;" id="jsonFTK"></textarea>
<hr>
<h3>Step 5</h3>
<div class="checkbox">
  <label><input type="checkbox" id="emailinfo" value=""><b>Email Login Information To Students?</b></label>
</div>
<p>If you choose to not send the Students their login information, they will all be distributed with the password "firs123" which follows the pattern of first 3 letters of their full first name in lowercase then the first 3 characters of their ID in lowercase. Therefore if the students name is Zachery and ID is 825291 then the password will be "zach825"</p>
<br>
<p>Press the "Update Database" button in the bottom right</p>

<script>
  	function updateLink() {
  		var valueChanged = $("select[name='tass_yrgroup']").val();
  		$("#redirectToTass").attr("href","https://tass.slc.qld.edu.au/kiosk/remote-json.cfm?do=kiosk.students.student.search.results.list&year_grp="+valueChanged);
  	}
  	
  	function updateDatabase() {
  		showWaiting('Updating Database...');
  		var selectedVal = $("select[name='tass_yrgroup']").val();
  		
$.ajax({

    url : 'remote-json.php?do=sebs.ultralink.tassweb.update&intent=save&source=tass',
    type : 'POST',
    data : {
    	emailinfo: $("#emailinfo").attr('checked')?true:false,
        tassdata: $("#jsonFTK").val()
    },
    dataType:'json',
    success : function(resp) {  
  			if(resp["status_code"] == "success") {   
  				//CleanUp         
  				$('div.modal-body #alert').remove();
       			$('div.modal-body h3').first().before('<div class="alert alert-success" id="alert" role="alert">'+ resp['status_desc'] +'</div>');
  				$("option[value='"+ selectedVal +"']").attr("disabled","true");
  				$(".modal-footer").html('<a type="button" class="btn btn-primary" href="index.php?do=user.dashboard">Done</a>');
  				$("#jsonFTK").val("");
  				hideWaiting();
       		} else {
       			hideWaiting();
       			$('div.modal-body #alert').remove();
       			$('div.modal-body h3').first().before('<div class="alert alert-danger" id="alert" role="alert">'+ resp['status_desc'] +'</div>');
       		}
    },
    error : function(request,error) {
    	$('div.modal-body #alert').remove();
        $('div.modal-body h3').first().before('<div class="alert alert-danger" id="alert" role="alert">ERROR UNWRAPPING: '+ +JSON.stringify(request) +'</div>');
    }
});
  		
  		
  	}
  		
 
</script>
