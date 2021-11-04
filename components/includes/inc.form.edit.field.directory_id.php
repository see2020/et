<?php
	$arr_spr_cfg = tblGetConfig($TblSetting[$key]['directory_table'],$arrSetting);
	$tex_value_field = "";
	if($arr_spr_cfg["table"]["directory_UseFullPath"]){
		//полное имя
		$tex_value_field = spr_get_element_nav_for_edit($sql,$arr_spr_cfg, $qChange[$TblSetting[$key]['name']]);
	}
	else{
		// короткое имя 
		$tex_value_field = spr_get_element_for_edit($sql, $arr_spr_cfg, $qChange[$TblSetting[$key]['name']]);
	}
	echo "<span class='field_name'>".$nm_field.":</span><br>";
	echo spr_frmInputAddField(
		$TblSetting[$key]['name'],
		$qChange[$TblSetting[$key]['name']],
		$tex_value_field,
		"select_".$TblSetting[$key]['name'],
		ET_PATH_HTML . "/aj.php?af=spr.window".
			"&selected_val=".spr_JsUrlEscape($qChange[$TblSetting[$key]['name']]).
			"&field_type=".$TblSetting[$key]['type'].
			"&tbl_spr=".$TblSetting[$key]['directory_table'].
			"&irn=".$TblSetting[$key]['name'],
		'jsAddField',
		$width_field,
		$readonly_var);		
	unset($arr_spr_cfg,$tex_value_field);
?>