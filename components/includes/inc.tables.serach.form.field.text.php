<?php
	echo "<td>".$nm_field.": "."</td>";
	echo "<td>".$action_select."</td>";
	echo "<td>";
	echo frmInputText(
		$TblSetting[$key]['name'], 
		(isset($_GET[$TblSetting[$key]['name']]))?$_GET[$TblSetting[$key]['name']]:"", 
		array("id"=>$TblSetting[$key]['name'],),
		$width_field
	);
	echo "</td>";

?>