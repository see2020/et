<?php
	$RowSaveError = false;

	// выполняем функцию перед сохранением записи таблицы
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["BeforeSaveRow"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";

	$arr = array();
	
	foreach($TblSetting["sortfieldform"] as $key => $val){
		// если разрешено редактирование
		if($TblSetting[$key]["editable"] == 1 && $TblSetting[$key]["type"] != "support"){
			
			// сохранение поля с типом bool
			include(GetIncFile($arrSetting,"inc.tables.edit.form.save.varbool.php", $TblSetting["table"]["name"]));
			
			// сохранения поля для файла
			include(GetIncFile($arrSetting,"inc.tables.edit.form.save.file.php", $TblSetting["table"]["name"]));
			
			if(isset($_POST[$TblSetting[$key]["name"]])){
				if($TblSetting[$key]['type']=="date"){
					include(GetIncFile($arrSetting,"inc.tables.edit.form.save.".$TblSetting[$key]['type'].".php", $TblSetting["table"]["name"]));
				}
				elseif($TblSetting[$key]['type']=='file' || $TblSetting[$key]['type']=='image' || $TblSetting[$key]['type']=='varbool'){}
				else{
					include(GetIncFile($arrSetting,"inc.tables.edit.form.save.text.php", $TblSetting["table"]["name"]));
				}
				$arr[$TblSetting[$key]["name"]] = strtr($arr[$TblSetting[$key]["name"]],array("\""=>"&quot;", "'"=>"&apos;"));
			}
		}
		
		// заполнение не активных полей значениями по умолчанию
		// только для полей с типом hide и отключенных для редактирования
		include(GetIncFile($arrSetting,"inc.tables.edit.form.save.set.default.php", $TblSetting["table"]["name"]));
	}
	
	$tmp_IdChange = $IdChange;
	
	$text_log = "";
	
	$ArrFV = $sql->sql_ExpandArr($arr);
	if($IdChange == 0){
		if($sql->sql_insert($TblSetting["table"]['name'],$ArrFV['ListField'],$ArrFV['ListValue'])){
			$IdChange = $sql->sql_insertLastId;
			$text_log = "Новая запись";
		}else{
			$RowSaveError = true;
			}
	}
	else{
		if($sql->sql_update($TblSetting["table"]['name'],$ArrFV['FieldAndValue'],"`".$TblFieldPrimaryKey."`='".$_GET[$TblFieldPrimaryKey]."'")){
			$IdChange = $_GET[$TblFieldPrimaryKey];
			$text_log = "Редактирование записи";
		}else{
			$RowSaveError = true;
		}
	}		
	
	// пишем лог если включена функция авторизации
	if($arrSetting['Access']['UsePassword']){
		$ut->utLog($TblSetting["table"]["name"]." ".$text_log.": IdChange=".$tmp_IdChange.">>".$IdChange."; Save array = ".ParseArrForLog($arr).". _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
	}
	
	// выполняем функцию после сохранением записи таблицы
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["AfterSaveRow"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
	
	if($arrSetting['Other']['AutoReloadEditForm']){
		Redirect("?tbl=".$TblSetting["table"]["name"]."&".$TblFieldPrimaryKey."=".$IdChange."&pagenum=".$pg."&event=".$_GET['event']."",0);
	}
	else{
		echo Message("[<a href='"."?tbl=".$TblSetting["table"]["name"]."&".$TblFieldPrimaryKey."=".$IdChange."&pagenum=".$pg."&event=".$_GET['event'].""."'>Продолжить редактирование</a>] [<a href='"."?tbl=".$TblSetting["table"]["name"]."'>Вернуться к списку</a>]");
	}

	unset($_SESSION[D_NAME][$TblSetting["table"]['name']]["edit"]);

?>