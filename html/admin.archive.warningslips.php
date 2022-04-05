<?php
if ($_SESSION['manager'] == false) {
	echo('<script>window.location.replace("index.php?do=user.dashboard&redirect_reason=role");</script>');
	die();
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
<style>
.dropdown-menu {
  max-height: 20rem;
  overflow-y: auto;
}
</style>
<div class="content">
        <div class="container-fluid">
        	<div class="row">
            	<div class="col-md-12">
            		<h1>Warning Delivery</h1>
            	</div>
            </div>
            <div class="row">
            
            	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
             		<div class="card card-stats" id="complient">
                		<div class="card-header card-header-success card-header-icon">
                  			<div class="card-icon">
                    			<i class="material-icons">how_to_reg</i>
                  			</div>
                  			<p class="card-category">Compliant Slips</p>
                  			<h3 class="card-title">-</h3>
                		</div>
                		<div class="card-footer">
                  			<div class="stats">
                    			<i class="material-icons">done_all</i> These slips have been delivered and acknowledged, no action required.
                  			</div>
                		</div>
              		</div>
            	</div>
            	
            	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
             		<div class="card card-stats" id="notdelivered">
                		<div class="card-header card-header-warning card-header-icon">
                  			<div class="card-icon">
                    			<i class="material-icons">voice_over_off</i>
                  			</div>
                  			<p class="card-category">Undelivered Slips</p>
                  			<h3 class="card-title">-</h3>
                		</div>
                		<div class="card-footer">
                  			<div class="stats">
                    			<i class="material-icons">warning</i> Undelivered slips are deemed invalid warning slips, please action below!
                  			</div>
                		</div>
              		</div>
            	</div>
            	
            	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
             		<div class="card card-stats" id="notacknowledged">
                		<div class="card-header card-header-danger card-header-icon">
                  			<div class="card-icon">
                    			<i class="material-icons">create</i>
                  			</div>
                  			<p class="card-category">Unsigned Slips</p>
                  			<h3 class="card-title">-</h3>
                		</div>
                		<div class="card-footer">
                  			<div class="stats">
                    			<i class="material-icons">error</i> Employee's must acknowledge their slips through their oneJob login!
                  			</div>
                		</div>
              		</div>
            	</div>
            	
            	<!--<div class="col-xl-7 col-lg-7 col-md-7 col-sm-6">
             		<div class="card card-stats" id="">
                		<div class="card-header card-header-secondary card-header-icon">
                  			<div class="card-icon">
                    			<i class="material-icons">info_outline</i>
                  			</div>
                  			<p class="card-category">Count By Category</p>
                  			<h3 class="card-title"></h3>
                		</div>
                		<div class="card-body">
                			<div class="table-responsive">
                    <table class="table table-hover" id="categorylist">
                      
                    </table>
                  </div>
                		</div>
                		<div class="card-footer">
                  			<div class="stats">
                    			<i class="material-icons">local_offer</i> Tracked from Github
                  			</div>
                		</div>
              		</div>
            	</div>-->
            	
            	


            	
            </div>
            
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-danger">
				  <ul class="nav nav-tabs" data-tabs="tabs">
				  		<li class="nav-item" id="warcreate">
                          <a class="nav-link active" href="#addslip" data-toggle="tab">
                            <i class="material-icons">add_box</i> Create Slip
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item" id="warview">
                          <a class="nav-link" href="#viewslip" data-toggle="tab">
                            <i class="material-icons">how_to_vote</i> View Existing Slips
                            <div class="ripple-container"></div>
                          </a>
                        </li>
						
                        <li class="nav-item" id="wardeliver">
                          <a class="nav-link" href="#deliverslip" data-toggle="tab">
                            <i class="material-icons">record_voice_over</i> Deliver Slip
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
					  <!--<hr>
                  <h3 class="card-title ">Employee Master File</h3>-->
                  
                </div>
                <div class="card-body">
					<div class="tab-content">
						<div class="tab-pane active show" id="addslip">
								<div class="progress" id="createprogress" style="height: 10px;">
  									<div class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<hr>
                      			<form id="slipcreate">
                      				<div data-slide="0" style="text-align:center;">
                      					<h2>Want to create a discussion slip?</h2>
                      					<h5>This is done without the crew persons knowledge, once the slip is created it can then be 'delivered' to the crew person.</h5>
                      					
                      						<button type="button" class="btn btn-success" onclick="advance(1)">Let's Get Started!<div class="ripple-container"></div></button>
                      					
                      				</div>
                      				<div data-slide="1" style="display:none;text-align:center;">
                      					<h2>Now, who do we want to deliver this to?</h2>
                      					<h5>Select the crew person from the drop down or filter the list by searching either the employee's ID, First or Lastname</h5>
                      					<div class="dropdown">
    										<button class="btn-lg btn-secondary dropdown-toggle" type="button" style="cursor:pointer;" id="selectempdropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select Employee<div class="ripple-container"></div></button>
   											 											
               	 							<div class="dropdown-menu" aria-labelledby="selectempdropdown">
												<div class="dropdown-header" id="searchdrop">
													<form class="px-4 py-2 dropdown-item">
            											<input type="search" class="form-control" id="formempsearch" placeholder="Search" autofocus="autofocus" style="color:#343434;">
        											</form>
												</div>
												
        										<div id="menuItems"></div>
        										<div id="emptydrop" class="dropdown-header"><h4>No Employee's Found</h4></div>
                							</div>
										</div>
                      					<button type="button" class="btn btn-success float-right" onclick="advance(2)">Next<div class="ripple-container"></div></button>
                      				</div>
                      				<div data-slide="2" style="display:none;text-align:center;">
                      					<h2>Ok, so who is involved or who is going to be present at the delivery?</h2>
                      					<h5>Select the crew person from the list or manually type their name if they aren't an employee.</h5>
                      					<button type="button" class="btn btn-light float-right" onclick="">Add Employee<div class="ripple-container"></div></button>
                      					<button type="button" class="btn btn-light float-right" onclick="">Add Non Employee<div class="ripple-container"></div></button>
                      					
                      					<div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <tr>
                        	<th>Person</th>
                        	<th>Position</th>
                        	<th style="text-align:right;">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="1">
                          <td>
                          	<button class="btn btn-link" type="button" style="cursor:pointer;width: 100%;text-align:left;" id="sepu-1" data-row="1" data-toggle="modal" data-target="#pickemp" onclick="prepareModal('1')"><b>Select Employee</b><div class="ripple-container"></div></button>
                          </td>
                          <td>
                          	Manager
                          </td>
                          <td><button type="button" class="btn btn-danger btn-sm float-right" onclick="removerow('1')">&times;<div class="ripple-container"></div></button></td>
                        </tr>
                        <tr id="2">
                          <td>
                          	<button class="btn btn-link" type="button" style="cursor:pointer;width: 100%;text-align:left;" id="sepu-2" data-row="2" data-toggle="modal" data-target="#pickemp" onclick="prepareModal('2')"><b>Select Employee</b><div class="ripple-container"></div></button>
                          </td>
                          <td>
                          	Employee
                          </td>
                          <td><button type="button" class="btn btn-danger btn-sm float-right" onclick="removerow('2')">&times;<div class="ripple-container"></div></button></td>
                        </tr>
                        <tr id="3">
                          <td>
                          	<button class="btn btn-link" type="button" style="cursor:pointer;width: 100%;text-align:left;" id="sepu-3" data-row="3" data-toggle="modal" data-target="#pickemp" onclick="prepareModal('3')"><b>Select Employee</b><div class="ripple-container"></div></button>
                          </td>
                          <td>
                          	<div class="form-group bmd-form-group is-filled">
                         	 	<select class="form-control" name="job_status[]">
      								<option>Manager</option>
      								<option>Employee</option>
      								<option>Employer Witness</option>
    					  		</select>
                        	</div>
                          </td>
                          <td><button type="button" class="btn btn-danger btn-sm float-right" onclick="removerow('3')">&times;<div class="ripple-container"></div></button></td>
                        </tr>
                        <tr id="4">
                          <td>
                          	<button class="btn btn-link" type="button" style="cursor:pointer;width: 100%;text-align:left;" id="sepu-4" data-row="4" data-toggle="modal" data-target="#pickemp" onclick="prepareModal('4')"><b>Select Employee</b><div class="ripple-container"></div></button>
                          </td>
                          <td>
                          	<div class="form-group bmd-form-group is-filled">
                         	 	<select class="form-control" name="job_status[]">
      								<option>Manager</option>
      								<option>Employee</option>
      								<option>Employer Witness</option>
    					  		</select>
                        	</div>
                          </td>
                          <td><button type="button" class="btn btn-danger btn-sm float-right" onclick="removerow(4')">&times;<div class="ripple-container"></div></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                      		
                      					<button type="button" class="btn btn-success float-right" onclick="advance(3)">Next<div class="ripple-container"></div></button>
                      					<button type="button" class="btn btn-warning float-left" onclick="devance(1)">Back<div class="ripple-container"></div></button>
                      				</div>
                      			</form>
                      			
						</div>
						<div class="tab-pane" id="viewslip">
							<table id="admin.employee.masterfile.list" class="table">
                      			<h1>View</h1>
							</table>
						</div>
						<div class="tab-pane" id="deliverslip">
							<table id="admin.employee.masterfile.list" class="table">
                      			<h1>Deliver</h1>
							</table>
						</div>
					</div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      
      <!-- Select Emp Modal -->
<div class="modal fade" id="pickemp" data-row="1">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Select Employee</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form class="px-4 py-2">
        	<input type="search" class="form-control" id="pickemp-search" placeholder="Search" autofocus="autofocus" style="color:#343434;">
        </form>
		<div id="pickemp-list"></div>
        <div id="pickemp-none"><h4>No Employee's Found</h4></div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
      
      <!-- Help Modal -->
<div class="modal fade" id="percentagehelp">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Slip Performance Indicators (Percentage) - Help</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        The percentage performance indicators are an incentive to encourage accurrate documentation of employee warning slips.
        <br>It measures the stores accuracy by looking at all unacknowledged, undelivered and complete slips to obtain a percentage of completion.
        <br><br><h3>The percentages value ranges</h3>
        <div class="alert alert-success"><span><h3 style="margin:0;">90%-100%</h3> Falling into the green category means the majority of slips are filed correctly and are complient to state law.</span></div>
        <div class="alert alert-warning"><span><h3 style="margin:0;">75%-89%</h3> Falling into the amber category means a fair amount of slips are filed correctly and may or may not be complient to state law, you should action these where you can.</span></div>
        <div class="alert alert-danger"><span><h3 style="margin:0;">0%-74%</h3> Falling into the red category means the majority of slips are filed incorrectly and are not complient to state law, this means you need to action your slip archive immediatly to comply with state law.</span></div>
        <br>
        <h3>How are the cells calculated?</h3>
        <p>The complient cell gains a numerical count value from all the warning slips that have been delivered and acknowledged, where as the percentage is calculated from obtaining this value and comparing it to the total slip count.</p>
        <p>The undelivered cell obtains a count value based on any warning slips that haven't been delivered. The percentage is calculated by comparing the total slip count to the undelivered slip count.</p>
        <p>The unacknowledged cell's count value is obtained from any warning slips that haven't had the employee acknowledge the slip from their web portal login. The percentage is calculated by comparing the total slip count to the unacknowledged slip count.</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

		<script>
			$("div.sidebar").attr("data-color","danger");
		</script>
		
		<script>
		//Initialize with the list of symbols

//Find the input search box
let search = document.getElementById("formempsearch");

//Find every item inside the dropdown
let items = document.getElementsByClassName("dropdown-item");
function buildDropDown(q) {
	var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		var empdata = JSON.parse(this.responseText);
		if (empdata["status_code"] == "success") {
			let contents = [];
			for(i = 0; i < empdata["DATA"].length; i++){
				var name = empdata["DATA"][i]["full_name"];
				var empcode = empdata["DATA"][i]["emp_code"];

    			contents.push('<input type="button" class="dropdown-item" type="button" style="cursor:pointer;" id="' + empcode + '" value="' + name + '"/>');
			}
			
			$('#menuItems').html('');
			$('#menuItems').append(contents.join(""));
			
			//Hide the row that shows no items were found
    		$('#emptydrop').hide();
    		
    		if (empdata["DATA"].length == 0) {
				$('#emptydrop').show();
			}
		} else {
			$('#menuItems').html('');
			$('#searchdrop').hide();
			$('#emptydrop').show();
   		}
		
    }
  };
  xhttp.open("GET", "remote-json.php?do=admin.employee.masterfile.list&query="+q, true);
  xhttp.send();
    
}

//Capture the event when user types into the search box
document.getElementById('formempsearch').addEventListener('input', function () {
    buildDropDown(search.value);
})

//If the user clicks on any item, set the title of the button as the text of the item
$('#menuItems').on('click', '.dropdown-item', function(){
    $('#selectempdropdown').text($(this)[0].value);
    $('#selectempdropdown').attr('data-selected-emp',$(this)[0].id);
    $("#selectempdropdown").dropdown('toggle');
})

buildDropDown('');
		</script>
	  