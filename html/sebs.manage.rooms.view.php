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

<form class="form-horizontal" id="room_viewedit" data-submit_type="json" data-submit="true">
	<h4>Room Information</h4>
	<hr>
    <div class="form-group">
			<label class="control-label col-sm-2" for="name">Room ID</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" id="room_code" value="CRT1" placeholder="" name="room_code" disabled>
      </div>
			<label class="control-label col-sm-3" for="capacity">Room Capacity</label>
			<div class="col-sm-2">
				<input type="number" class="form-control" id="capacity" placeholder="" name="capacity" required="required">
			</div>
    </div>

		<div class="form-group">
			<label class="control-label col-sm-2" for="name">Name</label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="name" value="Sport Ball" placeholder="" name="name">
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

  <div id="deletionConfirmation" class="dead">
  	<h3 style='text-align:center;'>Are you sure you want to delete<br><br><span class='label label-danger'>Booking #1234-5</span><br><br><h4 style='text-align:center;'><i>This action can't be undone and all data in accordance with this occurance of the booking will be deleted, inlcluding current loans and bookings!</i></h4></h3>
  </div>

  <script>

  function setupAll() {
  	changeTo("<?php echo($roomCode) ?>");
  }

 function changeTo(b) {
 	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {
					$("#room_code").val(b);
					$("#capacity").val(data["DATA"]["capacity"]);
					$('#location').val(data["DATA"]["location"]);
						$("#location_other").attr("disabled", true);
						$("#location_other").attr("required", false);
					$("#name").val(data["DATA"]["name"]);
					$("#capacity").val(data["DATA"]["capacity"]);

					$("#deleteroombtn").attr("data-room_code", b);

					armTooltips();
					hideWaiting();

				} else {
					$("div.modal-body").html("<h3>There was an error loading the room!<br>"+data["status_desc"]+"</h3>");
					$("div.modal-footer").html('<button type="button" class="btn btn-default" data-dismiss="modal">Done</button>');
				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.rooms.view&intent=display&room_code="+b, true);
  		xhttp.send();
 }

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
	$("#capacity").on('change', function() {
		$("#location").val($(this).find(':selected').data("loc"));
		if($(this).find(':selected').data("cap") <= 0) {
			$("#capacity").val("N/A");
		} else {
			$("#capacity").val($(this).find(':selected').data("cap"));
		}

	});

	$("#deleteroombtn").click(function() {
		var newTitle = 'Confirm Deletion of Room '+$(this).data("room_code");
		var newHtml = '<h3 style="text-align:center;">Are you sure you want to delete<br><br><span class="label label-danger">'+$(this).data("room_code")+' - '+$("#name").val()+'</span><br><br></h3><h4 style="text-align:center;"><i>This action can\'t be undone and all data in accordance with this room will be deleted, including current bookings!</i></h4>';
		var footHtml = '<button type="button" class="btn btn-success" style="float:left;" data-dismiss="modal">Cancel</button><button id="confirmdeletebtn" data-room_code="'+$(this).data("room_code")+'" type="button" class="btn btn-danger">Confirm Action</button>';
		$(".modal-title").html(newTitle);
		$(".modal-body").html(newHtml);
		$(".modal-footer").html(footHtml);
		rearmBtns();
	});

	$("#confirmdeletebtn").click(function() {
		showWaiting("Confirming Deletion with Server...");
		var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {
					var newTitle = 'Room Deleted';
					var newHtml = '<h3 style="text-align:center;"><span class="label label-success">'+data["status_desc"]+'</span><br><br></h3>';
					$(".modal-title").html(newTitle);
					$(".modal-body").html(newHtml);
					$("div.modal-footer").html('<button onclick="location.reload();" type="button" class="btn btn-default" data-dismiss="modal">Done</button>');
				} else {
					hideWaiting();
    				$('div.modal-body .alert').remove();
  					$("div.modal-body h3").first().before('<div class="alert alert-danger"><span><b> There was an error removing the room!</b><br>'+data['status_desc']+'</span></div>');
				}

			} else {
				hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("div.modal-body h3").first().before('<div class="alert alert-danger"><span><b> There was an error removing the room!</b><br>Unknown Reason, Check Internet Connection and Reload Page!</span></div>');
			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.rooms.remove&intent=save&room_code="+$(this).data("room_code"), true);
  		xhttp.send();
	});

	$("#updateroombtn").click(function() {
		showWaiting("Updating Room...");

  		if ($("#room_viewedit select[name='location']").val() == "Other") {
			var location = $("#room_viewedit input[name='location_other']").val();
		} else {
			var location = $("#room_viewedit select[name='location']").val();
		}
  		$.ajax({

    url : 'remote-json.php?do=sebs.manage.rooms.add&intent=update',
    type : 'POST',
    data : {
    	room_code: $("#room_viewedit input[name='room_code']").val(),
    	name: $("#room_viewedit input[name='name']").val(),
			location: location,
			capacity: $("#room_viewedit input[name='capacity']").val()
    },
    dataType:'json',
    success : function(resp) {
  			if(resp["status_code"] == "success") {
  				hideWaiting();
  				$('div.modal-body .alert').remove();
  				$(".modal-title").html("Room Updated Successfully");
  				$(".modal-body").html("<h3 style='text-align:center;'><span class='label label-success'>The room has been successfully updated!</span><br><br>The changes may have taken effect on current bookings but won't effect booking data integrity.</h3>");
  				$(".modal-footer").html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload();">Done</button>');

       		} else {
       			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#room_viewedit").before('<div class="alert alert-danger"><span><b> There was an error updating the room!</b><br>'+resp['status_desc']+'</span></div>');

       		}
    },
    error : function(request,error) {
    			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#room_viewedit").before('<div class="alert alert-danger"><span><b> There was an error updating the room!</b><br>Unexplained</span></div>');

    	    }
		});
	});
}

$(document).ready(function(){
	armTooltips();
	setupAll();

	rearmBtns();

});
  </script>
