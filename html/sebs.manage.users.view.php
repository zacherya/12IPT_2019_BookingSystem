<?php
$uuid = $_GET["username"] ?? 0;
$useragent=$_SERVER['HTTP_USER_AGENT'];

if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	$mobile=true;
} else {
	$mobile=false;
}

?>
<button class="btn btn-default" id="goback" style="margin-bottom:20px;" data-goto=""><i class="fa fa-arrow-left"></i> Back</button>
<div class="panel panel-default" id="profile_card" style="box-shadow: -1px 6px 15px rgba(14,21,47,0.2);border: 0px solid transparent;">
	<div class="panel-heading">
		<h1 class="panel-title">Loading...</h1>
	</div>
	<div class="panel-body" style="max-height: 91%;overflow-y: scroll;">
		<h6 id="card-usr_house">

		</h6>
		<h2 style="margin-top:0;" id="card-usr_fn">Loading...</h2>
	</div>
	<div class="panel-footer hidden" style="padding: 15px 30px 15px 30px;">
		<form class="form-horizontal" id="user_viewedit" data-submit_type="json" data-submit="true">
			<div class="form-group">
				<label class="control-label col-sm-2" for="email">E-Mail *</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="email" value="145871@slc.qld.edu.au" name="email">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="password">Password</label>
				<div class="col-sm-9 input-group" style="padding-right:15;padding-left:15;">
					<input type="password" class="form-control" id="password" value="" placeholder="New Password" name="password">
					<span class="btn btn-default input-group-addon" id="showpwd"><i class="fa fa-eye"></i></span>
				</div>
				<h6 class="col-sm-12"><br>Entering a password above will change the users password. Leave the field blank when updating to keep the current password.</h6>
			</div>
			<hr>
			<div class="form-group">
				<label class="control-label col-sm-2" for="firstname">Name</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="firstname" value="" placeholder="* First Name (Mitchel)" name="firstname" required="true" autofocus>
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
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="hse_name">House</label>
				<div class="col-sm-4">
					<select class="form-control" id="hse_name" name="hse_name" onchange="updateHouse()">
						<option selected>Other</option>
					</select>
				</div>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="hse_name_other" placeholder="Other House Name" name="hse_name_other" disabled>
				</div>
			</div>
			<hr>
			<div class="form-group" style="text-align:right; padding-top:5px;">
				<button id="updateuserbtn" class="btn btn-warning">Update User Information</button>
			</div>
			<input type="hidden" id="username" value="<?php echo($uuid); ?>" name="username">
			<input type="hidden" id="utype" value="" name="utype">
		</form>
	</div>
