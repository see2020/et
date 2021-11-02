<table style="text-align: left; width: 100%;" border="0" cellpadding="3" cellspacing="1"><tbody>
	<?php
		$tmp_list_type = array("date","directory_name","directory_id","selectarea","radiobutton","number","varbool",);

		//$stype_arr = TypeSearch();
		asort($TblSetting['sortfieldsearch']);
		foreach($TblSetting["sortfieldsearch"] as $key=>$val){
			if($TblSetting[$key]['type'] != 'support' && $TblSetting[$key]["for_search"] == "1"){
				
				// набор действий для разных типов полей
				$stype_arr = TypeSearch();
				//unset($stype_arr[10],$stype_arr[11],$stype_arr[12],$stype_arr[13],$stype_arr[14],$stype_arr[15],$stype_arr[16],$stype_arr[17]);
				if($TblSetting[$key]['type'] == "text" 
				|| $TblSetting[$key]['type'] == "textarea"
				|| $TblSetting[$key]['type'] == "password"
				|| $TblSetting[$key]['type'] == "directory_name"
				|| $TblSetting[$key]['type'] == ""
				|| $TblSetting[$key]['type'] == ""
				){
					unset($stype_arr[11],$stype_arr[13],$stype_arr[14],$stype_arr[15],$stype_arr[16],$stype_arr[17]);
				}
				if($TblSetting[$key]['type'] == "selectarea" 
				|| $TblSetting[$key]['type'] == "radiobutton"
				){
					unset($stype_arr[12],$stype_arr[13]);
				}
				if($TblSetting[$key]['type'] == "number"){
					unset($stype_arr[12]);
				}
				if($TblSetting[$key]['type'] == "date"){
					unset($stype_arr[11],$stype_arr[12],$stype_arr[13],$stype_arr[14],$stype_arr[15],$stype_arr[16],$stype_arr[17]);
				}
				if($TblSetting[$key]['type'] == "varbool"
				|| $TblSetting[$key]['type'] == "directory_id"
				){
					unset($stype_arr[12],$stype_arr[13],$stype_arr[14],$stype_arr[15],$stype_arr[16],$stype_arr[17]);
				}
				if($TblSetting[$key]['type'] == "link"
				|| $TblSetting[$key]['type'] == "list_string"
				|| $TblSetting[$key]['type'] == "list_link"
				){
					unset($stype_arr[10],$stype_arr[11],$stype_arr[13],$stype_arr[14],$stype_arr[15],$stype_arr[16],$stype_arr[17]);
				}
				if($TblSetting[$key]['type'] == "file"
				|| $TblSetting[$key]['type'] == "image"
				){
					unset($stype_arr[10],$stype_arr[11],$stype_arr[12],$stype_arr[13],$stype_arr[14],$stype_arr[15],$stype_arr[16],$stype_arr[17]);
				}
				
				$nm_field = frmGetName($TblSetting,$key);
				$action_select = frmSelect(
					$TblSetting[$key]['name']."_tp", 
					$stype_arr, 
					(isset($_GET[$TblSetting[$key]['name']."_tp"]))?$_GET[$TblSetting[$key]['name']."_tp"]:"", 
					//$_GET[$TblSetting[$key]['name']."_tp"]??"", 
					array("id"=>$TblSetting[$key]['name']."_tp","style"=>"width: 100px;")
				);
				
				if($TblSetting[$key]["type"] == "hide"){
					include(GetIncFile($arrSetting,"inc.tables.serach.form.field.hide.php", $TblSetting["table"]['name']));
				}
				elseif(in_array($TblSetting[$key]["type"],$tmp_list_type)){
					echo "<tr bgcolor='#e7f0f8'>";
					echo "<td>".strtoupper($TblSetting["table"]["WhereType"])."&nbsp;"."</td>";
					include(GetIncFile($arrSetting,"inc.tables.serach.form.field.".$TblSetting[$key]["type"].".php", $TblSetting["table"]["name"]));
					echo "</tr>";
				}
				else{
					echo "<tr bgcolor='#e7f0f8'>";
					echo "<td>".strtoupper($TblSetting["table"]["WhereType"])."&nbsp;"."</td>";
					include(GetIncFile($arrSetting,"inc.tables.serach.form.field.text.php", $TblSetting["table"]["name"]));
					echo "</tr>";
				}
			}
		}
		unset($tmp_list_type);
	?>
</tbody></table>