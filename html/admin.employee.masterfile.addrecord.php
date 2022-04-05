
<?php
if($_SESSION['store_manager']) {
	$storeManCheck = '<div class="col-md-12">
                      	<div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="access" value="1">
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
							General Manager
                        </label>
                    	</div>
                      </div>
                      <div class="col-md-12">
                      	<div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="access" value="2">
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
							Store Manager
                        </label>
                    	</div>
                      </div>';
} else {
	$storeManCheck = '';
}

$curDate = date("Y-m-d");
$addRecord = ('
	<div class="tab-pane" id="create">
		<h2>Create New Employee Record</h2>
		<form id="empcreate">
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Assigned Employee Code</label>
                          <input type="text" class="form-control" disabled="" name="emp_code" value="">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">(Optional) Other Employee ID</label>
                          <input type="text" class="form-control" name="emp_id">
                        </div>
                      </div>
                    </div>
					
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Legal First Name</label>
                          <input type="text" class="form-control" name="first_name">
                        </div>
                      </div>
					  <div class="col-md-3">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">(Optional) Prefered First Name</label>
                          <input type="text" class="form-control" name="first_name_pref">
                        </div>
                      </div>
					  <div class="col-md-5">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">(Optional) Middle Name</label>
                          <input type="text" class="form-control" name="middle_name">
                        </div>
                      </div>
					  <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Last Name</label>
                          <input type="text" class="form-control" name="last_name">
                        </div>
                      </div>
					  
                    </div>
                    
                    <br>
					<h4>Employee Self Info</h4>
					<p>Enter the employee\'s date of birth and gender as it appears on the provided legal documentation</p>
                    <div class="row">
                    
                      <div class="col-md-3">
                      	<div class="form-group bmd-form-group">
    						<label class="bmd-label-floating">Gender</label>
   	 						<select class="form-control" name="gender">
      							<option value="M">Male</option>
      							<option value="F">Female</option>
      							<option value="O">Other</option>
    						</select>
  						</div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-1 col-2">
					  	<div class="form-group bmd-form-group">
    						<label class="bmd-label-floating">Day</label>
   	 						<select class="form-control" name="dob-day">
      							<option>1</option>
    						</select>
  						</div>
					  </div>
					  <div class="col-md-3 col-6">
					  	<div class="form-group bmd-form-group">
    						<label class="bmd-label-floating">Month</label>
   	 						<select class="form-control" name="dob-month" onchange="updateDayDropDown()">
      							<option value="01">January</option>
      							<option value="02">Febuary</option>
      							<option value="03">March</option>
      							<option value="04">April</option>
      							<option value="05">May</option>
      							<option value="06">June</option>
      							<option value="07">July</option>
      							<option value="08">August</option>
      							<option value="09">September</option>
      							<option value="10">October</option>
      							<option value="11">November</option>
      							<option value="12">December</option>
    						</select>
  						</div>
					  </div>
					  <div class="col-md-2 col-4">
					  	<div class="form-group bmd-form-group">
    						<label class="bmd-label-floating">Year</label>
   	 						<select class="form-control" name="dob-year">
      							<option value="2018">2018</option>
    							
    						</select>
  						</div>
					  </div>
                    </div>
                    
					<br>
					<h4>Employee Contact Details</h4>
					<p>Ensure all details entered are accurate</p>
                    <div class="row">
					  <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Mobile Number</label>
                          <input type="tel" class="form-control" name="mobile_no">
                        </div>
                      </div>
					  <div class="col-md-4">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">Email Address</label>
							<input type="email" class="form-control" name="email">
						</div>
					  </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Address</label>
                          <input type="text" class="form-control" name="address">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Suburb</label>
                          <input type="text" class="form-control" name="suburb">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Postcode</label>
                          <input type="number" class="form-control" name="postcode">
                        </div>
                      </div>
					  <div class="col-md-3">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">City</label>
                          <input type="text" class="form-control" name="city">
                        </div>
                      </div>
					  <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Country</label>
                          <input type="text" class="form-control" name="country">
                        </div>
                      </div>
					</div>
					
					<br>
					
					<h4>Emergency Contact Details</h4>
					<p>Employee\'s Emergency Contact Details</p>
                    <div class="row">
                    <div class="col-md-6">
                    <h4>Contact 1</h4>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Contact Full Name</label>
                          <input type="text" class="form-control" name="contact_name[]">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
    						<label class="bmd-label-floating">Relationship</label>
   	 						<select class="form-control" name="relationship[]">
      							<option>Mother</option>
      							<option>Father</option>
      							<option>Parent</option>
      							<option>Brother</option>
      							<option>Sister</option>
      							<option>Auntie</option>
      							<option>Uncle</option>
      							<option>Son</option>
								<option>Daughter</option>
								<option>Friend</option>
      							<option>Grandmother</option>
      							<option>Grandfather</option>
      							<option>Godparent</option>
      							<option>Spouse</option>
      							<option>Partner</option>
      							<option>Flatmate</option>
      							<option>Physician</option>
      							<option>Doctor</option>
      							<option>Other</option>
    						</select>
  						</div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Mobile Number</label>
                          <input type="tel" class="form-control" name="mobile_no[]">
                        </div>
                      </div>
					  <div class="col-md-12">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">Email Address</label>
							<input type="email" class="form-control" name="email[]">
						</div>
					  </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Address</label>
                          <input type="text" class="form-control" name="address[]">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Suburb</label>
                          <input type="text" class="form-control" name="suburb[]">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Postcode</label>
                          <input type="number" class="form-control" name="postcode[]">
                        </div>
                      </div>
					  <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">City</label>
                          <input type="text" class="form-control" name="city[]">
                        </div>
                      </div>
					  <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Country</label>
                          <input type="text" class="form-control" name="country[]">
                        </div>
                      </div>
                      </div>
                      
                      
                      <div class="col-md-6">
                      <h4>Contact 2</h4>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Contact Full Name</label>
                          <input type="text" class="form-control" name="contact_name[]">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
    						<label class="bmd-label-floating">Relationship</label>
   	 						<select class="form-control" name="relationship[]">
      							<option>Mother</option>
      							<option>Father</option>
      							<option>Parent</option>
      							<option>Brother</option>
      							<option>Sister</option>
      							<option>Auntie</option>
      							<option>Uncle</option>
      							<option>Son</option>
								<option>Daughter</option>
								<option>Friend</option>
      							<option>Grandmother</option>
      							<option>Grandfather</option>
      							<option>Godparent</option>
      							<option>Spouse</option>
      							<option>Partner</option>
      							<option>Flatmate</option>
      							<option>Physician</option>
      							<option>Doctor</option>
      							<option>Other</option>
    						</select>
  						</div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Mobile Number</label>
                          <input type="tel" class="form-control" name="mobile_no[]">
                        </div>
                      </div>
					  <div class="col-md-12">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">Email Address</label>
							<input type="email" class="form-control" name="email[]">
						</div>
					  </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Address</label>
                          <input type="text" class="form-control" name="address[]">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Suburb</label>
                          <input type="text" class="form-control" name="suburb[]">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Postcode</label>
                          <input type="number" class="form-control" name="postcode[]">
                        </div>
                      </div>
					  <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">City</label>
                          <input type="text" class="form-control" name="city[]">
                        </div>
                      </div>
					  <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Country</label>
                          <input type="text" class="form-control" name="country[]">
                        </div>
                      </div>
                      </div>
                      
                    </div>
                    
                    <br>
                    <h4>Payroll Information</h4>
					<p>Please enter payroll information for the provided employee</p>
                    <div class="row">
                      <div class="col-md-4 col-8">
                        <div  id="prcontainer" class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Payroll ID</label>
                          <input type="text" class="form-control" name="payroll_id">
                        </div>
                      </div>
                      <div class="col-md-2 col-4">
                        <div class="form-group bmd-form-group">
                          <button type="button" class="btn btn-danger btn-round btn-sm" onclick="generatePayroll()">Generate<div class="ripple-container"></div></button>
                        </div>
                      </div>
                    </div>
                	<div class="row">
                      <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Tax File Number</label>
                          <input type="text" class="form-control" name="tfn">
                        </div>
                      </div>
                    </div>
                    
                    
                    <div class="row">
                      <div class="col-md-12">
                    	<div class="form-check">
                        	<label class="form-check-label">
                            	<input class="form-check-input" type="radio" name="stud_type" value="1" checked>
                            	<span class="form-check-sign">
                                	<span class="check"></span>
                            	</span>
								Is School Student
                        	</label>
                    	</div>
                    	<div class="form-check">
                        	<label class="form-check-label">
                            	<input class="form-check-input" type="radio" name="stud_type" value="2">
                            	<span class="form-check-sign">
                                	<span class="check"></span>
                            	</span>
								Is University Student
                        	</label>
                    	</div>
                    	<div class="form-check">
                        	<label class="form-check-label">
                            	<input class="form-check-input" type="radio" name="stud_type" value="0" checked>
                            	<span class="form-check-sign">
                                	<span class="check"></span>
                            	</span>
								Doesn\'t Attend An Education Institute
                        	</label>
                    	</div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Job Status</label>
                          <select class="form-control" name="job_status[]">
      							<option>Casual</option>
      							<option>Part Time</option>
      							<option>Full Time</option>
    					  </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Job</label>
                          <select class="form-control" name="job_id[]">
      							
    					  </select>
                        </div>
                      </div>
                    </div>
                    
                    <br>
                    <h4>Employee Access Level</h4>
					<p>Select the employee\'s permission level for portal access</p>
                    <div class="row">
                    <div class="col-md-12">
                      	<div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="access" value="0" checked>
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
							Crew
                        </label>
                    	</div>
                      </div>
                      
                      '.$storeManCheck.'
                    </div>
                    
                    <br>
                    <h4>Employee Permissions</h4>
					<p>Select the individual permission boundaries for the employee, this can be changed later on</p>
                    <div class="row">
                      <div class="col-md-12">
                      	<div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="web_access" value="1" checked>
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
							Can Access Web Portal
                        </label>
                    	</div>
                    	
                    	<div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="can_creategroup" value="1" >
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
							Can Create Chat Group
                        </label>
                    	</div>
                    	
                    	<div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="can_communicate" value="1" checked>
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
							Can Communicate On Group Chat
                        </label>
                    	</div>
                    	
                      </div>
                    </div>
                    
                    <br>
					
					<h4>Employee Additional Notes</h4>
					<p>Enter further medical details, action notes, disabilities or secondary jobs</p>
                    <div class="row">
                    	
                    </div>
					
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">Employee Notes</label>
                            <textarea class="form-control" rows="5" name="additional_info"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
					<div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="_sendmail" value="1" checked>
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
							Send Employee Login Details via Email
                        </label>
                    </div>
                    <input type="hidden" name="comm_date" value="'.$curDate.'">
                    <button type="submit" class="btn btn-success pull-right">Create Employee Record<div class="ripple-container"></div></button>
                    <div class="clearfix"></div>
                  </form>
	</div>
');
?>