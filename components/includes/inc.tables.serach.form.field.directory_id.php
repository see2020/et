<?php
	echo "<td>".$nm_field.": "."</td>";
	echo "<td>".$action_select."</td>";
	echo "<td>";
	$arr_spr_cfg = tblGetConfig($TblSetting[$key]['directory_table'],$arrSetting);
	echo spr_frmInputAddField(
	$TblSetting[$key]['name'],
	$_GET[$TblSetting[$key]['name']]??"",
	spr_get_element_nav($sql,$arr_spr_cfg, $_GET[$TblSetting[$key]['name']]??""),
		"select_".$TblSetting[$key]['name'],
//		$cmsPathRelative."/spr_window.php".
//			"?selected_val=".spr_JsUrlEscape($_GET[$TblSetting[$key]['name']]??"").
		ET_PATH_HTML . "/aj.php?af=spr.window".
			"&selected_val=".spr_JsUrlEscape($_GET[$TblSetting[$key]['name']]??"").
			"&field_type=".$TblSetting[$key]['type'].
			"&tbl_spr=".$TblSetting[$key]['directory_table'].
			"&irn=".$TblSetting[$key]['name'],
		'jsAddField',
		$width_field);
	echo "</td>";
?>