<?php

	echo "<td>".$nm_field.": "."</td>";

	if($TblSetting[$key]['directory_table'] != ""){
		$tmp_arr_td =spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key);
	}
	else{
		$tmp_arr_td = GetArrTypeData($TblSetting[$key]['type_data']);
	}

	echo "<td>".$action_select."</td>";
	echo "<td>";
	echo frmRadio(
		$TblSetting[$key]['name'], 
		$tmp_arr_td, 
		((!empty($_GET[$TblSetting[$key]['name']]))?$_GET[$TblSetting[$key]['name']]:""), 
		array());
	echo "</td>";

?>