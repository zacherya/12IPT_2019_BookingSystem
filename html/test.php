<?php
?>

<style>
.items {

}
.items > .items-header {

}
.items > .items-body {
	margin-left: 30px;
}

.items > .items-body > div > .items-cell {
	background: white;
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
	margin-top: -5px;
}
.items > .items-body > div > .items-cell > .items-title-sub {
	font-size:70%;
	color: #ccc;
	margin-top:8px;
}
</style>

<div class="panel panel-primary">
            <div class="panel-heading">
              <h1 class="panel-title">Manage Equiptment</h1>
              
              
              
            </div>
<div class="panel-body" style="max-height: 91%;overflow-y: scroll;">
<div class="well well-sm" style="text-align:right;">
	<button class="btn btn-success modal-trigger" style="" data-toggle="modal" data-target="#remotecatcher" data-rh="sebs.manage.equiptment.add">Add New Item</button>
</div>


<div class="well well-sm">
<div class="row">

            	  <div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_category">
			  		<option value="" selected>All Categories</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-3" style="padding-right:0;">
			  		<select class="form-control" id="filter_sport">
			  			<option value="" selected>All Sports</option>
  					</select>
  					</div>
  					<div class="col-xs-12 col-sm-5 pull-right">
  					  <div class="input-group" style="margin-bottom: 0;">
   						<span class="input-group-addon"><i class="fa fa-search"></i></span>
              			<input type="text" autocomplete="off" class="form-control" id="userquery" placeholder="Filter By Item Name" name="query" />
              		  </div>
              		</div>
            			  
				  </div>
</div>

<div class="row items">
	<div class="col-xs-12 slc-overlay items-header">
		<div class="outer">
			<span>Rugby</span>
		</div>
	</div>
	<div class="items-body">
		<div class="col-xs-12">
			<div class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Game Ball</h3>
			</div>
			<div class="items-cell">
				<h3 class="items-title-sub">Recreation</h3>
				<h3 class="items-title">Ball</h3>
			</div>
			<div class="items-cell">
				<h3 class="items-title-sub">Clothes</h3>
				<h3 class="items-title">Firsts Training Short Sleeve MEDIUM</h3>
			</div>
			<div class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Goal Post Wrap Arounds</h3>
			</div>
			<div class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Goal Post Wrap Arounds</h3>
			</div>
			<div class="items-cell">
				<h3 class="items-title-sub">AIC Equiptment</h3>
				<h3 class="items-title">Goal Post Wrap Arounds</h3>
			</div>
		</div>
	</div>
</div>
</div>
	</div>
<?php
?>