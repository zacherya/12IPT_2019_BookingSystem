<?php
$uuid = $_GET["username"] ?? 0;
$useragent=$_SERVER['HTTP_USER_AGENT'];

if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	$mobile=true;
} else {
	$mobile=false;
}

?>

<div class="panel panel-default" style="box-shadow: -1px 6px 15px rgba(14,21,47,0.2);border: 0px solid transparent;">
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
				<label class="control-label col-sm-2" for="email">E-Mail</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="email" value="" name="email" disabled>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label class="control-label col-sm-2" for="firstname">Name</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="firstname" value="" placeholder="* First Name (Mitchel)" name="firstname" disabled>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="firstname_pref" value="" placeholder="Pref Name (Mitch)" name="firstname_pref" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="middlename"></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="middlename" value="" placeholder="Middle Name (James)" name="middlename" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="lastname"></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="lastname" value="" name="lastname" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="dob">DOB</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="dob" value="" name="dob" disabled>
				</div>
				<label class="control-label col-sm-2" for="gender">Gender</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="gender" value="" name="gender" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="hse_name">House</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="hse_name" name="hse_name" disabled>
				</div>
			</div>
		</form>
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
						$("#hse_name").val("N/A");
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
					$("#dob").val(dobArr[2]+"/"+dobArr[1]+"/"+dobArr[0]);

					switch (data["DATA"]["info"]["gender"]) {
						case "M":
							$("#gender").val("Male");
							break;
						case "F":
							$("#gender").val("Female");
							break;

						default:
							$("#gender").val("Other");
							break;
					}

					if(data["DATA"]["info"]["year_grp"] === null) {
						//setup view for teacher
						$(".panel-title").first().html("Viewing My Profile")
					}

					$(".panel-footer").removeClass("hidden");

				} else {
					$("div.modal-body").html("<h3>There was an error loading the booking!<br>"+data["status_desc"]+"</h3>");
					$("div.modal-footer").html('<button onclick="location.reload();" type="button" class="btn btn-default" data-dismiss="modal">Done</button>');
				}

			} else {

			}
  		};
  		xhttp.open("GET", "remote-json.php?do=sebs.manage.users.view&intent=display&username=<?php echo($_SESSION['uuid']); ?>", true);
  		xhttp.send();
  		}
  		$(document).ready(function(){
  			showUser();
		});
        </script>

<?php
?>
