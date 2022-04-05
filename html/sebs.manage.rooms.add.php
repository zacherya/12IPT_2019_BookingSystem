<?php
include('db_vars.php');
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo("<center><h3>There was an internal error this function may not work as expected - ".$conn->connect_error."</h3></center>");
}

$roomCode = $_GET['room_code'] ?? 0;

$sqlL = "SELECT DISTINCT(location) FROM rooms";

$locationHtml = "";
if($result = $conn->query($sqlL)) {

	if ($result->num_rows > 0) {
    	// output data of each row
    	while($row = $result->fetch_assoc()) {
    		$loc = $row["location"];

    		$locationHtml .= '<option value="'.$loc.'">'.$loc.'</option>';
    	}
	} else {
		echo("<h3><span class='label label-danger'>ERROR: No Locations Found - Please setup rooms with a Location in MANAGE > ROOMS > CREATE Rooms</span></h3>");
	}
}
?>

<form class="form-horizontal" id="room_new" data-submit_type="json" data-submit="true">
	<h4>Room Information</h4>
	<hr>
    <div class="form-group">
			<label class="control-label col-sm-2" for="name">Room ID</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" id="room_code" value="" placeholder="R1" name="room_code">
      </div>
			<label class="control-label col-sm-3" for="capacity">Room Capacity</label>
			<div class="col-sm-2">
				<input type="number" class="form-control" id="capacity" placeholder="124" name="capacity" required="required">
			</div>
    </div>

		<div class="form-group">
			<label class="control-label col-sm-2" for="name">Name</label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="name" value="" placeholder="Room 1" name="name">
			</div>
		</div>

		<h4>Room Location</h4>
		<hr>
    <div class="form-group">
  		<label class="control-label col-sm-3" for="location">Location</label>
  		<div class="col-sm-8">
  			<select class="form-control" id="location" name="location" onchange="updateLocation()">
  			<?php echo($locationHtml); ?>
  			<option value="Other" selected>Other Location</option>
  			</select>
  		</div>

		</div>
		<div class="form-group">
  		<label class="control-label col-sm-3" for="location">Other Location</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="location_other" placeholder="STEM Building Level 1" name="location_other" required="required">
			</div>
		</div>





  </form>


  <script>

	function updateLocation() {
 	if($("select[name='location']").val() == "Other") {
 		$("#location_other").attr("disabled", false);
 		$("#location_other").attr("required", true);
 	} else {
 		$("#location_other").attr("disabled", true);
 		$("#location_other").attr("required", false);
 	}
 }

function rearmBtns() {
	$("#room").on('change', function() {
		$("#location").val($(this).find(':selected').data("loc"));
		if($(this).find(':selected').data("cap") <= 0) {
			$("#capacity").val("N/A");
		} else {
			$("#capacity").val($(this).find(':selected').data("cap"));
		}

	});


	$("#addroombtn").click(function() {
		showWaiting("Adding room...");

		if ($("#room_new select[name='location']").val() == "Other") {
		var location = $("#room_new input[name='location_other']").val();
	} else {
		var location = $("#room_new select[name='location']").val();
	}
  		$.ajax({

    url : 'remote-json.php?do=sebs.manage.rooms.add&intent=save',
    type : 'POST',
		data : {
    	room_code: $("#room_new input[name='room_code']").val(),
    	name: $("#room_new input[name='name']").val(),
			location: location,
			capacity: $("#room_new input[name='capacity']").val()
    },
    dataType:'json',
    success : function(resp) {
  			if(resp["status_code"] == "success") {
  				hideWaiting();
  				$('div.modal-body .alert').remove();
  				$(".modal-title").html("Room Added Successfully");
  				$(".modal-body").html("<h3 style='text-align:center;'><span class='label label-success'>The room has been successfully added!</span></h3>");
  				$(".modal-footer").html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload();">Done</button>');

       		} else {
       			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#room_new").before('<div class="alert alert-danger"><span><b> There was an error adding the room!</b><br>'+resp['status_desc']+'</span></div>');

       		}
    },
    error : function(request,error) {
    			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#room_new").before('<div class="alert alert-danger"><span><b> There was an error adding the room!</b><br>Unexplained</span></div>');

    	    }
		});
	});
}

$(document).ready(function(){
	armTooltips();

	rearmBtns();

});

  </script>
