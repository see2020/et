<?php
	$width_field_def	 = (isset($TblSetting["table"]["FormFieldWidth"]))?$TblSetting["table"]["FormFieldWidth"]:500;
	$height_field_def	 = (isset($TblSetting["table"]["FormFieldHeight"]))?$TblSetting["table"]["FormFieldHeight"]:100;

	// сортировка полей 
	asort($TblSetting['sortfieldform']);

	echo "<table style='text-align: left; width: 100%;' border='0' cellpadding='5' cellspacing='0'><tbody>";

	// верхн€€ часть формы
	echo "<tr>";
		echo "<td valign='top' align='left' style='min-width: 30%;'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]["editable"] == 1 && $TblSetting[$key]["type"] != "support" && $TblSetting[$key]["FormColumn"] == 3){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]["name"]));
				}
			}
		echo "</td>";
		echo "<td valign='top' align='left'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]["editable"] == 1 && $TblSetting[$key]["type"] != "support" && $TblSetting[$key]["FormColumn"] == 4){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]["name"]));
				}
			}
		echo "</td>";
		echo "<td valign='top' align='left'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]['editable'] == 1 && $TblSetting[$key]['type'] != 'support' && $TblSetting[$key]['FormColumn'] == 5){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]['name']));
				}
			}
		echo "</td>";
	echo "</tr>";
	
	// центр
	echo "<tr>";
		echo "<td valign='top' align='left' style='min-width: 30%;'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]['editable'] == 1 && $TblSetting[$key]['type'] != 'support' && $TblSetting[$key]['FormColumn'] == 0){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]['name']));
				}
			}
		echo "</td>";
		echo "<td valign='top' align='left'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]['editable'] == 1 && $TblSetting[$key]['type'] != 'support' && $TblSetting[$key]['FormColumn'] == 1){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]['name']));
				}
			}
		echo "</td>";
		echo "<td valign='top' align='left'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]['editable'] == 1 && $TblSetting[$key]['type'] != 'support' && $TblSetting[$key]['FormColumn'] == 2){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]['name']));
				}
			}
		echo "</td>";
	echo "</tr>";
	
	// нижн€€ часть формы
	echo "<tr>";
		echo "<td valign='top' align='left' style='min-width: 30%;'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]['editable'] == 1 && $TblSetting[$key]['type'] != 'support' && $TblSetting[$key]['FormColumn'] == 6){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]['name']));
				}
			}
		echo "</td>";
		echo "<td valign='top' align='left'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]['editable'] == 1 && $TblSetting[$key]['type'] != 'support' && $TblSetting[$key]['FormColumn'] == 7){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]['name']));
				}
			}
		echo "</td>";
		echo "<td valign='top' align='left'>";
			foreach($TblSetting["sortfieldform"] as $key=>$val){
				if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]['editable'] == 1 && $TblSetting[$key]['type'] != 'support' && $TblSetting[$key]['FormColumn'] == 8){
					include(GetIncFile($arrSetting,"inc.form.edit.fields.row.php", $TblSetting["table"]['name']));
				}
			}
		echo "</td>";
	echo "</tr>";
	
	echo "</tbody></table>";
	
	echo "<br>";
	echo $EditActionButton;
	echo "<br>";

?>