<?php
	
	$nm_field = frmGetName($TblSetting,$key);
	
	if($TblSetting[$key]['required']){
		$nm_field .= " <span style='color:red;font-size:10px;'>(обязательно к заполнению)</span>";
	}
	
	//$readonly_var = ($TblSetting[$key]['readonly'] == 1)?true:false;
	
	// если запись новая, то разрешаем редактировать поля только для чтения
	if($_GET[$TblFieldPrimaryKey] !=0 ){
		$readonly_var = ($TblSetting[$key]['readonly'] == 1)?true:false;
	}
	
	// если запись чем то заполнена
	if($TblSetting[$key]['type'] == "number"){
		if($qChange[$TblSetting[$key]['name']] != 0){
			$readonly_var = ($TblSetting[$key]['readonly'] == 1)?true:false;
		}
	}
	else{
		if($qChange[$TblSetting[$key]['name']] != ""){
			$readonly_var = ($TblSetting[$key]['readonly'] == 1)?true:false;
		}
	}
	
	// если это копия записи, то разрешаем редактирование всех полей с пометкой "только для чтения"
	// tables.php?tbl=ad_users&pagenum=1&id=0&f_copy=1723&event=edit
	if($TblSetting["table"]["ReadonlyOffForCopyRow"]){
		if(isset($_GET["f_copy"]) && $_GET[$TblFieldPrimaryKey] == 0){
			$readonly_var = false;
		}
	}
	if(usr_Access("root")){
		$readonly_var = false;
	}
		
	$width_field	 = (isset($TblSetting[$key]["FormFieldWidth"]))?$TblSetting[$key]["FormFieldWidth"]:$width_field_def;
	$height_field	 = (isset($TblSetting[$key]["FormFieldHeight"]))?$TblSetting[$key]["FormFieldHeight"]:$height_field_def;
	
	/* если спавочник */
	// для перехода не предыдущий уровень 
	if(isset($arrTableSpr["id_root"]) && $TblSetting[$key]['name'] == $arrTableSpr["id_root"]){
		if($TblSetting["table"]['is_directory'] == "1"){
			echo "<span class='field_name'>".$nm_field.":</span><br>";
			echo spr_selectfield($sql, $arrSetting, $TblSetting, $arrTableSpr, $key, $qChange, $TblSetting["table"]['name'], $TblFieldPrimaryKey,0,$width_field);	
		}
		else{
			include(GetIncFile($arrSetting,"inc.form.edit.field.text.php", $TblSetting["table"]["name"]));
		}
	}
	elseif(in_array($TblSetting[$key]["type"],$allSettings["includes"]["form_fields"])){
		include(GetIncFile($arrSetting,"inc.form.edit.field.".$TblSetting[$key]["type"].".php", $TblSetting["table"]["name"]));
	}
	else{
		include(GetIncFile($arrSetting,"inc.form.edit.field.text.php", $TblSetting["table"]["name"]));
	}
	
	$readonly_var = false;
	
?>