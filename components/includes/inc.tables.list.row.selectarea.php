<?php
	// if(isset($tblsetting[$key]['name'])){
		if($TblSetting[$key]['directory_table'] != ""){
			$tmp_arr_td = spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key);
		}
		else{
			$tmp_arr_td = GetArrTypeData($TblSetting[$key]['type_data']);
		}
		if(isset($tmp_arr_td[$query[$TblSetting[$key]['name']]])){
			$ShowRow = $tmp_arr_td[$query[$TblSetting[$key]['name']]];
		}
		unset($tmp_arr_td);
	// }
?>