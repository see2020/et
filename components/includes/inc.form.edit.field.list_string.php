<?php
	echo "<span class='field_name'>".$nm_field.":ls</span><br>";
	echo frmInputListString($TblSetting[$key]['name'], $qChange[$TblSetting[$key]['name']], $width_field, $height_field, $readonly_var);
?>