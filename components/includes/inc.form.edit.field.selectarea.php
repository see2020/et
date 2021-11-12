<?php

	echo "<span class='field_name'>".$nm_field.":</span><br>";
	
	if($TblSetting[$key]['directory_table'] != ""){
		$tmp_arr_td = spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key,'edit');
	}
	else{
		$tmp_arr_td = GetArrTypeData($TblSetting[$key]['type_data']);
	}
	
	echo frmSelect($TblSetting[$key]['name'], $tmp_arr_td, $qChange[$TblSetting[$key]['name']], array("id"=>$TblSetting[$key]['name'],"style"=>"width: ".$width_field."px;",),$readonly_var);
	unset($tmp_arr_td);

?>