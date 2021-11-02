<?php
		if($TblSetting[$key]['directory_table'] != ""){
			$tmp_arr_td = spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key);
		}
		else{
			$tmp_arr_td = GetArrTypeData($TblSetting[$key]['type_data']);
		}
		$ShowRow = $tmp_arr_td[$query[$TblSetting[$key]['name']]];
		unset($tmp_arr_td);
?>