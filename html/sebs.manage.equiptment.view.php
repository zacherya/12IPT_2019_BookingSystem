<?php
include('db_vars.php');
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo("<center><h3>There was an internal error this function may not work as expected - ".$conn->connect_error."</h3></center>");
} 

$itemCode = $_GET['item_code'] ?? 0;

$sqlS = "SELECT DISTINCT(sport) FROM items";
$sqlC = "SELECT DISTINCT(category) FROM items";

$sportHtml = "";
$categoryHtml = "";
if($result = $conn->query($sqlS)) {

	if ($result->num_rows > 0) {
    	// output data of each row
    	while($row = $result->fetch_assoc()) {
    		$sport = $row["sport"];
    		
    		$sportHtml .= '<option value="'.$sport.'">'.$sport.'</option>';
    	}
	} else {
		echo("<h3><span class='label label-danger'>ERROR: No Sports Found - Please setup items with a Sport in MANAGE > EQUIPTMENT > CREATE ITEM</span></h3>");
	}
}
if($result = $conn->query($sqlC)) {

	if ($result->num_rows > 0) {
    	// output data of each row
    	while($row = $result->fetch_assoc()) {
    		$cat = $row["category"];
    		
    		$categoryHtml .= '<option value="'.$cat.'">'.$cat.'</option>';
    	}
	} else {
		echo("<h3><span class='label label-danger'>ERROR: No Categories Found - Please setup items with a Category in MANAGE > EQUIPTMENT > CREATE ITEM</span></h3>");
	}
}
?>

