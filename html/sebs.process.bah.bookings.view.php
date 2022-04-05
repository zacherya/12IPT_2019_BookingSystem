<?php
include('db_vars.php');
session_start();

$bookingId = $_GET['booking_id'] ?? ""; //student(d) - teacher

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo("<center><h3>There was an internal error this function may not work as expected - ".$conn->connect_error."</h3></center>");
} 

$uuid = $_SESSION['uuid'] ?? "";

$sql = "SELECT * FROM rooms";

$roomsHtml = "";
if($result = $conn->query($sql)) {

	if ($result->num_rows > 0) {
    	// output data of each row
    	while($row = $result->fetch_assoc()) {
    		$rc = $row["room_code"];
    		$loc = $row["location"];
    		$name = $row["name"];
    		$cap = $row["capacity"];
    		
    		$roomsHtml .= '<option value="'.$rc.'" data-loc="'.$loc.'" data-cap="'.$cap.'">'.$name.' ('.$rc.')</option>';
    	}
	} else {
		echo("<h3><span class='label label-danger'>ERROR: No Rooms Found - Please setup rooms in MANAGE > ROOMS > CREATE ROOM</span></h3>");
	}
}
?>

<form class="form-horizontal" id="booking_viewedit" data-submit_type="json" data-submit="true">
	<h4>Overall Booking Information</h4>
	<hr>
    <div class="form-group">
      <label class="control-label col-sm-2" for="organiser_name">Organiser</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="organiser_name" value="Edward Wallace" placeholder="" name="organiser_name" disabled>
      </div>
      <div class="col-sm-3">
      	<a href="mailto:ewallace@slc.qld.edu.au" id="organiser_email" class="btn btn-default" style="float:left;" data-dismiss="modal"><span class="fa fa-envelope-o"></span> Email</a>
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-2" for="guest">Guest</label>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="guest" value="" placeholder="Not Applicable" name="guest" disabled>
      </div>
    </div>
    
    
    <div class="form-group">
      <label class="control-label col-sm-2" for="memo">Memo</label>
      <div class="col-sm-9">          
        <textarea data-gramm_editor="false" class="form-control" style="width:100%;height:75px;resize: none;" id="memo"></textarea>
      </div>
    </div>
    
    <br>
    <h4>Currently Selected Information</h4>
    <div class="form-group">
		<div class="col-sm-12"> 
			<div class="checkbox">
 				<label><input type="checkbox" id="emailinfo" value="">Use 'Auto Assume' when updating info for this booking? <i class="fa fa-info-circle" data-toggle="tooltip" title="The system will match changes below with future occurances including room change and match time change with the corrisponding day"></i></label>
			</div>
		</div>
	</div>
	<hr>
	<div class="form-group">
      <label class="control-label col-sm-2" for="room">Room</label>
      <div class="col-sm-5">          
        <select class="form-control" id="room" name="room">
    		<?php echo($roomsHtml); ?>
  		</select>
      </div>
      <label class="control-label col-sm-2" for="capacity">Capacity</label>
      <div class="col-sm-2"> 
      	<input type="number" class="form-control" id="capacity" value="1922" name="capacity" disabled>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="lastname">Location</label>
      <div class="col-sm-9"> 
      	<input type="text" class="form-control" id="location" value="STEM Building Ground Floor" name="location" disabled>
      </div>
    </div>
    
    <br>
    
    <div class="form-group">
      <label class="control-label col-sm-2" for="from">From</label>
      <div class="col-sm-4"> 
      	<input type="date" class="form-control" id="from_date" min="" value="" name="from_date">
      </div>
      <div class="col-sm-1"> 
      	<h5>at</h5>
      </div>
      <div class="col-sm-4"> 
      	<input type="time" class="form-control" id="from_time" value="" name="from_time">
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-2" for="to">To</label>
      <div class="col-sm-4"> 
      	<input type="date" class="form-control" id="to_date" min="" value="2012-02-02" name="to_date">
      </div>
      <div class="col-sm-1"> 
      	<h5>at</h5>
      </div>
      <div class="col-sm-4"> 
      	<input type="time" class="form-control" id="to_time" value="08:23" name="to_time">
      </div>
    </div>
    
    <br>
    <h4>Future Occurances</h4>
	<hr>
	<div class="form-group">
		<div class="col-sm-12">
			<table id="futureRecur" class="table table-striped">
				<thead><tr><th>Recur #</th><th>From</th><th>To</th><th>Room</th></tr></thead><tbody>
				</tbody>
			</table>
		</div>
	</div>
	
  </form>
  
  <div id="deletionConfirmation" class="dead">
  	<h3 style='text-align:center;'>Are you sure you want to delete<br><br><span class='label label-danger'>Booking #1234-5</span><br><br><h4 style='text-align:center;'><i>This action can't be undone and all data in accordance with this occurance of the booking will be deleted, inlcluding current loans and bookings!</i></h4></h3>
  </div>
  
  <script>
  
  function setupAll() {
  	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {   
					$("#organiser_name").val(data["DATA"]["root_info"]["organiser_name"]);
					$("#organiser_email").val(data["DATA"]["root_info"]["email"]);
					if (data["DATA"]["root_info"]["guest_booking"]==false) {
						$("#guest").attr("placeholder","Not Applicable");
					} else {
						$("#guest").val(data["DATA"]["root_info"]["guest_name"]);
					}
					$("#memo").val(data["DATA"]["root_info"]["info"]);
					$("#room").val(data["DATA"]["requested"]["room_code"]);
					$("#location").val(data["DATA"]["requested"]["location"]);
					if(data["DATA"]["requested"]["capacity"] <= 0) {
						$("#capacity").val("N/A");
					} else {
						$("#capacity").val(data["DATA"]["requested"]["capacity"]);
					}
					
					
					
					$("#from_time").val(data["DATA"]["requested"]["ft"]);
					$("#from_date").val(data["DATA"]["requested"]["fd"]);
					
					$("#to_time").val(data["DATA"]["requested"]["tt"]);
					$("#to_date").val(data["DATA"]["requested"]["td"]);
					
					var rtb = "";
					for (var i=0; i<data["DATA"]["future_occurrences"].length; i++) {
						var cur = data["DATA"]["future_occurrences"][i];
						rtb += '<tr style="cursor:pointer;"  data-booking_id="'+data["DATA"]["root_info"]["booking_id"]+'-'+cur["recur_id"]+'"><td>'+cur["recur_id"]+'</td><td>'+cur["fd_desc"]+' at '+cur["ft_desc"]+'</td><td>'+cur["td_desc"]+' at '+cur["tt_desc"]+'</td><td data-toggle="tooltip" title="'+cur["name"]+'">'+cur["room_code"]+' <i class="fa fa-info-circle"></i></td></tr>';
					}
					if (data["DATA"]["future_occurrences"].length == 0) {
						$("#futureRecur").html("<h3 style='text-align:center;'><span class='label label-default'>There are no future occurrences for this booking!</span></h3>");
					} else {
						$("#futureRecur tbody").html(rtb);
					}
					armTooltips(); 
					armRecurRows();
					
				} else {
					$("div.modal-body").html("<h3>There was an error loading the booking!<br>"+data["status_desc"]+"</h3>");
				}
				
			} else {
				
			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.process.bah.bookings.view&intent=display&booking_id=<?php echo($bookingId) ?>", true);
  		xhttp.send();
  }
  
 function changeTo(b) {
 	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {   
					$("#organiser_name").val(data["DATA"]["root_info"]["organiser_name"]);
					$("#organiser_email").val(data["DATA"]["root_info"]["email"]);
					if (data["DATA"]["root_info"]["guest_booking"]==false) {
						$("#guest").attr("placeholder","Not Applicable");
					} else {
						$("#guest").val(data["DATA"]["root_info"]["guest_name"]);
					}
					$("#memo").val(data["DATA"]["root_info"]["info"]);
					$("#room").val(data["DATA"]["requested"]["room_code"]);
					$("#location").val(data["DATA"]["requested"]["location"]);
					if(data["DATA"]["requested"]["capacity"] <= 0) {
						$("#capacity").val("N/A");
					} else {
						$("#capacity").val(data["DATA"]["requested"]["capacity"]);
					}
					
					
					
					$("#from_time").val(data["DATA"]["requested"]["ft"]);
					$("#from_date").val(data["DATA"]["requested"]["fd"]);
					
					$("#to_time").val(data["DATA"]["requested"]["tt"]);
					$("#to_date").val(data["DATA"]["requested"]["td"]);
					
					var rtb = "";
					for (var i=0; i<data["DATA"]["future_occurrences"].length; i++) {
						var cur = data["DATA"]["future_occurrences"][i];
						rtb += '<tr style="cursor:pointer;" data-booking_id="'+data["DATA"]["root_info"]["booking_id"]+'-'+cur["recur_id"]+'"><td>'+cur["recur_id"]+'</td><td>'+cur["fd_desc"]+' at '+cur["ft_desc"]+'</td><td>'+cur["td_desc"]+' at '+cur["tt_desc"]+'</td><td data-toggle="tooltip" title="'+cur["name"]+'">'+cur["room_code"]+' <i class="fa fa-info-circle"></i></td></tr>';
					}
					if (data["DATA"]["future_occurrences"].length == 0) {
						$("#futureRecur").html("<h3 style='text-align:center;'><span class='label label-default'>There are no future occurrences for this booking!</span></h3>");
					} else {
						$("#futureRecur tbody").html(rtb);
					}
					$("div.modal-header #mtitle").html("View Booking #"+b);
					armTooltips(); 
					armRecurRows();
					hideWaiting();
					
				} else {
					$("div.modal-body").html("<h3>There was an error loading the booking!<br>"+data["status_desc"]+"</h3>");
					$("div.modal-body").html("View Booking #"+b);
				}
				
			} else {
				
			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.process.bah.bookings.view&intent=display&booking_id="+b, true);
  		xhttp.send();
 }
  
function armRecurRows() {
	$("#futureRecur tbody tr").on('click', function() {
		showWaiting("Fetching Booking...");
		 changeTo($(this).data("booking_id"));
	});
}
  

$(document).ready(function(){
	armTooltips(); 	
	setupAll();
	
	$("#room").on('change', function() {
		$("#location").val($(this).find(':selected').data("loc"));
		if($(this).find(':selected').data("cap") <= 0) {
			$("#capacity").val("N/A");
		} else {
			$("#capacity").val($(this).find(':selected').data("cap"));
		}
		
	});
	
	
});
  </script>
