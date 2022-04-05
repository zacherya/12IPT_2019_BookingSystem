<?php
include('db_vars.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
	//header("Location: index.php?do=user.dashboard"); 
	$empty = array("status_code"=>"error","status_desc"=>"You do not have permission to view this data!","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
}

function BlockSQLInjection($str) {
	$string = str_replace("'","\'",$str);
	$string = str_replace('"','\"',$string);
	return str_replace(array("&quot;\"'","&quot;"),"",$string);
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	$empty = array("status_code"=>"error","status_desc"=>$conn->connect_error,"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
	die();
} 

$intent = $_GET['intent'] ?? "setup"; //setup(d) - save
$type = $_GET['type'] ?? "student"; //student(d) - teacher

if ($intent == "setup") {
	$empty = array("status_code"=>"success","status_desc"=>"","intent"=>$intent,"body_html"=>"remote-html.php?do=sebs.manage.equiptment.add","footer_html"=>'<button type="button" class="btn btn-default" style="float:left;" data-dismiss="modal">Cancel</button><button id="additembtn" type="button" class="btn btn-success">Create New Item</button>',"DATA"=>array());
    $json = json_encode($empty);
	echo($json);
} else if ($intent == "save") {
	
	if(isset($_POST["name"],$_POST["sport"],$_POST["category"],$_POST["stock_qty"],$_POST["max_qty"],$_POST["max_period_num"],$_POST["max_period_factor"],$_POST["staff_alwd"],$_POST["stud_alwd"])) {
		//validation of data time
		$ferr = array();
		if(str_replace(" ","",BlockSQLInjection($_POST["name"])) == "") {
			$ferr[] = "name";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["sport"])) == "") {
			$ferr[] = "sport";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["category"])) == "") {
			$ferr[] = "category";
		}
		if(!is_int(json_decode(BlockSQLInjection($_POST["stock_qty"])))) {
			$ferr[] = "stock_qty";
		}
		if(!is_int(json_decode(BlockSQLInjection($_POST["max_qty"])))) {
			$ferr[] = "max_qty";
		}
		if(!is_float(json_decode(BlockSQLInjection($_POST["max_period_num"]))) && !is_int(json_decode(BlockSQLInjection($_POST["max_period_num"])))) {
			$ferr[] = "max_period_num";
		}
		if(BlockSQLInjection($_POST["max_period_factor"]) != "m" && BlockSQLInjection($_POST["max_period_factor"]) != "h" && BlockSQLInjection($_POST["max_period_factor"]) != "d" && BlockSQLInjection($_POST["max_period_factor"]) != "w" && BlockSQLInjection($_POST["max_period_factor"]) != "mth") {
			$ferr[] = "max_period_factor";
		}
		if(!is_bool(json_decode(BlockSQLInjection($_POST["staff_alwd"])))) {
			$ferr[] = "staff_alwd";
		}
		if(!is_bool(json_decode(BlockSQLInjection($_POST["stud_alwd"])))) {
			$ferr[] = "stud_alwd";
		}
		
		if(count($ferr) > 0) {
			$empty = array("status_code"=>"error","status_desc"=>"Field Set Error","DATA"=>$ferr);
    		$json = json_encode($empty);
			echo($json);
			die();
		}
		
		//do time calculations
		switch (BlockSQLInjection($_POST["max_period_factor"])) {
			case "m":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]))/1440;
				break;
			case "h":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]))/24;
				break;
			case "d":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]));
				break;
			case "w":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]))*7;
				break;
			case "mth":
				$dayval = (json_decode(BlockSQLInjection($_POST["max_period_num"]))*28);
				break;
			default:
				$empty = array("status_code"=>"error","status_desc"=>"TIME CALCULATION ERROR!!!! Aborted to avoid database curruption","DATA"=>$ferr);
    			$json = json_encode($empty);
				echo($json);
				die();
				break;
		}
		
		//determine sport and category
		/*if($_POST["sport"] == "Other") {
			$sport = $_POST["sport_other"];
		} else {
			$sport = $_POST["sport"];
		}
		if($_POST["category"] == "Other") {
			$sport = $_POST["category_other"];
		} else {
			$sport = $_POST["category"];
		}*/
		
		$sql = "INSERT INTO items (name, category, sport, stock_qty, max_qty, max_days, stud_alwd, staff_alwd) VALUES ('".BlockSQLInjection($_POST["name"])."','".BlockSQLInjection($_POST["category"])."','".BlockSQLInjection($_POST["sport"])."',".BlockSQLInjection($_POST["stock_qty"]).",".BlockSQLInjection($_POST["max_qty"]).",".$dayval.",".BlockSQLInjection($_POST["stud_alwd"]).",".BlockSQLInjection($_POST["staff_alwd"]).")";
			//update values in DB
		if ($conn->query($sql) === TRUE) {
    		$empty = array("status_code"=>"success","status_desc"=>"Item Added Successfully","intent"=>$intent,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		} else {
		    $empty = array("status_code"=>"error","status_desc"=>"Bad internal query: ". $conn->error,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}
			
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Form Data is invalid!","DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}
	
} else if ($intent == "update") {
	if(isset($_POST["item_code"],$_POST["name"],$_POST["sport"],$_POST["category"],$_POST["stock_qty"],$_POST["max_qty"],$_POST["max_period_num"],$_POST["max_period_factor"],$_POST["staff_alwd"],$_POST["stud_alwd"])) {
		//validation of data time
		$ferr = array();
		if(str_replace(" ","",BlockSQLInjection($_POST["item_code"])) == "") {
			$ferr[] = "item_code";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["name"])) == "") {
			$ferr[] = "name";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["sport"])) == "") {
			$ferr[] = "sport";
		}
		if(str_replace(" ","",BlockSQLInjection($_POST["category"])) == "") {
			$ferr[] = "category";
		}
		if(!is_int(json_decode(BlockSQLInjection($_POST["stock_qty"])))) {
			$ferr[] = "stock_qty";
		}
		if(!is_int(json_decode(BlockSQLInjection($_POST["max_qty"])))) {
			$ferr[] = "max_qty";
		}
		if(!is_float(json_decode(BlockSQLInjection($_POST["max_period_num"]))) && !is_int(json_decode(BlockSQLInjection($_POST["max_period_num"])))) {
			$ferr[] = "max_period_num";
		}
		if(BlockSQLInjection($_POST["max_period_factor"]) != "m" && BlockSQLInjection($_POST["max_period_factor"]) != "h" && BlockSQLInjection($_POST["max_period_factor"]) != "d" && BlockSQLInjection($_POST["max_period_factor"]) != "w" && BlockSQLInjection($_POST["max_period_factor"]) != "mth") {
			$ferr[] = "max_period_factor";
		}
		if(!is_bool(json_decode(BlockSQLInjection($_POST["staff_alwd"])))) {
			$ferr[] = "staff_alwd";
		}
		if(!is_bool(json_decode(BlockSQLInjection($_POST["stud_alwd"])))) {
			$ferr[] = "stud_alwd";
		}
		
		if(count($ferr) > 0) {
			$empty = array("status_code"=>"error","status_desc"=>"Field Set Error","DATA"=>$ferr);
    		$json = json_encode($empty);
			echo($json);
			die();
		}
		
		//do time calculations
		switch (BlockSQLInjection($_POST["max_period_factor"])) {
			case "m":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]))/1440;
				break;
			case "h":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]))/24;
				break;
			case "d":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]));
				break;
			case "w":
				$dayval = json_decode(BlockSQLInjection($_POST["max_period_num"]))*7;
				break;
			case "mth":
				$dayval = (json_decode(BlockSQLInjection($_POST["max_period_num"]))*28);
				break;
			default:
				$empty = array("status_code"=>"error","status_desc"=>"TIME CALCULATION ERROR!!!! Aborted to avoid database curruption","DATA"=>$ferr);
    			$json = json_encode($empty);
				echo($json);
				die();
				break;
		}
		
		//determine sport and category
		/*if($_POST["sport"] == "Other") {
			$sport = $_POST["sport_other"];
		} else {
			$sport = $_POST["sport"];
		}
		if($_POST["category"] == "Other") {
			$sport = $_POST["category_other"];
		} else {
			$sport = $_POST["category"];
		}*/
		
		$sql = "UPDATE items SET name='".BlockSQLInjection($_POST["name"])."',category='".BlockSQLInjection($_POST["category"])."',sport='".BlockSQLInjection($_POST["sport"])."',stock_qty=".BlockSQLInjection($_POST["stock_qty"]).",max_qty=".BlockSQLInjection($_POST["max_qty"]).",max_days=".$dayval.",stud_alwd=".BlockSQLInjection($_POST["stud_alwd"]).",staff_alwd=".BlockSQLInjection($_POST["staff_alwd"])." WHERE item_code=".BlockSQLInjection($_POST["item_code"]);
			//update values in DB
		if ($conn->query($sql) === TRUE) {
    		$empty = array("status_code"=>"success","status_desc"=>"Item Updated Successfully","intent"=>$intent,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		} else {
		    $empty = array("status_code"=>"error","status_desc"=>"Bad internal query: ". $conn->error,"DATA"=>array());
    		$json = json_encode($empty);
			echo($json);
		}
			
	} else {
		$empty = array("status_code"=>"error","status_desc"=>"Form Data is invalid!","DATA"=>array());
    	$json = json_encode($empty);
		echo($json);
	}
} else {
	$empty = array("status_code"=>"error","status_desc"=>"Bad intent provided","DATA"=>array());
    $json = json_encode($empty);
	echo($json);
}


$conn->close();
?>