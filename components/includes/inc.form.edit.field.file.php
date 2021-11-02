<?php
	echo "<span class='field_name'>".$nm_field.":</span><br>";
	echo frmInputFile($TblSetting[$key]['name'], $qChange[$TblSetting[$key]['name']], array("id"=>$TblSetting[$key]['name'],),$width_field,$readonly_var);
?>