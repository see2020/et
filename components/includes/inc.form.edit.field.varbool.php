<?php
	echo "<span class='field_name'>".$nm_field.": </span>";
	echo frmInputCheckbox($TblSetting[$key]['name'], $qChange[$TblSetting[$key]['name']], array("id"=>$TblSetting[$key]['name'],),$readonly_var);
	echo "<br>";
?>