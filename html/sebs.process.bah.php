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
<div class="stepper">
		<div class="row items away" id="processHeader">
			<div class="col-xs-12">
				<h1 <?php if($mobile){echo("style='text-align:center;'");} ?>><?php echo("".$greeting." ");
				if(isset($_SESSION['pref_name']) && $_SESSION['pref_name'] != ""){
					echo($_SESSION['pref_name']);
				} else if(isset($_SESSION['firstname'])){
					echo($_SESSION['firstname']);
				} else {
					echo("{NAME_ERROR}");
				} ?></h1>
				<h4 style='<?php if($mobile){echo("text-align:center;");} ?>'>What would you like to process today?</h4>
			</div>
		</div>
		<hr>
		<div class="row items away" style="margin-top:35px;height:100vh;" id="processBody">
			<div class="col-xs-12 col-sm-6">
				<a href="index.php?do=sebs.process.bah.bookings" id="sebs.manage.bookings.plex" class="ZAPlaxLink">
				<div class="ZAPlaxImg">
					<img src="resource/rb.png">
					<div class="ZAPlaxImg-layer" data-img="resource/bookinghire1.png"></div>
					<div class="ZAPlaxImg-layer" data-img="resource/rb.png"></div>
				</div>
				</a>
			</div>
			<div class="col-xs-12 col-sm-6">
				<a href="index.php?do=sebs.process.bah.loans" id="sebs.manage.loans.plex" class="ZAPlaxLink">
				<div class="ZAPlaxImg">
					<img src="resource/eh.png">
					<div class="ZAPlaxImg-layer" data-img="resource/equiptment1.png"></div>
					<div class="ZAPlaxImg-layer" data-img="resource/eh.png"></div>
				</div>
				</a>
			</div>
		</div>
</div>

		<script>
		function readyInital() {

			$("#processHeader").removeClass("hidden");
			$("#processHeader").removeClass("away");

			setTimeout(function() {
				$("#processBody").removeClass("hidden");
				$("#processBody").removeClass("away");
			}, 250);


		}

		function advance(f) {
			switch (f) {

				case true:
					if(!$("div[active='true']").next().hasClass('hidden')) {
						console.log("FAR FORWARD AS POSSIBLE");
					} else {
						console.log("Going Forwards");
						$("#backbutton").fadeOut(250);
						var previous = $("div[active='true']").attr("id");
						var next = $("div[active='true']").next().attr("id");

						$("#"+previous).addClass("away");
						setTimeout(function() {
		    				$("#"+previous).addClass("hidden");
							$("#"+previous).attr("active", false);
							setTimeout(function() {
		    					$("#"+next).attr("active", true);
								$("#"+next).removeClass("hidden");
								setTimeout(function() {
		    						$("#"+next).removeClass("away");
		    						if($("div[active='true']").data("stage") > 1) {
		    							$("#backbutton").removeClass("hidden");
		    							$("#backbutton").fadeOut(0);
		    							$("#backbutton").fadeIn(750);
		    						} else {
		    							$("#backbutton").addClass("hidden");
		    							$("#backbutton").fadeOut(0);
		    						}
		  						}, 250);
		  					}, 0);
		  				}, 500);

					}
					break;

				case false:
					if(!$("div[active='true']").prev().hasClass('hidden')) {
						console.log("FAR BACK AS POSSIBLE");
					} else {
						console.log("Going Backwards");
						$("#backbutton").fadeOut(250);
						var previous = $("div[active='true']").prev().attr("id");
						var current = $("div[active='true']").attr("id");

						$("#"+current).addClass("away");
						setTimeout(function() {
		    				$("#"+current).addClass("hidden");
							$("#"+current).attr("active", false);
							setTimeout(function() {
		    					$("#"+previous).attr("active", true);
								$("#"+previous).removeClass("hidden");
								setTimeout(function() {
		    						$("#"+previous).removeClass("away");
		    						if($("div[active='true']").data("stage") > 1) {
		    							$("#backbutton").removeClass("hidden");
		    							$("#backbutton").fadeOut(0);
		    							$("#backbutton").fadeIn(750);
		    						} else {
		    							$("#backbutton").addClass("hidden");
		    							$("#backbutton").fadeOut(0);
		    						}
		  						}, 250);
		  					}, 0);
		  				}, 500);
					}
					break;

				default:
					break;


			}
		}

		$(document).ready(function(){

			readyInital();

		});
		</script>

<?php
?>
