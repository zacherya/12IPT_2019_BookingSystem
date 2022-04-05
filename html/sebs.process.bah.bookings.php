<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];

if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	$mobile=true;
} else {
	$mobile=false;
}

/* This sets the $time variable to the current hour in the 24 hour clock format */
    $time = date("H");
    /* Set the $timezone variable to become the current timezone */
    $timezone = date("e");
    /* If the time is less than 1200 hours, show good morning */
		$greeting = "Hello";
    if ($time < "12") {
        $greeting = "Good Morning";
    } else
    /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
    if ($time >= "12" && $time < "17") {
        $greeting = "Good Afternoon";
    } else
    /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
    if ($time >= "17" && $time < "19") {
        $greeting = "Good Evening";
    } else
    if ($time >= "19") {
        $greeting = "Good Evening";
    }

?>

		<div class="row items away" id="processHeader">
			<button class="btn btn-default hidden" id="backbutton" data-previous="1" style="margin-bottom:20px;"><i class="fa fa-arrow-left"></i> Back</button>
			<div class="col-xs-12">
				<h1 <?php if($mobile){echo("style='text-align:center;'");} ?>>Please fill out the booking below!</h1>
			</div>
		</div>
		<hr>

		<!-- Stage 1 Who is the booking for -->
		<div class="row items away" style="margin-top:35px;" id="formbody">
			<div class="items-body" style='margin-left:0;text-align:center;'>
				<div class="col-xs-12">
					<h1>Who is the booking for?</h1>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div data-belong_stage="1" data-advance="" data-value="cu" class="items-cell">
						<h3 class="items-title-sub">The Logged In User</h3>
						<h3 class="items-title">Edward Wallace</h3>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div data-belong_stage="1" data-advance="" data-value="cu" class="items-cell">
						<h3 class="items-title-sub">A Registered User</h3>
						<h3 class="items-title">Student or Staff Member</h3>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4">
					<div data-belong_stage="1" data-advance="" data-value="cu" class="items-cell">
						<h3 class="items-title-sub">Someone outside of Lauries Network</h3>
						<h3 class="items-title">Guest</h3>
					</div>
				</div>

				<hr>
				<div class="col-xs-12">
					<h1>What is the primary room for the booking?</h1>
					<p>You'll be able to change rooms for each occurance at a later point!</p>
				</div>
				<div class="col-xs-12 slc-overlay items-header"><div class="outer" style="margin-top:15px;"><span>STEM Building Ground Floor</span></div></div><div class="col-xs-12 col-sm-6 col-md-4"><div data-belong_stage="3" data-value="CRT1" class="items-cell"><h3 class="items-title-sub">CRT1</h3><h3 class="items-title">Sports Hall Court 1</h3></div></div><div class="col-xs-12 col-sm-6 col-md-4"><div data-belong_stage="3" data-value="CRT2" class="items-cell"><h3 class="items-title-sub">CRT2</h3><h3 class="items-title">Sports Hall Court 2</h3></div></div><div class="col-xs-12 slc-overlay items-header"><div class="outer" style="margin-top:15px;"><span>STEM Building Level 1</span></div></div><div class="col-xs-12 col-sm-6 col-md-4"><div data-belong_stage="3" data-value="S2.2" class="items-cell selected"><h3 class="items-title-sub">S2.2</h3><h3 class="items-title">STEM 2.2</h3></div></div><div class="col-xs-12 col-sm-6 col-md-4"><div data-belong_stage="3" data-value="WR" class="items-cell"><h3 class="items-title-sub">WR</h3><h3 class="items-title">Weights Room</h3></div></div><div class="col-xs-12 slc-overlay items-header"><div class="outer" style="margin-top:15px;"><span>Literacy Building</span></div></div><div class="col-xs-12 col-sm-6 col-md-4"><div data-belong_stage="3" data-value="X1" class="items-cell"><h3 class="items-title-sub">X1</h3><h3 class="items-title">Xavier 1</h3></div></div>

				<hr>
				<div class="col-xs-12">
					<h1>Is there any further on the booking?</h1>
					<p>This is general information for all occurances of the booking!</p>
				</div>
				<div class="col-xs-12">
					<textarea data-gramm_editor="false" class="form-control" style="width:100%;height:75px;resize: none;" id="memo" placeholder="Contact Eddie for Information on wet weather changes"></textarea>
				</div>

				<hr>
				<div class="col-xs-12">
					<h1>When will the first occurance be happening?</h1>
					<p>This is when the booking will start and finish, you can change individual dates and times on occurances later!</p>
				</div>
				<div class="col-xs-12 col-md-6">
					<div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" placeholder="Please select starting date and time" />
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
          </div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" placeholder="Please select ending date and time" />
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
          </div>
				</div>
				<script type="text/javascript">
						$(function () {
							 $('#datetimepicker1').datetimepicker({
										sideBySide: true,
										format: 'dddd, MMMM Do YYYY, h:mm a'
							 });
							 $('#datetimepicker2').datetimepicker({
										sideBySide: true,
										format: 'dddd, MMMM Do YYYY, h:mm a'
							 });
							 	$("#datetimepicker1").on("dp.change", function (e) {
				            $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
				        });

						});
				</script>


		<hr>
		<div class="col-xs-12">
			<h1>Will there be any further occurances?</h1>
			<p>You can repeat this booking or create sub bookings based on the the primary information enetred above!</p>
		</div>
		<div class="col-xs-12">
			<button class="btn btn-success">Add Occurance</button>
		</div>
		<div class="col-xs-12">
			<table class="table table-striped table-condensed" id="s2_table">
						<thead>
							<tr>
								<th>Recur #</th>
								<th>Room ID</th>
								<th>From</th>
								<th>To</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<!--<td colspan="7" style="text-align:center;">Invalid Form Data!</td>-->
								<td>1</td>
								<td>
									<select class="form-control" id="4775-0-room" name="4775-0-room">
  									<option value="S2.2">S2.2</option>
										<option value="C1" selected>C1</option>
										<option value="C2">C2</option>
  								</select>
								</td>
								<td>
									<div class="form-group">
				                <div class='input-group date' id='recur1-dtpicker-to'>
				                    <input type='text' class="form-control" placeholder="Please select starting date and time" />
				                    <span class="input-group-addon">
				                        <span class="fa fa-calendar"></span>
				                    </span>
				                </div>
				          </div>
								</td>
								<td>
									<div class="form-group">
				                <div class='input-group date' id='recur1-dtpicker-from'>
				                    <input type='text' class="form-control" placeholder="Please select ending date and time" />
				                    <span class="input-group-addon">
				                        <span class="fa fa-calendar"></span>
				                    </span>
				                </div>
				          </div>
								</td>
								<td>
									<button class="btn btn-danger" style="width:100%;"><span class="fa fa-trash"></span></button>
								</td>
							</tr>
							<tr>
								<!--<td colspan="7" style="text-align:center;">Invalid Form Data!</td>-->
								<td>2</td>
								<td>
									<select class="form-control" id="4775-0-room" name="4775-0-room">
  									<option value="S2.2">S2.2</option>
										<option value="C1">C1</option>
										<option value="C2">C2</option>
  								</select>
								</td>
								<td>
									<div class="form-group">
				                <div class='input-group date' id='recur2-dtpicker-to'>
				                    <input type='text' class="form-control" placeholder="Please select starting date and time" />
				                    <span class="input-group-addon">
				                        <span class="fa fa-calendar"></span>
				                    </span>
				                </div>
				          </div>
								</td>
								<td>
									<div class="form-group">
				                <div class='input-group date' id='recur2-dtpicker-from'>
				                    <input type='text' class="form-control" placeholder="Please select ending date and time" />
				                    <span class="input-group-addon">
				                        <span class="fa fa-calendar"></span>
				                    </span>
				                </div>
				          </div>
								</td>
								<td>
									<button class="btn btn-danger" style="width:100%;"><span class="fa fa-trash"></span></button>
								</td>
							</tr>
						</tbody>
					</table>
					<script type="text/javascript">
							$(function () {
								 $('#recur1-dtpicker-from').datetimepicker({
											sideBySide: true,
											format: 'dddd, MMMM Do YYYY, h:mm a'
								 });
								 $('#recur1-dtpicker-to').datetimepicker({
											sideBySide: true,
											format: 'dddd, MMMM Do YYYY, h:mm a'
								 });
								 	$("#recur1-dtpicker-from").on("dp.change", function (e) {
					            $('#recur1-dtpicker-to').data("DateTimePicker").minDate(e.date);
					        });

									$('#recur2-dtpicker-from').datetimepicker({
 											sideBySide: true,
 											format: 'dddd, MMMM Do YYYY, h:mm a'
 								 });
 								 $('#recur2-dtpicker-to').datetimepicker({
 											sideBySide: true,
 											format: 'dddd, MMMM Do YYYY, h:mm a'
 								 });
 								 	$("#recur2-dtpicker-from").on("dp.change", function (e) {
 					            $('#recur2-dtpicker-to').data("DateTimePicker").minDate(e.date);
 					        });

							});
					</script>

		</div>
		<br><br>
		<div class="col-xs-12">
			<div data-belong_stage="1" data-advance="" data-value="cu" class="items-cell">
				<h3 class="items-title">Confirm Booking</h3>
			</div>
		</div>
	</div>
</div>


		<script>
			$(document).ready(function(){
				$("#processHeader").removeClass("hidden");
				$("#processHeader").removeClass("away");

				setTimeout(function() {
					$("#formbody").removeClass("hidden");
					$("#formbody").removeClass("away");
				}, 250);
			});
		</script>

<?php
?>
