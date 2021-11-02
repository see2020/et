<?php

	echo "<span class='field_name'>".$nm_field.": </span>";
	if($TblSetting[$key]['directory_table'] != ""){
		$tmp_arr_td = spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key);
	}
	else{
		$tmp_arr_td = GetArrTypeData($TblSetting[$key]['type_data']);
	}
	echo frmRadio($TblSetting[$key]['name'], $tmp_arr_td, $qChange[$TblSetting[$key]['name']], array());
	unset($tmp_arr_td);
	echo "<br>";

?>