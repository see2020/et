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
	echo frmSelect($TblSetting[$key]['name'], $tmp_arr_td, $_GET[$TblSetting[$key]['name']]??"", array("id"=>$TblSetting[$key]['name'],"style"=>"width: ".$width_field."px;",));
	echo "</td>";
?>