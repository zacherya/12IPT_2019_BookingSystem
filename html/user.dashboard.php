<?php
?>
<div class="sideswipe">
	<h2>Good Morning <?php
						if(isset($_SESSION['pref_name']) && $_SESSION['pref_name'] != ""){
							echo($_SESSION['pref_name']);
						} else if(isset($_SESSION['firstname'])){
							echo($_SESSION['firstname']);
						} else {
							echo("{NAME_ERROR}");
						}
					?>!</h2>
</div>
<div class="row">
		<div class="col-sm-12">
		  <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Your Room Bookings Today</h3>
            </div>
            <div class="panel-body" style="padding:0 !important;">
            	<table class="table table-striped table-hover" id="user.dashboard.bookings.today">
    				<thead>
      					<tr>
        					<th>Room</th>
        					<th>From</th>
        					<th>To</th>
      					</tr>
    				</thead>
    				<tbody>

    				</tbody>
  				</table>
            </div>
            <div class="panel-footer" style="text-align:right;">
            	<button class="btn btn-default"><i class="fa fa-external-link"></i> Show All</button>
            </div>
          </div>
        </div>
		<div class="col-sm-6">

          <div class="panel panel-warning">
            <div class="panel-heading">
              <h3 class="panel-title">Equiptment Due Soon</h3>
            </div>
            <div class="panel-body">
            	<h3 style="color:#b5b5b5;margin-top: 0;">There are <span id="upcomingstudents" class="emphasise">-</span> students and <span id="upcomingstaff" class="emphasise">-</span> staff with equiptment due within the next 3 days</h3>
            </div>
            <div class="panel-footer" style="text-align:right;">
							<button class="btn btn-default modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-ht="System Notice" data-bt="This function is unavilable at this stage due to server functionality."><i class="fa fa-envelope-o"></i> Email Users</button>
            	<button class="btn btn-default"><i class="fa fa-external-link"></i> More</button>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Room Info</h3>
            </div>
            <div class="panel-body">
            	<h3 style="color:#b5b5b5;margin-top: 0;">Today there are <span id="bookingstoday" class="emphasise">-</span> room bookings and <span id="bookingstodayuser" class="emphasise">-</span> of these involve you</h3>
            </div>
            <div class="panel-footer" style="text-align:right;">
            	<button class="btn btn-default"><i class="fa fa-external-link"></i> More</button>
            </div>
          </div>

          <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">Out Of Bounds Loans</h3>
            </div>
            <div class="panel-body">
            	<h3 style="color:#b5b5b5;margin-top: 0;">There are <span id="forbiddenstaff" class="emphasise">-</span> staff members with forbidden items borrowed.</h3>
            </div>
            <div class="panel-footer" style="text-align:right;">
							<button class="btn btn-default modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-ht="System Notice" data-bt="This function is unavilable at this stage due to server functionality."><i class="fa fa-envelope-o"></i> Email Users</button>
            	<button class="btn btn-default"><i class="fa fa-external-link"></i> More</button>
            </div>
          </div>



        </div><!-- /.col-sm-4 -->

		<div class="col-sm-6 col-md-6">

          <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">Users With Overdue Equiptment</h3>
            </div>
            <div class="panel-body">
            	<h3 style="color:#b5b5b5;margin-top: 0;">There are <span id="overduestudents" class="emphasise">-</span> students and <span id="overduestaff" class="emphasise">-</span> staff with equiptment overdue</h3>
            </div>
            <div class="panel-footer" style="text-align:right;">
							<button class="btn btn-default modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-ht="System Notice" data-bt="This function is unavilable at this stage due to server functionality."><i class="fa fa-envelope-o"></i> Email Users</button>
            	<button class="btn btn-default"><i class="fa fa-external-link"></i> More</button>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Equiptment Info</h3>
            </div>
            <div class="panel-body">
            	<h3 style="color:#b5b5b5;margin-top: 0;">
            		There are <span id="itemstotal" class="emphasise">-</span> borrowable items<br>
            		<span id="itemsstudents" class="emphasise">-</span> of these are available to Students<br>
            		<span id="itemsstaff" class="emphasise">-</span> of these are available to Staff<br>
            		and <span id="borroweduser" class="emphasise">-</span> of these items have been borrowed by you
            	</h3>
            </div>
            <div class="panel-footer" style="text-align:right;">
            	<button class="btn btn-default"><i class="fa fa-external-link"></i> More</button>
            </div>
          </div>

          <!--<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Current Room Status</h3>
            </div>
            <div class="panel-body">
            	<table class="table table-striped">
    				<thead>
      					<tr>
        					<th>Room</th>
        					<th>Booked By</th>
        					<th>Current Status</th>
      					</tr>
    				</thead>
    				<tbody>
      					<tr>
        					<td>Sports Hall</td>
        					<td>ewallace</td>
        					<td class="danger">In Use</td>
      					</tr>
      					<tr>
        					<td>STEM 2.2</td>
        					<td>ewallace</td>
        					<td class="warning">Booked in 20 minutes</td>
      					</tr>
      					<tr>
        					<td>STEM 2.3</td>
        					<td>N/A</td>
        					<td class="success">Available</td>
      					</tr>
      					<tr>
        					<td>Sports Hall</td>
        					<td>ewallace</td>
        					<td class="danger">In Use</td>
      					</tr>
      					<tr>
        					<td>STEM 2.2</td>
        					<td>ewallace</td>
        					<td class="warning">Booked in 20 minutes</td>
      					</tr>
      					<tr>
        					<td>STEM 2.3</td>
        					<td>N/A</td>
        					<td class="success">Available</td>
      					</tr>
      					<tr>
        					<td>Sports Hall</td>
        					<td>ewallace</td>
        					<td class="danger">In Use</td>
      					</tr>
      					<tr>
        					<td>STEM 2.2</td>
        					<td>ewallace</td>
        					<td class="warning">Booked in 20 minutes</td>
      					</tr>
      					<tr>
        					<td>STEM 2.3</td>
        					<td>N/A</td>
        					<td class="success">Available</td>
      					</tr>
      					<tr>
        					<td>Sports Hall</td>
        					<td>ewallace</td>
        					<td class="danger">In Use</td>
      					</tr>
      					<tr>
        					<td>STEM 2.2</td>
        					<td>ewallace</td>
        					<td class="warning">Booked in 20 minutes</td>
      					</tr>
      					<tr>
        					<td>STEM 2.3</td>
        					<td>N/A</td>
        					<td class="success">Available</td>
      					</tr>
      					<tr>
        					<td>Sports Hall</td>
        					<td>ewallace</td>
        					<td class="danger">In Use</td>
      					</tr>
      					<tr>
        					<td>STEM 2.2</td>
        					<td>ewallace</td>
        					<td class="warning">Booked in 20 minutes</td>
      					</tr>
      					<tr>
        					<td>STEM 2.3</td>
        					<td>N/A</td>
        					<td class="success">Available</td>
      					</tr>
    				</tbody>
  				</table>
            </div>
            <div class="panel-footer">
            	<button class="btn btn-primary">View</button>
            </div>
          </div>-->

        </div><!-- /.col-sm-4 -->

      </div>

      <script>
      	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if (data["status_code"] == "success") {
					$("#upcomingstudents").html(data["DATA"]["upcoming_loans"]["students"]);
					$("#upcomingstaff").html(data["DATA"]["upcoming_loans"]["staff"]);

					$("#overduestudents").html(data["DATA"]["overdue_loans"]["students"]);
					$("#overduestaff").html(data["DATA"]["overdue_loans"]["staff"]);

					$("#bookingstoday").html(data["DATA"]["bookings"]["today"]);
					$("#bookingstodayuser").html(data["DATA"]["bookings"]["today_user"]);

					$("#itemstotal").html(data["DATA"]["total_items"]);
					$("#itemsstudents").html(data["DATA"]["loan_info"]["stud_borrowable"]);
					$("#itemsstaff").html(data["DATA"]["loan_info"]["staff_borrowable"]);
					$("#borroweduser").html(data["DATA"]["loan_info"]["borrowed_user"]);
					$("#forbiddenstaff").html(data["DATA"]["loan_info"]["forbidden_staff_loans"]);
					var hml = "<thead><tr><th style='padding-left: 12px;'>Room Name</th><th>Location</th><th>Timeframe</th></tr></thead><tbody>";
					for (var i=0; i<data["DATA"]["bookings"]["today_user_list"].length; i++) {
						if (parseInt(data["DATA"]["bookings"]["today_user_list"][i]["recurance_code"]) > 0) {
							var bookingId = data["DATA"]["bookings"]["today_user_list"][i]["booking_id"]+'-'+data["DATA"]["bookings"]["today_user_list"][i]["recurance_code"];
						} else {
							var bookingId = data["DATA"]["bookings"]["today_user_list"][i]["booking_id"];
						}

						hml += '<tr class="modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-ht="Viewing Booking #'+bookingId+'" data-rh="sebs.process.bah.bookings.view&booking_id='+bookingId+'" style="cursor:pointer;" data-booking_id="'+bookingId+'" data-toggle="tooltip" title="Booking #'+bookingId+'">';
        				hml += '<td style="padding-left: 12px;":>'+data["DATA"]["bookings"]["today_user_list"][i]["name"]+'</td>';
        				hml += '<td>'+data["DATA"]["bookings"]["today_user_list"][i]["location"]+'</td>';
        				if(data["DATA"]["bookings"]["today_user_list"][i]["all_day"] == true) {
        					hml += '<td>All Day</td>';
        				} else {
        					var cd = new Date();
        					var currentDate = cd.getFullYear()+'-'+(cd.getMonth()+1)+'-'+cd.getDate();
        					var currentDateAtMid = new Date(currentDate);

        					var eventStartDate = new Date(data["DATA"]["bookings"]["today_user_list"][i]["fds"]+' 00:00:00');
        					var eventEndDate = new Date(data["DATA"]["bookings"]["today_user_list"][i]["tds"]+' 00:00:00');

        					if (currentDate != eventStartDate) {
        						hml += '<td>All Day until ';
        					} else {
        						hml += '<td>'+data["DATA"]["bookings"]["today_user_list"][i]["fts"]+' to ';
        					}

        					if (eventEndDate > currentDateAtMid) {
        						var ndf = new Date(data["DATA"]["bookings"]["today_user_list"][i]["tds"]);
        						var sds = ndf.getDate()+'/'+(ndf.getMonth()+1)+'/'+ndf.getFullYear();
        						hml += sds+' at '+data["DATA"]["bookings"]["today_user_list"][i]["tts"]+'</td>';
        					} else {
        						hml += data["DATA"]["bookings"]["today_user_list"][i]["tts"]+'</td>';
        					}




        				}
        				//hml += '<td>'+data["DATA"]["bookings"]["today_user_list"][i]["tts"]+'</td>';
      					hml += '</tr>';
					}
					hml += '</tbody>';
					if (data["DATA"]["bookings"]["today_user_list"].length == 0) {
						hml = "<h3 style='margin-left:15px;margin-right:15px;'>You have no bookings today!</h3>";
					}
					document.getElementById('user.dashboard.bookings.today').innerHTML = hml;
					armTooltips();
					readyModal();
				} else {
					console.log(data["status_desc"]);
				}
			}
  		};
  		xhttp.open("GET", "remote-json.php?do=user.dashboard", true);
  		xhttp.send();
      </script>
