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
				<div class="progress">
  				<div class="progress-bar progress-bar-success progress-bar-striped" id="stageprocess" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
    				1/10
  				</div>
				</div>
				<h1 <?php if($mobile){echo("style='text-align:center;'");} ?>>Who is this booking for?</h1>
			</div>
		</div>
		<hr>

		<!-- Stage 1 Who is the booking for -->
		<div class="row items away" style="margin-top:35px;" id="s1" data-stage="1" data-selected="" active="true">
			<div class="items-body" style='margin-left:0;text-align:center;'>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div data-belong_stage="1" data-advance="3" data-value="cu" class="items-cell">
						<h3 class="items-title-sub">The Logged In User</h3>
						<h3 class="items-title"><?php
						if(isset($_SESSION['firstname'],$_SESSION['lastname'])){
							echo($_SESSION['firstname']." ".$_SESSION['lastname']);
						} else if(isset($_SESSION['pref_name']) && $_SESSION['pref_name'] != ""){
							echo($_SESSION['pref_name']);
						} else if(isset($_SESSION['firstname'])){
							echo($_SESSION['firstname']);
						} else {
							echo("{NAME_ERROR}");
						} ?></h3>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div data-belong_stage="1" data-value="ou" class="items-cell">
						<h3 class="items-title-sub">A Registered User</h3>
						<h3 class="items-title">Student or Staff Member</h3>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div data-belong_stage="1" data-value="gu" class="items-cell">
						<h3 class="items-title-sub">Someone Outside Lauries Faculty</h3>
						<h3 class="items-title">Guest</h3>
					</div>
				</div>
			</div>
		</div>

		<!-- Stage 2 Flexi Stage -->
		<div class="row items away hidden" style="margin-top:35px;" id="s2" data-stage="2" data-username="" data-guest="" active="false">
			<div class="items-body hidden" style="margin-left:0;text-align:center;" id="s2_su">
				<div class="col-xs-12">
					<div class="input-group" style="margin-bottom: 0;">
						<input type="text" class="form-control" placeholder="Search By Username Or By Name" name="s2_query" id="s2_query">
						<span class="btn btn-default input-group-addon" id="s2_gofind"><i class="fa fa-search"></i></span>
					</div>
				</div>
				<div class="col-xs-12" style="margin-top:25px;">
					<table class="table table-striped table-hover table-condensed" id="s2_table">
						<thead>
							<tr>
								<th>User ID</th>
								<th>Full Name</th>
								<th>User Type</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="4" style="text-align:center;">Search for a user first!</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="items-body hidden" style="margin-left:0;text-align:center;" id="s2_gu">
				<div class="col-xs-12" style="margin-bottom:10px;">
						<h3>* Leaving the Guest field blank will assume the booking under your name</h3>
				</div>
				<div class="col-xs-12 col-sm-8" style="margin-bottom:10px;">
					<input type="text" class="form-control" style="height:54px;font-size:25px;" placeholder="Enter the name of the guest" name="s2_guest" id="s2_guest">
				</div>
				<div class="col-xs-12 col-sm-4">
					<div data-belong_stage="2" data-value="gu" class="items-cell">
						<h3 class="items-title">Continue</h3>
					</div>
				</div>
			</div>

		</div>

		<!-- Stage 3 What room is being booked? -->
		<div class="row items away hidden" style="margin-top:35px;" id="s3" data-stage="3" active="false">
			<div class="items-body" style='margin-left:0;text-align:center;'>
				<div class="col-xs-12 slc-overlay items-header">
					<div class="outer" style="margin-top:15px;">
						<span>Loading Rooms...</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Stage 4 When from -->
		<div class="row items away hidden" style="margin-top:35px;" id="s4" data-stage="4" active="false">
			<div class="items-body" style="margin-left:0;text-align:center;">
				<div class="col-xs-12 col-sm-5" style="margin-bottom:10px;">
					<div class="col-xs-12" style="margin-bottom:10px;">
						<h3>What date?</h3>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="date" style="height:54px;font-size:25px;" class="form-control" id="from_date" min="" value="" name="from_date">
					</div>
				</div>
				<div class="hidden-xs col-sm-2" style="margin-bottom:10px;padding-top:25px;">
					<h1><i class="fa fa-arrow-right"></i></h1>
				</div>
				<div class="col-xs-12 col-sm-5" style="margin-bottom:10px;">
					<div class="col-xs-12" style="margin-bottom:10px;">
						<h3>What time?</h3>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="time" style="height:54px;font-size:25px;" class="form-control" id="from_time" value="08:30" name="to_time">
					</div>
				</div>
				<div class="col-xs-12">
					<div data-belong_stage="4" class="items-cell">
						<h3 class="items-title">Continue</h3>
					</div>
				</div>
			</div>
		</div>

		<!-- Stage 5 When to -->
		<div class="row items away hidden" style="margin-top:35px;" id="s5" data-stage="5" active="false">
			<div class="items-body" style="margin-left:0;text-align:center;">
				<div class="col-xs-12 col-sm-5" style="margin-bottom:10px;">
					<div class="col-xs-12" style="margin-bottom:10px;">
						<h3>What date?</h3>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="date" style="height:54px;font-size:25px;" class="form-control" id="to_date" min="" value="" name="from_date">
					</div>
				</div>
				<div class="hidden-xs col-sm-2" style="margin-bottom:10px;padding-top:25px;">
					<h1><i class="fa fa-arrow-right"></i></h1>
				</div>
				<div class="col-xs-12 col-sm-5" style="margin-bottom:10px;">
					<div class="col-xs-12" style="margin-bottom:10px;">
						<h3>What time?</h3>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="time" style="height:54px;font-size:25px;" class="form-control" id="to_time" value="14:30" name="to_time">
					</div>
				</div>
				<div class="col-xs-12">
					<div data-belong_stage="5" class="items-cell">
						<h3 class="items-title">Continue</h3>
					</div>
				</div>
			</div>
		</div>

		<!-- Stage 6 Re Occurances -->
		<div class="row items away hidden" style="margin-top:35px;" id="s6" data-stage="6" active="false">
			<div class="items-body" style="margin-left:0;text-align:center;">

				<div class="col-xs-12 col-sm-4" style="margin-bottom:10px;" id="s6_slab1">

					<div class="col-xs-12 slc-overlay items-header">
						<div class="outer" style="margin-top:15px;">
							<span>Repeating?</span>
						</div>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="radio" class="form-check-input" name="repeating" value="ot" id="s6_ot" checked="checked">
						<label class="form-check-label" for="everyday">No, One time booking</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="radio" class="form-check-input" name="repeating" value="ed" id="s6_ed">
						<label class="form-check-label" for="everyday">Yes, Every Day</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="radio" class="form-check-input" name="repeating" value="ew" id="s6_ew">
						<label class="form-check-label" for="everyweek">Yes, Every Week</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="radio" class="form-check-input" name="repeating" value="ef" id="s6_ef">
						<label class="form-check-label" for="everyweek">Yes, Every Fortnite</label>
					</div>

				</div>

				<div class="col-xs-12 col-sm-4 hidden" style="margin-bottom:10px;" id="s6_slab2">
					<div class="col-xs-12 slc-overlay items-header">
						<div class="outer" style="margin-top:15px;">
							<span>Stop After?</span>
						</div>
					</div>
					<div class="col-xs-12 input-group" style="margin-bottom:10px;">
						<input type="number" class="form-control" id="gountil" value="3" min="2" name="gountil" style="width:30%;border-right: 0;height:54px;font-size:25px;">
		    		<select class="form-control" id="gountil_factor" name="gountil_factor" style="width:65%;-webkit-appearance: none;height:54px;font-size:25px;" disabled>
		    			<option value="d">Days</option>
		    			<option value="w" selected>Weeks</option>
		    			<option value="f">Fortnites</option>
		  			</select>
					</div>
				</div>

				<div class="col-xs-12 col-sm-4 hidden" style="margin-bottom:10px;" id="s6_slab3">
					<div class="col-xs-12 slc-overlay items-header">
						<div class="outer" style="margin-top:15px;">
							<span>Recurring When?</span>
						</div>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="checkbox" class="form-check-input" id="monday">
						<label class="form-check-label" for="monday">Monday</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="checkbox" class="form-check-input" id="tuesday">
						<label class="form-check-label" for="tuesday">Tuesday</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="checkbox" class="form-check-input" id="wednesday">
						<label class="form-check-label" for="wednesday">Wednesday</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="checkbox" class="form-check-input" id="thursday">
						<label class="form-check-label" for="thursday">Thursday</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="checkbox" class="form-check-input" id="friday">
						<label class="form-check-label" for="friday">Friday</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="checkbox" class="form-check-input" id="saturday">
						<label class="form-check-label" for="saturday">Saturday</label>
					</div>
					<div class="col-xs-12" style="margin-bottom:10px;">
						<input type="checkbox" class="form-check-input" id="sunday">
						<label class="form-check-label" for="sunday">Sunday</label>
					</div>
				</div>



				<div class="col-xs-12" style="margin-bottom:10px;">
					<div data-belong_stage="6" class="items-cell">
						<h3 class="items-title">Continue</h3>
					</div>
				</div>

			</div>
		</div>

		<!-- Stage 7 When to -->
		<div class="row items away hidden" style="margin-top:35px;" id="s7" data-stage="7" active="false">
			<div class="items-body" style="margin-left:0;text-align:center;">
				<div class="col-xs-12" style="margin-bottom:10px;">
					<textarea data-gramm_editor="false" class="form-control" style="width:100%;height:75px;resize: none;" id="memo" placeholder="Contact Eddie for Information on wet weather changes"></textarea>
				</div>
				<div class="col-xs-12" style="margin-bottom:10px;">
					<div data-belong_stage="7" class="items-cell">
						<h3 class="items-title">Continue</h3>
					</div>
				</div>
			</div>
		</div>

		<!-- Stage 8 When to -->
		<div class="row items away hidden" style="margin-top:35px;" id="s8" data-stage="8" active="false">
			<div class="items-body" style="margin-left:0;text-align:center;">
				<div class="col-xs-12" style="margin-bottom:30px;">
					<div data-belong_stage="7" class="items-cell">
						<h3 class="items-title">Confirm Booking</h3>
					</div>
				</div>

				<div class="col-xs-12" style="margin-bottom:10px;">
					<table class="table table-striped table-condensed" id="s2_table">
						<thead>
							<tr>
								<th>Recur #</th>
								<th>Room ID</th>
								<th>From</th>
								<th>To</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<!--<td colspan="7" style="text-align:center;">Invalid Form Data!</td>-->
								<td>0</td>
								<td>
									<select class="form-control" id="4775-0-room" name="4775-0-room">
  									<option value="S2.2">S2.2</option>
										<option value="C1">C1</option>
										<option value="C2">C2</option>
  								</select>
								</td>
								<td>
									<input type="datetime-local" class="form-control" id="4775-0-from" name="4775-0-from" value="2019-02-02T13:30">
								</td>
								<td>
									<input type="datetime-local" class="form-control" id="4775-0-to" name="4775-0-to" value="">
								</td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>

		<script>

		</script>

<?php
?>
