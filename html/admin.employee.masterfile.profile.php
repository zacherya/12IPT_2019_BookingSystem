<?php
if (isset($_GET['emp_code']) == false) {
	echo('<script>window.location.replace("index.php?do=admin.employee.masterfile");</script>');
	die();
} else {
	echo('
	<div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
			<div class="card card-profile">
                <div class="card-avatar">
                  <a href="#pablo">
                    <img class="img" src="remote-resource.php?do=admin.employee.masterfile.profile.img&emp_code='.$_GET['emp_code'].'">
                  </a>
                </div>
                <div class="card-body">
                  <h6 class="card-category">CEO / Co-Founder</h6>
                  <h4 class="card-title">Alec Thompson</h4>
                  <p class="card-description">
                    Don\'t be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...
                  </p>
                  <a href="#pablo" class="btn btn-success btn-round">Modify</a>
				  <a href="#pablo" class="btn btn-danger btn-round">Modify</a>
                </div>
			</div>
			</div>
		</div>
	</div>');
}
?>