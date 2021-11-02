<?php

	echo "<td>".$nm_field.": "."</td>";
	echo "<td>".$action_select."</td>";
	
	echo "<td>";
	//$ShowRow1 = (!isset($_GET[$TblSetting[$key]["name"]."_start"]) || $_GET[$TblSetting[$key]["name"]."_start"] == "")?"1970-01-01 00:00:00":$_GET[$TblSetting[$key]["name"]."_start"];
	$ShowRow1 = (!isset($_GET[$TblSetting[$key]["name"]."_start"]) || $_GET[$TblSetting[$key]["name"]."_start"] == "")?$ut->utGetDate("Y-m-01 00:00:00"):$_GET[$TblSetting[$key]["name"]."_start"];
	$ShowRow2 = (!isset($_GET[$TblSetting[$key]["name"]."_end"]) || $_GET[$TblSetting[$key]["name"]."_end"] == "")?$ut->utGetDate("Y-m-d 23:59:59"):$_GET[$TblSetting[$key]["name"]."_end"];
	// $ShowRow1 = $_GET[$TblSetting[$key]["name"]."_start"];
	// $ShowRow2 = $_GET[$TblSetting[$key]["name"]."_end"];
/*
	// echo "c&nbsp;&nbsp;&nbsp;".frmInputText($TblSetting[$key]['name']."_start", $ShowRow1, array("id"=>$TblSetting[$key]['name']."_start","style"=>"width: ".($width_field / 2)."px;",));
	// echo "<input type=\"image\" src=\"".$allSettings["arrSetting"]["Path"]["ico"]."/calendar.gif\" style='margin-bottom:-5px;' />";
	// echo "<br>по&nbsp;".frmInputText($TblSetting[$key]['name']."_end", $ShowRow2, array("id"=>$TblSetting[$key]['name']."_end","style"=>"width: ".($width_field / 2)."px;",));
	// echo "<input type=\"image\" src=\"".$allSettings["arrSetting"]["Path"]["ico"]."/calendar.gif\" style='margin-bottom:-5px;' />";
	// echo "</td>";
	
	// echo "c&nbsp;&nbsp;&nbsp;".frmInputDateAdd($allSettings, $TblSetting[$key]['name']."_start", strtotime($ShowRow1), array("id"=>$TblSetting[$key]['name']."_start","style"=>"width: ".($width_field / 2)."px;",));
	// echo "<br>по&nbsp;".frmInputDateAdd($allSettings, $TblSetting[$key]['name']."_end", strtotime($ShowRow2), array("id"=>$TblSetting[$key]['name']."_end","style"=>"width: ".($width_field / 2)."px;",));
	
	// echo frmInputDateAdd($allSettings, $TblSetting[$key]['name']."_start", $ShowRow1, array("id"=>$TblSetting[$key]['name']."_start",),$width_field);
	// echo frmInputDateAdd($allSettings, $TblSetting[$key]['name']."_end", $ShowRow2, array("id"=>$TblSetting[$key]['name']."_end",),$width_field);

	// echo "
		// <table style='text-align: left; width: 100%;' border='0' cellpadding='0' cellspacing='0'>
		// <tr><td width='30'>с</td><td>".frmInputText($TblSetting[$key]['name']."_start", $ShowRow1, array("id"=>$TblSetting[$key]['name']."_start",),($width_field-31))."</td></tr>
		// <tr><td width='30'>по</td><td>".frmInputText($TblSetting[$key]['name']."_end", $ShowRow2, array("id"=>$TblSetting[$key]['name']."_end",),($width_field-31))."</td></tr>
		// </table>
	// ";
*/
	
/*
	echo "
		<table style='text-align: left; width: 100%;' border='0' cellpadding='0' cellspacing='0'>
		<tr><td width='30'>с</td><td>".frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name']."_start","style"=>"width: ".($width_field-31)."px; ", "value"=>$ShowRow1, "id"=>$TblSetting[$key]['name']."_start", ))."</td></tr>
		<tr><td width='30'>по</td><td>".frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name']."_end","style"=>"width: ".($width_field-31)."px; ", "value"=>$ShowRow2, "id"=>$TblSetting[$key]['name']."_end", ))."</td></tr>
		</table>
	";
*/

	// echo "
		// <table style='text-align: left; width: 100%;' border='0' cellpadding='0' cellspacing='0'>
		// <tr><td width='30'>с</td><td>".
		// frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name']."_start", "id"=>$TblSetting[$key]['name']."_start", "value"=>$ShowRow1, "style"=>"width: ".($width_field-50)."px; ",  )).
		// "<input type=\"image\" src=\"".$allSettings["arrSetting"]["Path"]["ico"]."/calendar.gif\" style='margin-bottom:-5px;' />".
		// "</td></tr>
		// <tr><td width='30'>по</td><td>".
		// frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name']."_end", "id"=>$TblSetting[$key]['name']."_end", "value"=>$ShowRow2, "style"=>"width: ".($width_field-50)."px; ", )).
		// "<input type=\"image\" src=\"".$allSettings["arrSetting"]["Path"]["ico"]."/calendar.gif\" style='margin-bottom:-5px;' />".
		// "</td></tr>
		// </table>
	// ";
	echo "
		<table border='0' cellpadding='0' cellspacing='0'>
		<tr>
			<td width='20' style='text-align: right;'>с&nbsp;</td>
			<td width='150' style='text-align: left;'>".
			frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name']."_start", "id"=>$TblSetting[$key]['name']."_start", "value"=>$ShowRow1, "style"=>"width: 120px; ",  )).
			"<input type=\"image\" src=\"".$allSettings["arrSetting"]["Path"]["ico"]."/calendar.gif\" style='margin-bottom:-5px;' />".
			"</td>
			<td width='20' style='text-align: right;'>по&nbsp;</td>
			<td width='150' style='text-align: left;'>".
			frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name']."_end", "id"=>$TblSetting[$key]['name']."_end", "value"=>$ShowRow2, "style"=>"width: 120px; ", )).
			"<input type=\"image\" src=\"".$allSettings["arrSetting"]["Path"]["ico"]."/calendar.gif\" style='margin-bottom:-5px;' />".
			"</td>
		</tr>
		</table>
	";
?>