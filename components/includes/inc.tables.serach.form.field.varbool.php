<?php
	echo "<td>".$nm_field.": "."</td>";
	echo "<td>".$action_select."</td>";
	echo "<td>";
	echo frmInputCheckbox($TblSetting[$key]['name'], $_GET[$TblSetting[$key]['name']]??"", array("id"=>$TblSetting[$key]['name'],));
	echo "</td>";
?>