</div>
<div class="panel panel-success" id="bookings_card">
			<div class="panel-heading">
				<h3 class="panel-title">All Room Bookings For </h3>
			</div>
			<div class="panel-body" style="padding:0 !important;">
				<table class="table table-striped table-hover" id="user.manage.users.view.bookings">
			<thead>
					<tr>
						<th>Booking Ref #</th>
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
		<div class="panel panel-warning" id="loans_card">
					<div class="panel-heading">
						<h3 class="panel-title">All Items Loaned By </h3>
					</div>
					<div class="panel-body" style="padding:0 !important;">
						<table class="table table-striped table-hover" id="user.manage.users.view.loans">
					<thead>
							<tr>
								<th>Item Ref #</th>
								<th>Name</th>
								<th>Qty</th>
								<th>Expected By</th>
								<th>Status</th>
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

        <script>
        function showUser() {
var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {

					$("#card-usr_fn").html(data["DATA"]["info"]["fullname"]);

					if(data["DATA"]["info"]["hse_name"] === null) {
						$("#card-usr_house").html("<span class='label label-default'>"+data["DATA"]["info"]["uuid"]+"</span>");
						$("#hse_name").val("Other");
					} else {
						$("#card-usr_house").html("<span class='label label-default'>"+data["DATA"]["info"]["uuid"]+"</span> | "+data["DATA"]["info"]["hse_name"]);
						$("#hse_name").val(data["DATA"]["info"]["hse_name"]);
					}

					$("#email").val(data["DATA"]["info"]["email"]);
					$("#firstname").val(data["DATA"]["info"]["firstname"]);
					$("#firstname_pref").val(data["DATA"]["info"]["firstname_pref"]);
					$("#middlename").val(data["DATA"]["info"]["middlename"]);
					$("#lastname").val(data["DATA"]["info"]["lastname"]);

					var dobArr = data["DATA"]["info"]["dob"].split('-');
					$("#dob_day").val(dobArr[2]);
					$("#dob_month").val(dobArr[1]);
					$("#dob_year").val(dobArr[0]);

					$("#gender").val(data["DATA"]["info"]["gender"]);

					if(data["DATA"]["info"]["year_grp"] === null) {
						//setup view for teacher
						$(".panel-title").first().html("Viewing Teacher Profile")
						$("label[for='year_grp']").remove();
						$("#year_grp").parent().remove();
						$("#utype").val("T");
						$("#goback").attr("data-goto","teachers");
					} else {
						//setup view for student
						$(".panel-title").first().html("Viewing Student Profile");
						$("#year_grp").val(data["DATA"]["info"]["year_grp"]);
						$("#utype").val("S");
						$("#goback").attr("data-goto","students");
					}

					// Setup Users Bookings Viewing
					var hml = "<thead><tr><th style='padding-left: 12px;'>Ref #</th><th>Room Name</th><th>From</th><th>To</th></tr></thead><tbody>";
					for (var i=0; i<data["DATA"]["bookings"].length; i++) {
						if (parseInt(data["DATA"]["bookings"][i]["recurance_code"]) > 0) {
							var bookingId = data["DATA"]["bookings"][i]["booking_id"]+'-'+data["DATA"]["bookings"][i]["recurance_code"];
						} else {
							var bookingId = data["DATA"]["bookings"][i]["booking_id"];
						}

						hml += '<tr class="modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-ht="Viewing Booking #'+bookingId+'" data-rh="sebs.process.bah.bookings.view&booking_id='+bookingId+'" style="cursor:pointer;" data-booking_id="'+bookingId+'" data-toggle="tooltip" title="Booking #'+bookingId+'">';
								if(data["DATA"]["bookings"][i]["recurance_code"] == 0){
									hml += '<td style="padding-left: 12px;">'+data["DATA"]["bookings"][i]["booking_id"]+'</td>';
								} else {
									hml += '<td style="padding-left: 12px;">'+data["DATA"]["bookings"][i]["booking_id"]+"-"+data["DATA"]["bookings"][i]["recurance_code"]+'</td>';
								}
								hml += '<td>'+data["DATA"]["bookings"][i]["name"]+' <i class="fa fa-info-circle" style="width:5%;" data-toggle="tooltip" title="'+data["DATA"]["bookings"][i]["location"]+'" data-original-title=""></i></td>';
        				//hml += '<td>'+data["DATA"]["bookings"][i]["location"]+'</td>';
        					var cd = new Date();
        					var currentDate = cd.getFullYear()+'-'+(cd.getMonth()+1)+'-'+cd.getDate();
        					var currentDateAtMid = new Date(currentDate);

        					var eventStartDate = new Date(data["DATA"]["bookings"][i]["fds"]+' 00:00:00');
        					var eventEndDate = new Date(data["DATA"]["bookings"][i]["tds"]+' 00:00:00');

										var ndf = new Date(data["DATA"]["bookings"][i]["fds"]);
        						var sds = ndf.getDate()+'/'+(ndf.getMonth()+1)+'/'+ndf.getFullYear();
        						hml += '<td>'+sds+' at '+data["DATA"]["bookings"][i]["fts"]+'</td>';

										var ndf = new Date(data["DATA"]["bookings"][i]["tds"]);
        						var sds = ndf.getDate()+'/'+(ndf.getMonth()+1)+'/'+ndf.getFullYear();
        						hml += '<td>'+sds+' at '+data["DATA"]["bookings"][i]["tts"]+'</td>';

        				//hml += '<td>'+data["DATA"]["bookings"][i]["tts"]+'</td>';
      					hml += '</tr>';
					}
					hml += '</tbody>';
					if (data["DATA"]["bookings"].length == 0) {
						hml = "<h3 style='margin-left:15px;margin-right:15px;'>This user has no bookings!</h3>";
					}
					document.getElementById('user.manage.users.view.bookings').innerHTML = hml;

					var lml = "<thead><tr><th style='padding-left: 12px;'>Item Ref #</th><th>Name</th><th>Qty</th><th>Expected By</th><th>Status</th></tr></thead><tbody>";
					for (var i=0; i<data["DATA"]["loans"].length; i++) {

						lml += '<tr class="modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-ht="Viewing Booking #'+bookingId+'" data-rh="sebs.process.bah.bookings.view&booking_id='+bookingId+'" style="cursor:pointer;" data-booking_id="'+bookingId+'" data-toggle="tooltip" title="Booking #'+bookingId+'">';
								lml += '<td style="padding-left: 12px;">'+data["DATA"]["loans"][i]["item_code"]+'</td>';
								lml += '<td>'+data["DATA"]["loans"][i]["item_desc"]+' <i class="fa fa-info-circle" style="width:5%;" data-toggle="tooltip" title="'+data["DATA"]["loans"][i]["item_sport"]+' - '+data["DATA"]["loans"][i]["item_category"]+'" data-original-title=""></i></td>';
								lml += '<td>'+data["DATA"]["loans"][i]["qty_of_item"]+'</td>';

								var ndf = new Date(data["DATA"]["loans"][i]["return_on"]);
								var dateDesc = ndf.getDate()+'/'+(ndf.getMonth()+1)+'/'+ndf.getFullYear();
								if(ndf.getHours() == 0) {
									var hours = "12";
									var factor = "am"
								} else if(ndf.getHours() > 0 && ndf.getHours() < 12) {
									var hours = ndf.getHours();
									var factor = "am"
								} else if(ndf.getHours() > 12 && ndf.getHours() <= 23) {
									var hours = (ndf.getHours()-12);
									var factor = "pm";
								} else {
									var hours = ndf.getHours();
									var factor = "";
								}
								if(ndf.getMinutes() >= 0 && ndf.getMinutes() <= 9) {
									var minutes = "0"+ndf.getMinutes();
								} else {
									var minutes = ndf.getMinutes();
								}
								var timeDesc = hours+':'+minutes+' '+factor;
								lml += '<td>'+dateDesc+' <i class="fa fa-info-circle" style="width:5%;" data-toggle="tooltip" title="'+timeDesc+'" data-original-title=""></i></td>';

								switch (data["DATA"]["loans"][i]["status_flg"]) {
									case "OD":
										lml += '<td class="danger">Overdue</td>';
										break;
									case "OK":
										lml += '<td class="success">On Loan</td>';
										break;
									case "DT":
										lml += '<td class="warning">Due Today</td>';
										break;
									case "DT":
										lml += '<td class="warning">Due Soon</td>';
										break;
									default:
										lml += '<td class="default">Unknown</td>';
										break;
								}


								//lml += '<td>'+data["DATA"]["loans"][i]["location"]+'</td>';

        				//lml += '<td>'+data["DATA"]["bookings"][i]["tts"]+'</td>';
      					lml += '</tr>';
					}
					lml += '</tbody>';
					if (data["DATA"]["loans"].length == 0) {
						lml = "<h3 style='margin-left:15px;margin-right:15px;'>This user has no loans!</h3>";
					}
					document.getElementById('user.manage.users.view.loans').innerHTML = lml;

					armTooltips();
					readyModal();
					updateHouse();
					$(".panel-footer").removeClass("hidden");

				} else {
					$("div.modal-body").html("<h3>There was an error loading the booking!<br>"+data["status_desc"]+"</h3>");
					$("div.modal-footer").html('<button onclick="location.reload();" type="button" class="btn btn-default" data-dismiss="modal">Done</button>');
				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.users.view&intent=display&username=<?php echo($uuid); ?>", true);
  		xhttp.send();
  		}
  		$(document).ready(function(){
  			showUser();
		});
        </script>

<?php
?>