<form class="form-horizontal" id="equiptment_viewedit" data-submit_type="json" data-submit="true">
	<h4>Item Information</h4>
	<input type="hidden" id="item_code" value="" name="item_code">
	<hr>
    <div class="form-group">
      <label class="control-label col-sm-2" for="name">Name</label>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="name" value="Sport Ball" placeholder="" name="name" disabled>
      </div>
    </div>
    
    <div class="form-group">
  		<label class="control-label col-sm-2" for="sport">Sport</label>
  		<div class="col-sm-4"> 
  			<select class="form-control" id="sport" name="sport" onchange="updateSport()">
  			<?php echo($sportHtml); ?>
  			<option value="Other" selected>Other Sport</option>
  			</select>
  		</div>
  		<div class="col-sm-5"> 
  			<input type="text" class="form-control" id="sport_other" placeholder="Other Sport Name" name="sport_other" required="required">
  		</div>
	</div>
	
	<div class="form-group">
  		<label class="control-label col-sm-2" for="category">Category</label>
  		<div class="col-sm-4"> 
  			<select class="form-control" id="category" name="category" onchange="updateCategory()">
  				<?php echo($categoryHtml); ?>
  				<option value="Other" selected>Other Category</option>
  			</select>
  		</div>
  		<div class="col-sm-5"> 
  			<input type="text" class="form-control" id="category_other" placeholder="Other Category Name" name="category_other" required="required">
  		</div>
	</div>
    
    <br>
    <h4>Item's Stock Information</h4>
	<hr>
	<div class="form-group">
      <label class="control-label col-sm-3" for="stock_qty">Quantity of Stock</label>
      <div class="col-sm-2">          
        <input type="number" class="form-control" id="stock_qty" value="" name="stock_qty" disabled>
      </div>
      <label class="control-label col-sm-4" for="max_qty">Max Borrow Amount <i class="fa fa-info-circle" style="width:5%;" data-toggle="tooltip" title="The max quantity that one user can borrow at one time."></i></label>
      <div class="col-sm-2"> 
      	<input type="number" class="form-control" id="max_qty" value="" name="max_qty" disabled>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-3" for="max_period">Max Period <i class="fa fa-info-circle" style="width:5%;" data-toggle="tooltip" title="The max period this item can be borrowed for. This applies to both Staff and Students."></i></label>
      <div class="col-sm-6 input-group" style="padding-left:15px;padding-right:15px;"> 
      	<input type="number" class="form-control" id="max_period_num" value="4" name="max_period_num" style="width:30%;border-right: 0;">
    	<select class="form-control" id="max_period_factor" name="max_period_factor" style="width:65%;-webkit-appearance: none;">
    		<option value="m">Minutes</option>
    		<option value="h">Hours</option>
    		<option value="d" selected>Days</option>
    		<option value="w">Weeks</option>
    		<option value="mth">Months</option>
  		</select>
  	  </div>
    </div>
    
    <br>
    <h4>Other Relevant Information</h4>
	<hr>
	<div class="form-group">
		<label class="control-label col-sm-2" for="to">Permissions</label>
		<div class="col-sm-10"> 
			<div class="checkbox">
 				<label><input type="checkbox" id="staff_alwd" value="">Staff are allowed to borrow this item <i class="fa fa-info-circle" data-toggle="tooltip" title="The system will warn staff on the portal that they aren't permitted and deny borrowing on the self borrowing system"></i></label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2" for="to"></label>
		<div class="col-sm-10"> 
			<div class="checkbox">
 				<label><input type="checkbox" id="stud_alwd" value="">Students are allowed to borrow this item <i class="fa fa-info-circle" data-toggle="tooltip" title="The system will deny students from borrowing on the self borrowing system"></i></label>
			</div>
		</div>
	</div>
	
  </form>
  
  <div id="deletionConfirmation" class="dead">
  	<h3 style='text-align:center;'>Are you sure you want to delete<br><br><span class='label label-danger'>Booking #1234-5</span><br><br><h4 style='text-align:center;'><i>This action can't be undone and all data in accordance with this occurance of the booking will be deleted, inlcluding current loans and bookings!</i></h4></h3>
  </div>
  
  <script>
  
  function setupAll() {
  	changeTo("<?php echo($itemCode) ?>");
  }
  
 function changeTo(b) {
 	var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {   
					$("#item_code").val(b);
					$("#name").val(data["DATA"]["name"]);
					$('#sport').val(data["DATA"]["sport"]);
						$("#sport_other").attr("disabled", true);
						$("#sport_other").attr("required", false);
					$('#category').val(data["DATA"]["category"]);
						$("#category_other").attr("disabled", true);
						$("#category_other").attr("required", false);
					$("#stock_qty").val(data["DATA"]["stock_qty"]);
					$("#max_qty").val(data["DATA"]["max_qty"]);
					
					$("#staff_alwd").prop("checked",data["DATA"]["staff_alwd"]);
					$("#stud_alwd").prop("checked",data["DATA"]["stud_alwd"]);
					
					$("#deleteitembtn").attr("data-item_code", b);
					
					
					if(data["DATA"]["max_days"] >= 7) {
						//Weeks
							// DB_VALUE divide by 7
							var timeval = data["DATA"]["max_days"]/7;
							if(timeval >= 4) {
								//Month
								// TIME VALUE divide by 4
								timeval = timeval/4;
								$("#max_period_num").val(timeval.toFixed(2));
								$('#max_period_factor').val("mth");
							} else {
								//Defs weeks
								$("#max_period_num").val(timeval.toFixed(2));
								$('#max_period_factor').val("w");
							}
					} else if(data["DATA"]["max_days"] >= 1) {
						//Days
							// DB_VALUE
							$("#max_period_num").val(data["DATA"]["max_days"].toFixed(2));
							$('#max_period_factor').val("d");
					} else if(data["DATA"]["max_days"] >= (1/24)) {
						//Hours
							// DB_VALUE times by 24
							var timeval = data["DATA"]["max_days"]*24;
							$("#max_period_num").val(timeval.toFixed(2));
							$('#max_period_factor').val("h");
					} else if(data["DATA"]["max_days"] >= (1/1446)) {
						//Minutes
							// DB_VALUE times by 24 (Gets Time in hours)
							// HOURS times by 60
							var timeval = (data["DATA"]["max_days"]*24)*60;
							$("#max_period_num").val(timeval.toFixed(2));
							$('#max_period_factor').val("m");
					} 
					
					armTooltips(); 
					hideWaiting();
					
				} else {
					$("div.modal-body").html("<h3>There was an error loading the booking!<br>"+data["status_desc"]+"</h3>");
					$("div.modal-footer").html('<button onclick="location.reload();" type="button" class="btn btn-default" data-dismiss="modal">Done</button>');
				}
				
			} else {
				
			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.equiptment.view&intent=display&item_code="+b, true);
  		xhttp.send();
 }
 
 function updateSport() {
	if($("select[name='sport']").val() == "Other") {
		$("#sport_other").attr("disabled", false);
		$("#sport_other").attr("required", true);
	} else {
		$("#sport_other").attr("disabled", true);
		$("#sport_other").attr("required", false);
	}
}

function updateCategory() {
	if($("select[name='category']").val() == "Other") {
		$("#category_other").attr("disabled", false);
		$("#category_other").attr("required", true);
	} else {
		$("#category_other").attr("disabled", true);
		$("#category_other").attr("required", false);
	}
}
  
