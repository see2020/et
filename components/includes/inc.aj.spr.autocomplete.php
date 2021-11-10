<?php
/**
 * inc.aj.spr.autocomplete.php.php - автопоиск из таблиц с типом справочник при вводе в формах
 */
	include($arrSetting["Path"]["class"]."/a.charset.php");
	$url = new class_url();
	$qSearch	 = $url->uJsUrlDecode($_GET['query']);
	$qSearch	 = charset_x_win($_GET['query']);
	$arr_spr_cfg = tblGetConfig($_GET['tbl_spr'],$arrSetting);
	
	// список полей таблицы
	if(!$arrFields = $sql->sql_GetFieldFromTable($sql->prefix_db.$_GET["tbl_spr"])){
		$ut->utLog(__FILE__ . " - Ошибка получения полей таблицы ".$sql->prefix_db.$_GET["tbl_spr"]);
	}
	$json_var = '{"query": "'.$qSearch.'","suggestions": [';

	$elementNum = 0;
	
	$wdne = "";
	if($arr_spr_cfg["table"]["directory_name_edit2"] != ""){$wdne.= " OR `".$arr_spr_cfg["table"]["directory_name_edit2"]."` LIKE '%".$qSearch."%'";}
	if($arr_spr_cfg["table"]["directory_name_edit3"] != ""){$wdne.= " OR `".$arr_spr_cfg["table"]["directory_name_edit3"]."` LIKE '%".$qSearch."%'";}
	if($arr_spr_cfg["table"]["directory_name_edit4"] != ""){$wdne.= " OR `".$arr_spr_cfg["table"]["directory_name_edit4"]."` LIKE '%".$qSearch."%'";}
	if($arr_spr_cfg["table"]["directory_name_edit5"] != ""){$wdne.= " OR `".$arr_spr_cfg["table"]["directory_name_edit5"]."` LIKE '%".$qSearch."%'";}
	
	if($wdne = ""){
		$wdne = "AND `".$arr_spr_cfg["table"]["directory_name_edit"]."` LIKE '%".$qSearch."%'";	
	}
	else{
		$wdne = "AND (`".$arr_spr_cfg["table"]["directory_name_edit"]."` LIKE '%".$qSearch."%' ".$wdne.")";	
	}
	
	$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$_GET["tbl_spr"]." WHERE ".$arr_spr_cfg["table"]["StatusField"]."='1' ".$wdne." ORDER BY ".$arr_spr_cfg["table"]["directory_name_edit"]." ASC");
	if($sql->sql_rows($result)){
		while($query = $sql->sql_array($result)){
			if($elementNum != 0){$json_var.= ',';}
		
			if($_GET['field_type'] == "directory_id"){
				if($arr_spr_cfg["table"]["directory_UseFullPath"] != 0){
					$query[$arr_spr_cfg["table"]["directory_name_edit"]] = spr_get_element_nav_for_edit($sql,$arr_spr_cfg, $query[$arr_spr_cfg["table"]["PrimaryKey"]]);
				}
				else{
					$query[$arr_spr_cfg["table"]["directory_name_edit"]] = spr_get_element_for_edit($sql,$arr_spr_cfg, $query[$arr_spr_cfg["table"]["PrimaryKey"]]);
				}
			}
			$query[$arr_spr_cfg["table"]["directory_name_edit"]] = str_replace("&quot;", '\"', $query[$arr_spr_cfg["table"]["directory_name_edit"]]);

			$json_tmp = "";
			foreach($arrFields as $key => $val){
				$json_tmp.= ', "'.$val.'": "'.$query[$val].'"';
			}

			$dne = "";
			if($arr_spr_cfg["table"]["directory_name_edit2"] != ""){$dne.= "; ".str_replace("&quot;", '\"', $query[$arr_spr_cfg["table"]["directory_name_edit2"]]);}
			if($arr_spr_cfg["table"]["directory_name_edit3"] != ""){$dne.= "; ".str_replace("&quot;", '\"', $query[$arr_spr_cfg["table"]["directory_name_edit3"]]);}
			if($arr_spr_cfg["table"]["directory_name_edit4"] != ""){$dne.= "; ".str_replace("&quot;", '\"', $query[$arr_spr_cfg["table"]["directory_name_edit4"]]);}
			if($arr_spr_cfg["table"]["directory_name_edit5"] != ""){$dne.= "; ".str_replace("&quot;", '\"', $query[$arr_spr_cfg["table"]["directory_name_edit5"]]);}
			
			$json_var.= '{ "value": "'.$query[$arr_spr_cfg["table"]["directory_name_edit"]].$dne.'", "primarykey": "'.$query[$arr_spr_cfg["table"]["PrimaryKey"]].'" '.$json_tmp.'}';
			
			$elementNum = 1;
		}
	}
	$json_var.= "]}";
	echo $json_var;
?>