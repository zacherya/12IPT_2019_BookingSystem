<?php
?>

<button class="btn btn-default" id="backbutton"><i class="fa fa-arrow-left"></i> Back</button>

<div class="stepper">
<div class="row items away" id="select_sport" data-stage="1" active="true" data-selected="">
	<div class="col-xs-12 items-header">
		<h2>Before we can manage equiptment either...</h2>
	</div>

	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Create a new item</span>
		</div>
	</div>
	<div class="items-body">
		<div class="col-xs-12">
			<button class="btn btn-success btn-lg modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.manage.equiptment.add"><i class="fa fa-plus"></i> Create Item</button>
		</div>
	</div>


	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Or select a sport to manage items</span>
		</div>
	</div>

	<div class="items-body">
		<h2>Loading...</h2>
	</div>
</div>

<div class="row items away hidden" id="select_category" data-stage="2" active="false" data-selected="">
	<div class="col-xs-12 items-header">
		<h2>You picked Rugby!</h2>
	</div>

	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Select a sub-category</span>
		</div>
	</div>

	<div class="items-body">
		<h2>Loading...</h2>
	</div>
</div>

<div class="row items away hidden" id="select_item" data-stage="3" active="false" data-selected="">
	<div class="col-xs-12 items-header">
		<h2>You are viewing all Items in Rugby - AIC Equiptment</h2>
	</div>

	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Select an item to edit</span>
		</div>
	</div>

	<div class="items-body">
		<div class="col-xs-12 col-sm-6">
			<div data-category="Rugby" data-fiber_stage="3" class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Goal Posts</h3>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<div data-category="Rugby" data-fiber_stage="3" class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Goal Posts</h3>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<div data-category="Rugby" data-fiber_stage="3" class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Goal Posts</h3>
			</div>
		</div>
	</div>
</div>
</div>


<script>

function readyInital() {
	$("#backbutton").addClass("hidden");

	$("#select_sport").removeClass("hidden");
	$("#select_sport").removeClass("away");

	$("#select_category").addClass("hidden");

	$("#select_item").addClass("hidden");
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

function listSports() {
	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {
					var sportHtml = "";
					for(var i=0; i<data["DATA"].length; i++) {
						sportHtml += '<div class="col-xs-12 col-sm-6"><div data-sport="'+data["DATA"][i]+'" data-fiber_stage="1" class="items-cell"><h3 class="items-title">'+data["DATA"][i]+'</h3></div></div>';
					}
					$("#select_sport div.items-body").last().html(sportHtml);
					rearm();
				} else {

				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.equiptment.list&distinct=true&stage=1", true);
  		xhttp.send();
}

function listCategories(s) {
	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {
					var sportHtml = "";
					for(var i=0; i<data["DATA"].length; i++) {
						sportHtml += '<div class="col-xs-12 col-sm-6"><div data-sport="'+s+'" data-category="'+data["DATA"][i]+'" data-fiber_stage="2" class="items-cell"><h3 class="items-title-sub">'+s.toUpperCase()+'</h3><h3 class="items-title">'+data["DATA"][i]+'</h3></div></div>';
					}
					if (data["DATA"].length <= 0) {
						$("#select_category div.items-body").last().html("");
						$("#select_category div.items-header").first().html("<h2>No items available for "+s+" - "+c+"</h2>");
					} else {
						$("#select_category div.items-body").last().html(sportHtml);
						$("#select_category div.items-header").first().html("<h2>Viewing categories of "+s+"</h2>");
					}

					rearm();
				} else {

				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.equiptment.list&distinct=true&stage=2&sport="+s, true);
  		xhttp.send();
}

function listItems(s,c) {
	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {
					var sportHtml = "";
					for(var i=0; i<data["DATA"].length; i++) {
						sportHtml += '<div class="col-xs-12 col-sm-6"><div data-sport="'+s+'" data-category="'+c+'" data-item_code="'+data["DATA"][i]["item_code"]+'" data-fiber_stage="3" class="items-cell modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.manage.equiptment.view&item_code='+data["DATA"][i]["item_code"]+'" data-ht="Viewing Item #'+data["DATA"][i]["item_code"]+'"><h3 class="items-title-sub">'+s+' - '+c+'</h3><h3 class="items-title">'+data["DATA"][i]["item_desc"]+'</h3></div></div>';
					}
					if (data["DATA"].length <= 0) {
						$("#select_item div.items-body").last().html("");
						$("#select_item div.items-header").first().html("<h2>No items available for "+s+" - "+c+"</h2>");
					} else {
						$("#select_item div.items-body").last().html(sportHtml);
						$("#select_item div.items-header").first().html("<h2>You are viewing all "+s+" items in the "+c+" category!</h2>");
					}
					rearm();
				} else {

				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.equiptment.list&distinct=true&stage=3&sport="+s+"&category="+c, true);
  		xhttp.send();
}

function rearm() {
	$(".items-body > div > div").off("click");
	$("#backbutton").off("click");
	$(".items-body > div > div").click(function(e) {
		switch ($(this).data("fiber_stage")) {
			case 1:
				$("#select_sport").attr("data-selected",$(this).data("sport"));
				listCategories($(this).data("sport"));
				break;
			case 2:
				$("#select_category").attr("data-selected",$(this).data("category"));
				listItems($(this).data("sport"),$(this).data("category"));
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

	listSports();
	readyInital();
	readyModal();

	rearm();

});
</script>

<?php
?>
