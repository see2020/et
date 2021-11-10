<?php
	echo "<span class='field_name'>".$nm_field.":</span><br>";

	if(isset($TblSetting[$key]['maxlength']) && $TblSetting[$key]['maxlength'] != "" && $TblSetting[$key]['maxlength'] != 0){
		$TblSetting[$key]['maxlength'] = (int)$TblSetting[$key]['maxlength'];
		echo frmInputPassword($TblSetting[$key]['name'], $qChange[$TblSetting[$key]['name']], array("id"=>$TblSetting[$key]['name'],"maxlength"=>$TblSetting[$key]['maxlength']),$width_field,$readonly_var);
	}
	else{
		echo frmInputPassword($TblSetting[$key]['name'], $qChange[$TblSetting[$key]['name']], array("id"=>$TblSetting[$key]['name'],),$width_field,$readonly_var);
	}
?>