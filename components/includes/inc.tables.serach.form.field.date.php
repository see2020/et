<?php

	echo "<td>".$nm_field.": "."</td>";
	echo "<td>".$action_select."</td>";
	
	echo "<td>";
	$ShowRow1 = (!isset($_GET[$TblSetting[$key]["name"]."_start"]) || $_GET[$TblSetting[$key]["name"]."_start"] == "")?$ut->utGetDate("Y-m-01 00:00:00"):$_GET[$TblSetting[$key]["name"]."_start"];
	$ShowRow2 = (!isset($_GET[$TblSetting[$key]["name"]."_end"]) || $_GET[$TblSetting[$key]["name"]."_end"] == "")?$ut->utGetDate("Y-m-d 23:59:59"):$_GET[$TblSetting[$key]["name"]."_end"];
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