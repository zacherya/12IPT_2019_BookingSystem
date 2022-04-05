<?php

?>

$.fn.hasAttr = function(name) {  
   return this.attr(name) !== undefined;
};

function setupCards() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		var data = JSON.parse(this.responseText);
		if (data["status_code"] == "success") {
			
			var complientPercentage =  Math.round((data["DATA"]["complient"]/(data["DATA"]["complient"]+data["DATA"]["not_acknowledged"]))*100);
			if (isNaN(complientPercentage)) {
				complientPercentage = 100;
			}
			var complientRating = "text-danger";
			if (complientPercentage >= 90) {
				complientRating = "text-success";
			} else if (complientPercentage >= 75) {
				complientRating = "text-warning";
			}
			$('#complient h3.card-title').first().html(data["DATA"]["complient"] + ' (<a class="'+complientRating+'" style="cursor:pointer;" data-toggle="modal" data-target="#percentagehelp"><b>' + complientPercentage + '%</b></a>)');
			
			$('#notdelivered h3.card-title').first().html(data["DATA"]["not_delivered"]);
			$('#notacknowledged h3.card-title').first().html(data["DATA"]["not_acknowledged"]);
			/*
			var tableHead = '<thead class=""><tr><th>Category</th><th>Warning Count</th><th>Percentage %</th></tr></thead>';
			var tableBody = '<tbody>';
			
			for(i = 0; i < data["DATA"]["category_count"].length; i++){
    				tableBody += '<tr><td>'+ data["DATA"]["category_count"][i]["category"] +'</td><td>'+ data["DATA"]["category_count"][i]["slip_count"] +'</td><td>'+ Math.round((data["DATA"]["category_count"][i]["slip_count"]/(data["DATA"]["complient"]+data["DATA"]["not_acknowledged"]))*100) +'</td></tr>';
				
			}
			      
            tableBody += '</tbody>';
            $('#categorylist').html(tableHead+tableBody);*/
			
		} else {
			
   		}
		
    }
  };
  xhttp.open("GET", "remote-json.php?do=admin.archive.warningslip&terminated=true&current=false", true);
  xhttp.send();
}

function updateBar(v) {
	var t = $("#slipcreate").children().length+1;
	var nv = (v/t)*100;
	$("#createprogress div").first().width(nv+'%');
	$("#createprogress div").first().attr('aria-valuenow',nv);
}

function advance(d) {
	if (d == 2 && !$('#selectempdropdown').hasAttr('data-selected-emp')) {
		sn('top', 'center', 'warning', '<b>Please select an employee before advancing to the next slip process!</b>');
		$('#selectempdropdown').find('.ripple-container').trigger('mousedown, mouseup, click'); 
		return;
	}

	var nextIndex = Number(d);
	$('div[data-slide='+(nextIndex-1)+']').fadeOut();
	updateBar(d);
    $('div[data-slide='+(nextIndex)+']').delay(350).fadeIn();
}
function devance(d) {
	var preIndex = Number(d);
	$('div[data-slide='+(preIndex+1)+']').fadeOut();
	updateBar(d);
    $('div[data-slide='+(preIndex)+']').delay(350).fadeIn();
}

function sn(from, align, type, message) {

    color = Math.floor((Math.random() * 5) + 1);

    $.notify({
      icon: "report",
      message: message

    }, {
      type: type,
      timer: 500,
      placement: {
        from: from,
        align: align
      }
    });
  }
  
//MODAL SELECT EMP
function buildEmpList(q) {
	var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		var empdata = JSON.parse(this.responseText);
		if (empdata["status_code"] == "success") {
			let contents = [];
			for(i = 0; i < empdata["DATA"].length; i++){
				var name = empdata["DATA"][i]["full_name"];
				var empcode = empdata["DATA"][i]["emp_code"];

    			contents.push('<input type="button" class="list-group-item list-group-item-action" type="button" style="cursor:pointer;" id="' + empcode + '" value="' + name + '"/>');
			}
			
			$('#pickemp-list').html('');
			$('#pickemp-list').append(contents.join(""));
			
			//Hide the row that shows no items were found
    		$('#pickemp-none').hide();
    		
    		if (empdata["DATA"].length == 0) {
				$('#emptydrop').show();
			}
		} else {
			$('#pickemp-list').html('');
			$('#pickemp-search').hide();
			$('#pickemp-none').show();
   		}
		
    }
  };
  xhttp.open("GET", "remote-json.php?do=admin.employee.masterfile.list&query="+q, true);
  xhttp.send();
    
}

function prepareModal(j) {
	$("#pickemp").attr('data-row',j);
	 buildEmpList('');
	 	
}



$(document).ready(function(){
	setupCards();
	
	//Capture the event when user types into the search box of modal
	var modalSearchBar = document.getElementById('pickemp-search');
	document.getElementById('pickemp-search').addEventListener('input', function () {
    	buildEmpList(modalSearchBar.value);
	})
	
	//If the user clicks on any item, set the title of the button as the text of the item
		$('#pickemp-list').on('click', '.list-group-item', function(){
			var mce = $('#pickemp').attr('data-row');
    		$('#sepu-'+mce).text($(this)[0].value);
    		$('#sepu-'+mce).attr('data-selected-emp',$(this)[0].id);
    		$('#pickemp').modal('toggle');
		})
    
});
