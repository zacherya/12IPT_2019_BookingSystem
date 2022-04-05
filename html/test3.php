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
    				1/6
  				</div>
				</div>
				<h1 <?php if($mobile){echo("style='text-align:center;'");} ?>>Who is this booking for?</h1>
			</div>
		</div>
		<hr>


		<!-- Stage 2 Find User -->
		<div class="row items hidden" style="margin-top:35px;" id="s2" data-stage="2" active="false">
			<div class="items-body" style='margin-left:0;text-align:center;'>
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
								<td>123456</td>
								<td>John (Johnathon) Doe Smith</td>
								<td>Student</td>
								<td><button class="btn btn-sm btn-success" onclick="">Select</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- Stage 2 Type Guest -->
		<div class="row items" style="margin-top:35px;" id="s2" data-stage="2" active="false">
			<div class="items-body" style='margin-left:0;text-align:center;'>
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

		<script>


		$(document).ready(function(){

		});
		</script>

<?php
?>
