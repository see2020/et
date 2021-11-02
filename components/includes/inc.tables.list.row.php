<?php
 // обработка одной записи в списке
 
	$TblStatusColored = "";
	// если стоит показывать все записи
	if($TblSetting["table"]['AllRows']=="1" && $TblSetting["table"]['StatusField']!= ""){
		if($query[$TblSetting["table"]['StatusField']] != 1){
			$TblStatusColored = "color: grey;";
		}
	}

	// если поле не заполнено, то выводим данные по умолчанию
	if(isset($query[$TblSetting[$key]['name']])){
		if($query[$TblSetting[$key]['name']]==""){ $query[$TblSetting[$key]['name']] = $TblSetting[$key]['default']; }
	}

	$ShowRow = "";

	if(in_array($TblSetting[$key]['type'],$allSettings["includes"]["list_fields"])){
		include(GetIncFile($arrSetting,"inc.tables.list.row.".$TblSetting[$key]['type'].".php", $TblSetting["table"]['name']));
	}
	else{
		include(GetIncFile($arrSetting,"inc.tables.list.row.text.php", $TblSetting["table"]['name']));
	}
	
	if($TblSetting[$key]['type'] != "link"){
		if($TblSetting[$key]['link'] != ""){
			$tmpLnk = SetTplLnk($tmpArrSetField, $query, $TblSetting[$key]['link']);
			$ShowRow = fLnk($ShowRow, $tmpLnk, array("target" => (($TblSetting[$key]['link_newwindow']=="1")?"_blank":""),"title" =>$query[$TblSetting[$key]['name']],"style" => $TblStatusColored));
		}
	}

	// подсказка дл€ записи
	$TitleField = "";
	if($TblSetting[$key]["type"] == "text" || $TblSetting[$key]["type"] == "textarea"){
		$TitleField = "title='".$query[$TblSetting[$key]['name']]."'";
	}
	
	$tpl_name_field	 = "field";
	$tpl_path		 = $TblDefTplPath;
	//$allSettings["TblPath"]["theme"]
	if($TblSetting[$key]['theme'] != ""){
		$tpl_name_field	 = trim($TblSetting[$key]['theme']);
		$tpl_path		 = $allSettings["TblPath"]["theme"];
	}
	
	// если задана функци€ обработки записи
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting[$key]["func"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}
	$func_file = "";
	
	
	// если есть подключенные пол€
	for($i = 1; $i <= 5; $i++){
		$SaveKey	 = $key;
		$SaveShowRow = $ShowRow;
		if($TblSetting[$key]["AttachField".$i] != ""){
			$key		 = $TblSetting[$key]["AttachField".$i];
			$keyAttach	 = $key;
			if($query[$TblSetting[$key]['name']] != ""){
					if(in_array($TblSetting[$key]['type'],$allSettings["includes"]["list_fields"])){
					include(GetIncFile($arrSetting,"inc.tables.list.row.".$TblSetting[$key]['type'].".php", $TblSetting["table"]['name']));
				}
				else{
					include(GetIncFile($arrSetting,"inc.tables.list.row.text.php", $TblSetting["table"]['name']));
				}
				
				$key	 = $SaveKey;
				if(!$TblSetting[$key]["AttachShowColumnName"]){
					$ShowRow = $SaveShowRow.$TblSetting[$key]["AttachSeparator"].$ShowRow;
				}
				else{
					$AttachName = ($TblSetting[$keyAttach]['column_descr'] == "")?(($TblSetting[$keyAttach]['description'] == "")?$TblSetting[$keyAttach]['name'].": ":$TblSetting[$keyAttach]['description']." "):$TblSetting[$keyAttach]['column_descr']." ";
					$ShowRow = $SaveShowRow.$TblSetting[$key]["AttachSeparator"].$AttachName.$ShowRow;
				}
			}
		}
	}
	
	// название столбца в €чейке
	$col_name = "";
	if(!empty($TblSetting[$key]['AttachShowColumnName']) && $TblSetting[$key]['AttachShowColumnName'] == 1){
		$col_name = ($TblSetting[$key]['column_descr']=="")?(($TblSetting[$key]['description']=="")?$TblSetting[$key]['name']:$TblSetting[$key]['description']):$TblSetting[$key]['column_descr'];
		$col_name.= " ";
	}

	
	$show_field.= GetTpl($tpl_name_field, array("col_name" => $col_name,"value" => $ShowRow, "attribute" => "style='".$TblStatusColored."' ".$TitleField."", ), $tpl_path);
?>