<?php
	echo "<span class='field_name'>".$nm_field.":</span><br>";
	echo frmInputAddField(
		$TblSetting[$key]['name'],
		$qChange[$TblSetting[$key]['name']],
		"select_".$TblSetting[$key]['name'],
		$cmsPathRelative."/aj.php?af=spr.window"
			."&selected_val=".spr_JsUrlEscape($qChange[$TblSetting[$key]['name']])
			."&field_type=".$TblSetting[$key]['type']
			."&tbl_spr=".$TblSetting[$key]['directory_table']
			."&irn=".$TblSetting[$key]['name']
			.(($TblSetting[$key]['multiselect'])?"&ms=1&mss=".$TblSetting[$key]['multiselect_sep']:"")
			,
		'jsAddField',
		$width_field,
		$readonly_var);
?>