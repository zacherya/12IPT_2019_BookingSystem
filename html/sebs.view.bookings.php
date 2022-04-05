<?php
if (isset($_GET['query'])) {
	$query=$_GET['query'];
	if ($_GET['query'] == "") {
		$searchQuery = '';
	} else if ($_GET['query'] == " ") {
		$searchQuery = '';
	} else {
		$searchQuery = '<p class="card-category">Showing Search Results for <b><i>'.$query.'</i></b>.<br><a href=\'index.php?do=admin.employee.masterfile\' class=\'btn btn-danger pull-left\'>Show All Employees</a></p>';
	}
} else {
	$query="";
	$searchQuery = '';
}
$filter="";
if(isset($_GET['filter'])) {
	$filter=$_GET['filter'];
}
$useragent=$_SERVER['HTTP_USER_AGENT'];

if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	$mobile=true;
} else {
	$mobile=false;
}

?>

		<div class="panel panel-primary">
            <div class="panel-heading">
              <h1 class="panel-title">All My Bookings</h1>
            </div>
            <div class="panel-body" style="max-height: 91%;overflow-y: scroll;">
            	<div class="well well-sm" style="text-align:right;">
            		<div class="btn-group" style="float:left;">
  						<a href="index.php?do=sebs.manage.users&filter=students" class="btn btn-info disabled">Students</a>
  						<a href="index.php?do=sebs.manage.users&filter=teachers" class="btn btn-default">Staff</a>
			  		</div>
			  		<button class="btn btn-success modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.ultralink.tassweb.update&for=students">Update Students from TASS</button>
              		<button class="btn btn-success modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.manage.users.add&type=student">Create New Student</button>

            	</div>
            	<div class="well well-sm">
            	<div class="row">

            		<?php
            				if ($filter == "students") {
            					echo('
            					<div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_yrgrp">
			  		<option value="" selected>All Grades</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_house">
			  			<option value="" selected>All Houses</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-5" style="float:right;">
  					  <div class="input-group" style="margin-bottom: 0;">
   						<span class="input-group-addon"><i class="fa fa-search"></i></span>
              			<input type="text" autocomplete="off" class="form-control" id="userquery" value="'.$query.'" placeholder="Filter By Name" name="query" />
              		  </div>
              		</div>
            					');
            				} else if ($filter == "teachers") {
            					echo('
  					<div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_house">
			  			<option value="" selected>All Houses</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-5" style="float:right;">
  					  <div class="input-group" style="margin-bottom: 0;">
   						<span class="input-group-addon"><i class="fa fa-search"></i></span>
              			<input type="text" autocomplete="off" class="form-control" id="userquery" value="'.$query.'" placeholder="Filter By Name" name="query" />
              		  </div>
              		</div>
            					');
            				} else {
            					echo('
            						<script>$(".panel-body>.well").last().remove();</script>
            					');
            				}
            			?>

				</div>
            	</div>
            	<table id="sebs.view.bookings" data-filter="<?php echo($filter);?>" class="table table-striped table-hover table-condensed">
				</table>
            </div>
          </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
              <h1 class="panel-title">All Bookings</h1>
            </div>
            <div class="panel-body" style="max-height: 91%;overflow-y: scroll;">
            	<div class="well well-sm" style="text-align:right;">
            		<div class="btn-group" style="float:left;">
  						<a href="index.php?do=sebs.manage.users&filter=students" class="btn btn-info disabled">Students</a>
  						<a href="index.php?do=sebs.manage.users&filter=teachers" class="btn btn-default">Staff</a>
			  		</div>
			  		<button class="btn btn-success modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.ultralink.tassweb.update&for=students">Update Students from TASS</button>
              		<button class="btn btn-success modal-trigger" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.manage.users.add&type=student">Create New Student</button>

            	</div>
            	<div class="well well-sm">
            	<div class="row">

            		<?php
            				if ($filter == "students") {
            					echo('
            					<div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_yrgrp">
			  		<option value="" selected>All Grades</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_house">
			  			<option value="" selected>All Houses</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-5" style="float:right;">
  					  <div class="input-group" style="margin-bottom: 0;">
   						<span class="input-group-addon"><i class="fa fa-search"></i></span>
              			<input type="text" autocomplete="off" class="form-control" id="userquery" value="'.$query.'" placeholder="Filter By Name" name="query" />
              		  </div>
              		</div>
            					');
            				} else if ($filter == "teachers") {
            					echo('
  					<div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_house">
			  			<option value="" selected>All Houses</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-5" style="float:right;">
  					  <div class="input-group" style="margin-bottom: 0;">
   						<span class="input-group-addon"><i class="fa fa-search"></i></span>
              			<input type="text" autocomplete="off" class="form-control" id="userquery" value="'.$query.'" placeholder="Filter By Name" name="query" />
              		  </div>
              		</div>
            					');
            				} else {
            					echo('
            						<script>$(".panel-body>.well").last().remove();</script>
            					');
            				}
            			?>

				</div>
            	</div>
            	<table id="sebs.view.bookings" data-filter="<?php echo($filter);?>" class="table table-striped table-hover table-condensed">
				</table>
            </div>
          </div>
        </div>



	  <script>
	  function getData(hn,yg,qu) {
	  document.getElementById('sebs.view.bookings').innerHTML = "<center><h4>Fetching Request...</h4></center>";
	  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		var empdata = JSON.parse(this.responseText);
		if (empdata["status_code"] == "success") {
			if( /AndUser IDroid|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				var tableHtml = "<thead class=\'\'><tr><th>User ID</th><th>Full Name</th><th>Actions</th></tr></thead><tbody>";
			} else {
				var tableHtml = "</h3><thead class=\'\'><tr><th>User ID</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Actions</th></tr></thead><tbody>";
			}
			for(i = 0; i < empdata["DATA"].length; i++){
				if( /AndUser IDroid|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
					var actionWidth = "90px";
					var rowAction = "onclick='empProcess(\""+empdata["DATA"][i]["uuid"]+"\")'";
					var viewBtn = "";
				} else {
					var actionWidth = "125px";
					var rowAction = "onclick='empProcess(\""+empdata["DATA"][i]["uuid"]+"\")'";
					var viewBtn = "";//"<button class='btn btn-default' onclick=\'empProcess(\""+empdata["DATA"][i]["uuid"]+"\")\'><span class='fa fa-eye'></span></button>";
				}
				tableHtml += "<tr style='cursor:pointer;' data-uid=\'"+empdata["DATA"][i]["uuid"]+"\'><td "+rowAction+" class=\'text-warning\'>";
				tableHtml += empdata["DATA"][i]["uuid"];
				tableHtml += "</td><td "+rowAction+">";
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
					tableHtml += empdata["DATA"][i]["fullname"];
				} else {
					if(empdata["DATA"][i]["firstname_pref"] == "") {
						tableHtml += empdata["DATA"][i]["firstname"];
					} else {
						tableHtml += empdata["DATA"][i]["firstname"];
						tableHtml += " (";
						tableHtml += empdata["DATA"][i]["firstname_pref"];
						tableHtml += ")";
					}
					tableHtml += "</td><td "+rowAction+">";
					tableHtml += empdata["DATA"][i]["middlename"];
					tableHtml += "</td><td "+rowAction+">";
					tableHtml += empdata["DATA"][i]["lastname"];
				}



				tableHtml += "</td><td style='text-align: center;min-width: "+actionWidth+";'><div class='btn-group' style='width:100%;'>"+viewBtn;

				if (empdata["DATA"][i]["uuid"] != $('#idtag').data('uid')) {
					tableHtml += "<a class='btn btn-default' style='width:50%;' href=\'mailto:"+empdata["DATA"][i]["email"]+"\'><span class='fa fa-envelope-o'></span></a>";
					tableHtml += "<button class='btn btn-danger modal-trigger' style='width:50%;' data-toggle='modal' data-target='#remotecatcher' data-rh='sebs.manage.users.remove&username="+empdata["DATA"][i]["uuid"]+"'><span class='fa fa-trash'><dead>Remove User</dead></span></button>";
				}
				tableHtml += "</div></td></tr>";

			}
			tableHtml += "</tbody>";
			document.getElementById('sebs.view.bookings').innerHTML = tableHtml;
			readyModal();
		} else {
			var tableHtml = "<h3 id=\'tableerror\'>";
			tableHtml += empdata["status_desc"];
			tableHtml += "</h3>";
			document.getElementById('sebs.view.bookings').innerHTML = tableHtml;
   		}

    }
  };
  xhttp.open("GET", "remote-json.php?do=sebs.view.bookings&query="+qu+"&filter=<?php echo($filter);?>&filter_house="+hn+"&filter_yrgrp="+yg, true);
  xhttp.send();
  }
  getData("","","<?php if(isset($_GET["query"])) {echo($_GET["query"]);} ?>");
	  </script>

<?php
?>
