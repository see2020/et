<?php
	echo "<span class='field_name'>".$nm_field.":</span><br>";
	echo frmInputImage($TblSetting[$key]['name'], $qChange[$TblSetting[$key]['name']], array("id"=>$TblSetting[$key]['name'],),$width_field,$height_field,$readonly_var);
?>