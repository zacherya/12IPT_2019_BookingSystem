<?php

?>
function setupWaiting() {
	var hl='<div id="busy-loader" class="loaded"><div class="busy-loader-container"><center><div class="ZARipple"><div></div><div></div></div><p id="ZARipple-desc">Loading...</p></center></div></div>';
	$(hl).insertBefore("body > nav");
	$(hl).insertBefore("body.auth-body > div:first-child");
}

function showWaiting(desc) {
	$("#busy-loader").removeClass("loaded");
	$("#ZARipple-desc").html(desc);
}

function hideWaiting() {
	$("#busy-loader").addClass("loaded");
}

$(document).ready(function(){
	setupWaiting()
	if ($("link[href='include-css.php?do=portal.zaloading']").length == false) {
		$("head").append('<link type="text/css" rel="stylesheet" href="include-css.php?do=portal.zaloading">');
	}
	
});
