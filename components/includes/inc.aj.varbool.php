<?php
	//inc.aj.varbool.php
	$status_field = trim($_GET['field']);
	//if(usr_Access("edit")){}else{echo Message("Недостаточно прав на изменение этого раздела", "error");exit;}
	
	// выполняем функцию перед сменой статуса записи таблицы
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["BeforeChangeRow"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
	
	$result = $sql->sql_query("select * from ".$sql->prefix_db.$TblSetting["table"]['name']." where `".$TblFieldPrimaryKey."`='".$_GET[$TblFieldPrimaryKey]."'");
	if($sql->sql_err){}
	else{
		if($sql->sql_rows($result)){$query = $sql->sql_array($result);}
		if($query[$status_field]=='1'){$status_new = '0';}else{$status_new = '1';}
		if(!$sql->sql_update($TblSetting["table"]['name'],"`".$status_field."`='".$status_new."'","`".$TblFieldPrimaryKey."`='".$_GET[$TblFieldPrimaryKey]."'")){
			}else{
			// пишем лог если включена функция авторизации
			if($arrSetting['Access']['UsePassword']){
				$ut->utLog("AX Изменение статуса записи: ".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey].";  _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
			}
			// выполняем функцию после смены статуса записи таблицы
			$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["AfterChangeRow"];
			if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
			
			$tmpName = $status_new;
			if($TblSetting[$status_field]['link_image']=="1"){
				if($status_new == 1 && $TblSetting[$status_field]['image'] != ""){
					$tmpName = "<img src=".$arrSetting['Path']['ico']."/".$TblSetting[$status_field]['image'].">";
				}
				if($status_new == 0 && $TblSetting[$status_field]['image_other']!=""){
					$tmpName = "<img src=".$arrSetting['Path']['ico']."/".$TblSetting[$status_field]['image_other'].">";
				}
			}
			echo $tmpName;
		}
	}
?>