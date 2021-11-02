<?php
	echo "<span class='field_name'>".$nm_field.":</span><br>";
	echo frmTextarea($TblSetting[$key]['name'], $qChange[$TblSetting[$key]['name']], array("id"=>$TblSetting[$key]['name'],"style"=>"width: ".$width_field."px; height:".$height_field."px;",),$readonly_var);
?>