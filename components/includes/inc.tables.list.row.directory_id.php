<?php
	$arr_spr_cfg = tblGetConfig($TblSetting[$key]['directory_table'],$arrSetting);
	if($arr_spr_cfg["table"]["directory_UseFullPath"] != 0){
		$ShowRow = spr_get_element_nav_for_list($sql,$arr_spr_cfg, $query[$TblSetting[$key]['name']]);
	}
	else{
		$ShowRow = spr_get_element_for_list($sql, $arr_spr_cfg, $query[$TblSetting[$key]['name']]);
	}
	unset($arr_spr_cfg);
?>