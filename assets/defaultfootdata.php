

        <!-- Initiate Core Login JS Script -->

	  <?php
	  	echo('<script>ZAPlaxImg();var a = document.getElementById("'.$_GET["do"].'");if(a!=null){a.classList.add("active")};var b = document.getElementById("'.$_GET["do"].'.plex");if(b!=null){b.classList.add("dead")};</script>');
			if(strpos($_GET["do"], 'sebs.process.bah') !== false){
				//contains process trader_cdlseparatinglines
				echo('<script>$("#linksgrid").remove();$("#windowGrid").removeClass("col-sm-8");$("#windowGrid").addClass("col-sm-12");</script>');
			}
		?>
