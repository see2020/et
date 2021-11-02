<?php
	echo "<td>".$nm_field.": "."</td>";
	echo "<td>".$action_select."</td>";
	echo "<td>";
	//echo frmInputAddField($TblSetting[$key]['name'],$_GET[$TblSetting[$key]['name']],"select_".$TblSetting[$key]['name'],$cmsPathRelative."/spr_window_".$TblSetting["table"]['name']."_".$TblSetting[$key]['name'].".php?selected_val=".spr_JsUrlEscape($_GET[$TblSetting[$key]['name']])."&field_type=".$TblSetting[$key]['type'],'jsAddField',$width_field);
	echo frmInputAddField(
		$TblSetting[$key]['name'],
		$_GET[$TblSetting[$key]['name']]??"",
		"select_".$TblSetting[$key]['name'],
//$cmsPathRelative."/spr_window.php".
//"?selected_val=".spr_JsUrlEscape($_GET[$TblSetting[$key]['name']]??"").
		$cmsPathRelative."/aj.php?af=spr.window".
			"&selected_val=".spr_JsUrlEscape($_GET[$TblSetting[$key]['name']]??"").
			"&field_type=".$TblSetting[$key]['type'].
			"&tbl_spr=".$TblSetting[$key]['directory_table'].
			"&irn=".$TblSetting[$key]['name'],
		'jsAddField',
		$width_field
	);
	echo "</td>";
?>