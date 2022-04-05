<?php
?>

<button class="btn btn-default" id="backbutton"><i class="fa fa-arrow-left"></i> Back</button>

<div class="stepper">
<div class="row items away" id="select_building" data-stage="1" active="true" data-selected="">
	<div class="col-xs-12 items-header">
		<h2>Before we can manage rooms either...</h2>
	</div>

	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Add a new room</span>
		</div>
	</div>
	<div class="items-body">
		<div class="col-xs-12">
			<button class="btn btn-success btn-lg modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.manage.rooms.add"><i class="fa fa-plus"></i> Add Room</button>
		</div>
	</div>


	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Or select a location to manage a room</span>
		</div>
	</div>

	<div class="items-body">
		<h2>Loading...</h2>
	</div>
</div>

<div class="row items away hidden" id="select_room" data-stage="2" active="false" data-selected="">
	<div class="col-xs-12 items-header">
		<h2>You are viewing all rooms in *** Building</h2>
	</div>

	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Select a room to edit</span>
		</div>
	</div>

	<div class="items-body">
		<div class="col-xs-12 col-sm-6">
			<div data-building="STEM Building" data-fiber_stage="2" class="items-cell">
				<h3 class="items-title-sub">STEM Building</h3>
				<h3 class="items-title">Goal Posts</h3>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<div data-building="STEM Building" data-fiber_stage="2" class="items-cell">
				<h3 class="items-title-sub">STEM Building</h3>
				<h3 class="items-title">Goal Posts</h3>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<div data-building="STEM Building" data-fiber_stage="2" class="items-cell">
				<h3 class="items-title-sub">STEM Building</h3>
				<h3 class="items-title">Goal Posts</h3>
			</div>
		</div>
	</div>
</div>
</div>


<script>

function readyInital() {
	$("#backbutton").addClass("hidden");

	$("#select_building").removeClass("hidden");
	$("#select_building").removeClass("away");

	$("#select_room").addClass("hidden");
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

function listBuildings() {
	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {
					var buildingHtml = "";
					for(var i=0; i<data["DATA"].length; i++) {
						buildingHtml += '<div class="col-xs-12 col-sm-12"><div data-building="'+data["DATA"][i]+'" data-fiber_stage="1" class="items-cell"><h3 class="items-title">'+data["DATA"][i]+'</h3></div></div>';
					}
					$("#select_building div.items-body").last().html(buildingHtml);
					rearm();
				} else {

				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.rooms.list&distinct=true&stage=1", true);
  		xhttp.send();
}

function listRooms(b) {
	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {
					var roomHtml = "";
					for(var i=0; i<data["DATA"].length; i++) {
						roomHtml += '<div class="col-xs-12 col-sm-6"><div data-building="'+b+'" data-room_code="'+data["DATA"][i]["room_code"]+'" data-fiber_stage="2" class="items-cell modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.manage.rooms.view&room_code='+data["DATA"][i]["room_code"]+'" data-ht="Viewing '+data["DATA"][i]["room_name"]+'"><h3 class="items-title-sub">'+b+'</h3><h3 class="items-title">'+data["DATA"][i]["room_name"]+'</h3></div></div>';
					}
					if (data["DATA"].length <= 0) {
						$("#select_room div.items-body").last().html("");
						$("#select_room div.items-header").first().html("<h2>No rooms available for "+b+"</h2>");
					} else {
						$("#select_room div.items-body").last().html(roomHtml);
						$("#select_room div.items-header").first().html("<h2>You are viewing all rooms in "+b+"!</h2>");
					}
					rearm();
				} else {

				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.rooms.list&distinct=true&stage=2&building="+b, true);
  		xhttp.send();
}

function rearm() {
	$(".items-body > div > div").off("click");
	$("#backbutton").off("click");
	$(".items-body > div > div").click(function(e) {
		switch ($(this).data("fiber_stage")) {
			case 1:
				$("#select_building").attr("data-selected",$(this).data("building"));
				listRooms($(this).data("building"));
				break;
			case 2:
				$("#select_category").attr("data-selected",$(this).data("category"));
				listItems($(this).data("building"),$(this).data("category"));
				break;
			case 3:

				break;
			default:

				break;
		}
		advance(true);
	});

	readyModal();

	$("#backbutton").click(function(e) {
		advance(false);
	});
}

$(document).ready(function(){

	listBuildings();
	readyInital();
	readyModal();

	rearm();

});
</script>

<?php
?>
