<?php
	$lnk_order_by = "";
	
	// для сортироваки по столбцам ///////////////////////////////////////
	if($TblSetting["table"]['is_directory'] != "1"){
		$tmpColAsc	 = "<img src='".$arrSetting['Path']['ico']."/arrow_asc.gif' alt='' title='' />";
		$tmpColDesc	 = "<img src='".$arrSetting['Path']['ico']."/arrow_desc.gif' alt='' title='' />";
		$tmpColAscImg	 = $arrSetting['Path']['ico']."/arrow_asc.gif";
		$tmpColDescImg	 = $arrSetting['Path']['ico']."/arrow_desc.gif";
		$direction_lnk_name	 = "<img src='".$arrSetting['Path']['ico']."/arrow_ad.png' alt='' title='' />";
		$tblOrder_get		 = "";
		$or_direction		 = "ASC";
		if(isset($_GET["direction"])){
			if($_GET["direction"] == "desc" || $_GET["direction"] == "DESC"){$or_direction = "ASC";}
			if($_GET["direction"] == "asc" || $_GET["direction"] == "ASC"){$or_direction = "DESC";}
		}
		if(isset($_GET["or"])){
			$tblOrder_get	 = trim($_GET["or"])." ".trim($_GET["direction"]);
			$lnk_order_by = "&or=".$_GET["or"]."&direction=".$_GET["direction"];
		}
	}
	// конец для сортироваки по столбцам ///////////////////////////////////////
	$show_table = "";
	// выполняем функцию перед загрузкой списка таблицы
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["BeforeLoadingTable"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
	
	// упорядочиваем вывод колонок согласно заданным значениям
	// сортировка по значениям
	asort($TblSetting["sortfield"]);
	
	// шапка таблицы
	$show_field = "";
	
	foreach($TblSetting["sortfield"] as $key => $val){
		
		if(isset($TblSetting[$key])){
			if(is_array($TblSetting[$key])){
			
				if($TblSetting[$key]["visible"] == 1 && $TblSetting[$key]['type'] != "hide"){
					
					$tpl_name_field	 = "field_head";
					$tpl_path		 = $TblDefTplPath;
					
					$tmp_att = ($TblSetting[$key]['width']!=0 && $TblSetting[$key]['width']!="")?"width='".$TblSetting[$key]['width']."'":"";
					//если имя колонки задано отдельно
					$tmp_val = ($TblSetting[$key]['column_descr']=="")?(($TblSetting[$key]['description']=="")?$TblSetting[$key]['name']:$TblSetting[$key]['description']):$TblSetting[$key]['column_descr'];
					
					// для сортироваки по столбцам ///////////////////////////////////////
					$tmp_diretion = "";
					if($TblSetting[$key]['use_order'] == "1" && $TblSetting["table"]['is_directory'] != "1" && $TblSetting[$key]['type'] != "support"){
						$tmp_diretion = fLnk(
							((isset($_GET["or"]) && $_GET["or"]==$TblSetting[$key]['name'])?(
								($_GET["direction"]=="asc" || $_GET["direction"] == "ASC")?$tmpColAsc:$tmpColDesc
							):$direction_lnk_name), 
							"?tbl=".$TblSetting['table']["name"]."&or=".$TblSetting[$key]['name']."&direction=".$or_direction
							.((isset($_GET["pagenum"]))?"&pagenum=".$_GET["pagenum"]:"")
							.$PageLinkSrch
						);
						$tmp_val = "<div style='min-width: 80px;margin: 0;padding: 0;'>".$tmp_diretion."&nbsp;".$tmp_val."</div>";
					}
					// конец для сортироваки по столбцам ///////////////////////////////////////
					$show_field.= GetTpl($tpl_name_field, array(
					"value" => $tmp_val, 
					"attribute" => $tmp_att, ), 
					$tpl_path);
					unset($tmp_val,$tmp_val1,$tmp_att,$tmp_diretion);
				}
			}
		}
		
	}
	
	$show_row_head = GetTpl("row_head", array("field_head" => $show_field), $TblDefTplPath);

	// если установлена настройка это справочник 
	if($TblSetting["table"]['is_directory'] == "1"){
		if(isset($_GET['srch'])){
			// если поиск по стправочнику, отключаем вывод по категриям
			$tblWhere = " WHERE ".(($TblSetting["table"]["StatusField"]!="" && $TblSetting["table"]['AllRows']=="0" )?"a.`".$TblSetting["table"]['StatusField']."`='1'":"a.`".$TblFieldPrimaryKey."`<>'0'");
		}
		else{
			$tblWhere = " WHERE ".(($TblSetting["table"]["StatusField"]!="" && $TblSetting["table"]['AllRows']=="0" )?"a.`".$TblSetting["table"]['StatusField']."`='1'":"a.`".$TblFieldPrimaryKey."`<>'0'")." AND a.`".$TblSetting["table"]['directory_root']."`='0'";	
		}
			
		// и не указаны другие настройки сортировки
		if($TblSetting["table"]['order'] != ""){
			$tmp_arr0 = explode(",", $TblSetting["table"]["order"]);$tmp_arr1 = array();
			foreach($tmp_arr0 as $tmp_val){$tmp_arr1[] = "a.".trim($tmp_val);}
			$tblOrder = " ORDER BY ".implode(",",$tmp_arr1);
			unset($tmp_arr0, $tmp_arr1);
		}
		else{
			$tblOrder = " ORDER BY a.".$TblSetting["table"]["directory_type"]." DESC, a.".$TblSetting["table"]["directory_name"]." ASC";
		}
	}
	else{
		$tblWhere = " WHERE ".(($TblSetting["table"]['StatusField']!="" && $TblSetting["table"]['AllRows']=="0" )?"a.`".$TblSetting["table"]['StatusField']."`='1'":"a.`".$TblFieldPrimaryKey."`<>'0'");
		if($TblSetting["table"]['order'] != ""){
			$tmp_arr0 = explode(",", $TblSetting["table"]['order']);$tmp_arr1 = array();
			foreach($tmp_arr0 as $tmp_val){$tmp_arr1[] = "a.".trim($tmp_val);}
			$tblOrder = " ORDER BY ".implode(",",$tmp_arr1);
			unset($tmp_arr0, $tmp_arr1);
		}
		// для сортироваки по столбцам ///////////////////////////////////////
		if($tblOrder_get != ""){
			$tblOrder = "ORDER BY ".$tblOrder_get;
		}
		// конец для сортироваки по столбцам ///////////////////////////////////////
	}
	
	//Поиск
	include(GetIncFile($arrSetting,"inc.tables.list.search.php", $TblSetting["table"]["name"]));
	
	// запрос к таблице
	include(GetIncFile($arrSetting,"inc.tables.list.select.php", $TblSetting["table"]["name"]));
	
	// обработка результата
	$show_row  = "";
	if($sql->sql_rows($result)){
		$tmpArrSetField = $TblSetting["sortfield"];
		while($query = $sql->sql_array($result)){
			$show_field = "";
			foreach($TblSetting["sortfield"] as $key => $val){
				if(isset($TblSetting[$key])){
					if(is_array($TblSetting[$key])){
						if($TblSetting[$key]["visible"] == 1 && $TblSetting[$key]['type'] != "hide"){
							include(GetIncFile($arrSetting,"inc.tables.list.row.php", $TblSetting["table"]["name"]));
						}
					}
				}
			}
			$show_row.= GetTpl("row", array("field" => $show_field), $TblDefTplPath);
		}
	}
	
	// итоги в низу таблицы по колонкам с числовым типом
	include(GetIncFile($arrSetting,"inc.tables.list.total.php", $TblSetting["table"]["name"]));
	
	$show_list = GetTpl("list", array("row_head" => $show_row_head, "row" => $show_row, ), $TblDefTplPath);
	echo $show_list;	
	
	// выполняем функцию после загрузки списка таблицы
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["AfterLoadingTable"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
	
?>