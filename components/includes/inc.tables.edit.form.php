<?php
	$qChange = array();
	
	// данные по умолчанию
	asort($TblSetting['sortfieldform']);
	foreach($TblSetting['sortfieldform'] as $key => $val){
		if(isset($TblSetting[$key]['name'])){
			$qChange[$TblSetting[$key]['name']] = ((isset($TblSetting[$key]['default']))?$TblSetting[$key]['default']:"");
		}
	}
	
	if($_GET[$TblFieldPrimaryKey]!=0){
		$result = $sql->sql_query("select * from `".$sql->prefix_db.$TblSetting["table"]['name']."` where `".$TblFieldPrimaryKey."`='".$_GET[$TblFieldPrimaryKey]."'");
		if($sql->sql_rows($result)){
			$qChange = $sql->sql_array($result);
		}
	}
	if(isset($_GET["f_copy"]) && $_GET["f_copy"] !=0 ){
		$result = $sql->sql_query("select * from `".$sql->prefix_db.$TblSetting["table"]['name']."` where `".$TblFieldPrimaryKey."`='".$_GET["f_copy"]."'");
		if($sql->sql_rows($result)){
			$qChange = $sql->sql_array($result);
			
			$qChange[$TblFieldPrimaryKey] = 0;
		}
	}

	if(!isset($_GET["print"])){
		// сохраняем данные о файле
		foreach($TblSetting['sortfieldform']as $key=>$val){
			if(isset($TblSetting[$key]["editable"]) && $TblSetting[$key]["editable"] == 1 && $TblSetting[$key]["type"] != "support"){
				
				if($TblSetting[$key]['type']=='file' || $TblSetting[$key]['type']=='image'){
					
					// при копировании сбрасываем значения полей типа file и image
					if(isset($_GET["f_copy"]) && $_GET["f_copy"] !=0 ){
						$qChange[$TblSetting[$key]["name"]] = '';
					}
					
					if($qChange[$TblSetting[$key]["name"]] != ''){
						$_SESSION[D_NAME][$TblSetting["table"]['name']]["edit"][$TblSetting[$key]["name"]] = $qChange[$TblSetting[$key]["name"]];
					}
				}
			}
		}
		
		// выполняем функцию перед загрузкой формы
		$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["BeforeLoadEditForm"];
		if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
		
		include(GetIncFile($arrSetting,"inc.form.edit.php", $TblSetting["table"]["name"]));
		
		// выполняем функцию после сохранением записи таблицы
		$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["AfterLoadEditForm"];
		if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
	}
	else{
		include(GetIncFile($arrSetting,"inc.form.print.php", $TblSetting["table"]["name"]));
	}
?>