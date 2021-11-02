<?php //$IcoPath = $arrSetting['Path']['ico']; ?>

<div style="margin-top: 25px;"></div>

<div id="down_mn" style="position: fixed;z-index: 10;margin: 0;padding: 0;width: 100%;">
<?php  if(isset($_GET['tbl'])){ ?>
<div class='panel_btn'>
	<?php if(file_exists($TblDefTplPath."/menu_down.php")){include($TblDefTplPath."/menu_down.php");} ?>
</div>
<?php } ?>
</div>

	<script type="text/javascript">
		
		jQuery(document).ready(function() {
			var grid_height;	
			grid_height = $(window).height() - 24;
			$('#down_mn').css("top",grid_height+"px");
			$('#up_mn').css("top","0px");
		});
		
	</script>

</body>
</html>
