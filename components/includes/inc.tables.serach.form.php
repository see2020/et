<?php
	include(GetIncFile($arrSetting,"inc.tables.serach.form.js.php", $TblSetting["table"]['name']));
	$width_field = 400;
?>

<style>
	.fe-side{
		float:left; margin:0;padding:0;
	}
	.fe-side.left{
		width: 700px;
	}
	.fe-side.right{
		width: 170px;
	}
	.fe-block{
		margin-left: 7px;margin-top: 7px;margin-right: 7px;border: 1px solid #b7d2ec;background-color: #d9e8f4;		
	}
	.fe-side.left .fe-block{
		width: 660px;
	}
	
</style>

<div class="fe-side left">
	<form method='post' action='?tbl=<?php echo $_GET["tbl"]; ?>&event=serach'>
		<div id='inputArea' class="fe-block">
			<?php include(GetIncFile($arrSetting,"inc.tables.serach.form.fields.php", $TblSetting["table"]['name'])); ?>
		</div>			

		<div id='inputArea' class="fe-block">
			Поиск по всем полям:<br><?php echo frmInputText("allfield", $_GET["allfield"]??"", array("id"=>"allfield",),$width_field); ?>
		</div>
		<div id='inputArea' class="fe-block">
			<input type='submit' name='Search' id='Search' value='Найти' title=''>
			<input type='submit' name='NoSearch' id='NoSearch' value='Отмена' title=''>
		</div>
	</form>
</div>

<div class="fe-side right">
	<div id='inputArea' class="fe-block">
	дополнительные параметры 1
	</div>
	<div id='inputArea' class="fe-block" style="height: 50px;overflow-y: scroll;">
	дополнительные параметры 2<br>
	дополнительные параметры 2<br>
	дополнительные параметры 2<br>
	дополнительные параметры 2<br>
	дополнительные параметры 2<br>
	дополнительные параметры 2<br>
	дополнительные параметры 2<br>
	</div>
</div>

<div style='clear: both;padding: 0; margin: 0;'></div>