function rearmBtns() {
	$("#room").on('change', function() {
		$("#location").val($(this).find(':selected').data("loc"));
		if($(this).find(':selected').data("cap") <= 0) {
			$("#capacity").val("N/A");
		} else {
			$("#capacity").val($(this).find(':selected').data("cap"));
		}
		
	});
	
	$("#deleteitembtn").click(function() {
		var newTitle = 'Confirm Deletion of Item #1';
		var newHtml = '<h3 style="text-align:center;">Are you sure you want to delete<br><br><span class="label label-danger">Item #1 - Game Ball</span><br><br></h3><h4 style="text-align:center;"><i>This action can\'t be undone and all data in accordance with this item will be deleted, inlcluding current loans!</i></h4>';
		var footHtml = '<button type="button" class="btn btn-success" style="float:left;" data-dismiss="modal">Cancel</button><button id="confirmdeletebtn" data-item_code="'+$(this).data("item_code")+'" type="button" class="btn btn-danger">Confirm Action</button>';
		$(".modal-title").html(newTitle);
		$(".modal-body").html(newHtml);
		$(".modal-footer").html(footHtml);
		rearmBtns();
	});
	
	$("#confirmdeletebtn").click(function() {
		showWaiting("Confirming Deletion with Server...");
		var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				if(data["status_code"] == "success") {   
					var newTitle = 'Item Deleted';
					var newHtml = '<h3 style="text-align:center;"><span class="label label-success">'+data["status_desc"]+'</span><br><br></h3>';
					$(".modal-title").html(newTitle);
					$(".modal-body").html(newHtml);
					$("div.modal-footer").html('<button onclick="location.reload();" type="button" class="btn btn-default" data-dismiss="modal">Done</button>');
				} else {
					hideWaiting();
    				$('div.modal-body .alert').remove();
  					$("div.modal-body h3").first().before('<div class="alert alert-danger"><span><b> There was an error removing the user!</b><br>'+data['status_desc']+'</span></div>');
				}
				
			} else {
				hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("div.modal-body h3").first().before('<div class="alert alert-danger"><span><b> There was an error removing the user!</b><br>Unknown Reason, Check Internet Connection and Reload Page!</span></div>');
			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.equiptment.remove&intent=save&item_code="+$(this).data("item_code"), true);
  		xhttp.send();
	});
	
	$("#updateitembtn").click(function() {
		showWaiting("Updating Item...");
		
  		if ($("#equiptment_viewedit select[name='sport']").val() == "Other") {
			var sport = $("#equiptment_viewedit input[name='sport_other']").val();
		} else {
			var sport = $("#equiptment_viewedit select[name='sport']").val();
		}
		if ($("#equiptment_viewedit select[name='category']").val() == "Other") {
			var category = $("#equiptment_viewedit input[name='category_other']").val();
		} else {
			var category = $("#equiptment_viewedit select[name='category']").val();
		}
		
		if ($("#staff_alwd").is(':checked')) {
    		var staff = true;
  		} else {
    		var staff = false;
  		}
  		if ($("#stud_alwd").is(':checked')) {
    		var stud = true;
  		} else {
    		var stud = false;
  		}
  		$.ajax({

    url : 'remote-json.php?do=sebs.manage.equiptment.add&intent=update',
    type : 'POST',
    data : {
    	item_code: $("#equiptment_viewedit input[name='item_code']").val(),
    		name: $("#equiptment_viewedit input[name='name']").val(),
			category: category,
			sport: sport,
			stock_qty: $("#equiptment_viewedit input[name='stock_qty']").val(),
			max_qty: $("#equiptment_viewedit input[name='max_qty']").val(),
			max_period_num: $("#equiptment_viewedit input[name='max_period_num']").val(),
			max_period_factor: $("#equiptment_viewedit select[name='max_period_factor']").val(),
			staff_alwd: staff,
			stud_alwd: stud
    },
    dataType:'json',
    success : function(resp) {  
  			if(resp["status_code"] == "success") {   
  				hideWaiting();
  				$('div.modal-body .alert').remove();
  				$(".modal-title").html("Item Updated Successfully");
  				$(".modal-body").html("<h3 style='text-align:center;'><span class='label label-success'>The item has been successfully updated!</span><br><br>The changes may have taken effect on loan information and may flag some loans as 'out of bounds'</h3>");
  				$(".modal-footer").html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload();">Done</button>');
				
       		} else {
       			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#equiptment_viewedit").before('<div class="alert alert-danger"><span><b> There was an error updating the item!</b><br>'+resp['status_desc']+'</span></div>');

       		}
    },
    error : function(request,error) {
    			hideWaiting();
    			$('div.modal-body .alert').remove();
  				$("#equiptment_viewedit").before('<div class="alert alert-danger"><span><b> There was an error updating the item!</b><br>Unexplained</span></div>');

    	    }
		});
	});
}

$(document).ready(function(){
	armTooltips(); 	
	setupAll();
	
	rearmBtns();
	
});
  </script>
