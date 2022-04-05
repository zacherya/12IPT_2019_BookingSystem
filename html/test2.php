<?php
?>



<style>
.items {
	transform: translate(0,0);
    opacity: 1;
    transition: all 0.5s ease-in-out;
}
div.items.away {
	transform: translate(100px,0);
    opacity: 0;
}
.items > .items-header {

}
.items > .items-body {
	margin-left: 30px;
}

.items > .items-body > div > .items-cell {
	background: #efbc2bdb;
    padding: 6 9 6 12;
    color: #343434;
    border-radius: 11px;
    transition: all 0.15s ease-out;
    box-shadow: 0px 8.5px 20px -5px rgba(14,21,47,0.3);
    margin-bottom:15px;
}

.items > .items-body > div > .items-cell:hover {
	transform: scale3d(1.07,1.07,1.07);
}
.items > .items-body > div > .items-cell:active {
	transform: scale3d(0.97,0.97,0.97);
}

.items > .items-body > div > .items-cell > .items-title {
	margin-top: 6px;
}
.items > .items-body > div > .items-cell > .items-title-sub {
	font-size:70%;
	color: #fff;
	margin-top:8px;
	margin-bottom:0;
}
</style>
<button class="btn btn-default" id="backbutton" data-fiber_stage="2"><i class="fa fa-arrow-left"></i> Back</button>
<div class="row items away" id="select_sport" data-stage="1" data-selected="">
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
			<button class="btn btn-success btn-lg"><i class="fa fa-plus"></i> Create Item</button>
		</div>
	</div>
	
	
	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Or select a sport to manage items</span>
		</div>
	</div>
	
	<div class="items-body">
		<div class="col-xs-12 col-sm-6">
			<div data-sport="Rugby" data-fiber_stage="1" class="items-cell">
				<h3 class="items-title">Rugby</h3>
			</div>
		</div>
	</div>
	
</div>

<div class="row items" id="select_category" data-stage="2" data-selected=""> 
	
	<div class="col-xs-12 items-header">
		<h2>Now let's pick a category for Rugby items...</h2>
	</div>
	
	<div class="items-body">
		<div class="col-xs-12 col-sm-6">
			<div data-category="Rugby" data-fiber_stage="2" class="items-cell">
				<h3 class="items-title-sub">RUGBY</h3>
				<h3 class="items-title">AIC Equiptment</h3>
			</div>
		</div>
	</div>
	
</div>

<div class="row items" id="select_item" data-stage="3" data-selected=""> 
	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer" style="margin-top:15px;">
			<span>Rugby</span>
		</div>
	</div>
	
	<div class="items-body">
		<div class="col-xs-12 col-sm-6">
			<div data-category="Rugby" data-fiber_stage="3" class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Goal Posts</h3>
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

function dsp(fs) {
	switch (""+fs) {
		case "1":
			$("#backbutton").attr("data-fiber_stage","0");
			$("#backbutton").addClass("hidden");
			
			$("#select_sport").removeClass("hidden");
			$("#select_category").addClass("hidden");
			$("#select_item").addClass("hidden");
	
			break;
		case "2":
			$("#backbutton").attr("data-fiber_stage","1");
			$("#backbutton").removeClass("hidden");
			
			$("#select_sport").addClass("hidden");
			$("#select_category").removeClass("hidden");
			$("#select_item").addClass("hidden");
			
			break;
		case "3":
			$("#backbutton").attr("data-fiber_stage","2");
			$("#backbutton").removeClass("hidden");
			
			$("#select_sport").addClass("hidden");
			$("#select_category").addClass("hidden");
			$("#select_item").removeClass("hidden");
			
			break;
		default:
			break;
	}
}

$(document).ready(function(){
	
	readyInital();
	
	$(".items-body > div > div").click(function(e) {  
		if ($(this).data("fiber_stage") == "1") {
			$("#select_sport").attr("data-selected",$(this).data("sport"));
			dsp(2);
		} else if ($(this).data("fiber_stage") == "2") {
			$("#select_category").attr("data-selected",$(this).data("category"));
			dsp(3);
		} else {
		
		}
	});
	
	$("#backbutton").click(function(e) { 
		console.log("Going to "+$(this).data("fiber_stage")); 
		dsp($(this).data("fiber_stage"));
	});
	
});
</script>

<?php
?>