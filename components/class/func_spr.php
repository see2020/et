<?php
/**
 * func_spr.php - функции для работы с таблицой с типом справочник
 *
 */
	// $arrTableSpr["table_name"]			 = "tst_spr"; // таблица справочника
	// $arrTableSpr["order_by"]				 = "type_row desc, name asc"; // порядок сортировки
	// $arrTableSpr["PrimaryKey"]			 = "id";
	// $arrTableSpr["id"]					 = "id"; // идентификатор записи
	// $arrTableSpr["id_root"]				 = "id_root"; // поле с значениями родительской записи
	// $arrTableSpr["field_name"]			 = "id"; // название поля для вывода в списках
	// $arrTableSpr["field_name_show"]		 = "name"; // для двойного поля где связь по id
	// $arrTableSpr["field_status"]			 = "st";// поле статуса
	// $arrTableSpr["field_type_row"]		 = "type_row"; // тип записи категория или нет 0/1
	// $arrTableSpr["input_return_name"]		 = "id_spr"; // в какое поле возвращать значение выбрнной записи
	// $arrTableSpr["input_return_name_show"]	 = "id_spr_show"; // для двойного поля где связь по id
	// $arrTableSpr["uplevel_link"]				 = "";

	// навигация по сраницам
	function spr_navigation_list($sql, $arrTableSpr, $path_lnk, $arrNav = array(), $start_id_arr = 0, $id_root = "", $type_list = "window"){
		
		if($arrTableSpr["table"]['order'] == ""){
			$arrTableSpr["table"]['order'] = $arrTableSpr["table"]["directory_type"]." DESC, ".$arrTableSpr["table"]["directory_name"]." ASC";
		}
	
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]['name']." where ".$arrTableSpr["table"]['PrimaryKey']."='".$id_root."' and ".$arrTableSpr["table"]['StatusField']."='1' ORDER BY ".$arrTableSpr["table"]['order']);
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$arrNav[$start_id_arr]["name"] = $query[$arrTableSpr["table"]['directory_name']];
			
			if($type_list == "window"){
				$arrNav[$start_id_arr]["link"] = $path_lnk."
				&".$arrTableSpr["table"]['PrimaryKey']."=".$query[$arrTableSpr["table"]['PrimaryKey']]."
				&".$arrTableSpr["table"]['directory_root']."=".$query[$arrTableSpr["table"]['PrimaryKey']];
			}
			else{
				$arrNav[$start_id_arr]["link"] = $path_lnk."
				&".$arrTableSpr["table"]['PrimaryKey']."=".$query[$arrTableSpr["table"]['PrimaryKey']];
			}
			
			$start_id_arr++;
			$arrNav1 = array();
			$arrNav1 = spr_navigation_list($sql, $arrTableSpr, $path_lnk, $arrNav, $start_id_arr, $query[$arrTableSpr["table"]['directory_root']], $type_list);
			$arrNav	 = $arrNav1 + $arrNav;
			return($arrNav);
		}	
		return(array());
	}
	
	function spr_navigation_show($sql, $arrTableSpr, $path_lnk, $arrNav = array(), $start_id_arr = 0, $id_root = 0, $type_list = "window"){
		$return_arr = array();
		$arrNav	 = spr_navigation_list($sql, $arrTableSpr, $path_lnk, array(), $start_id_arr, $id_root, $type_list);
		if($type_list == "window"){
			$arrNav[] = array("name" => "на начало","link" => $path_lnk,);
		}
		$count_k	 = 0;
		$NavShow	 = "";
		$WinId		 = "";
		for($i = count($arrNav)-1; $i >= 0; $i--){
			$var_id_for_nav = "id_nav_cat_".$count_k;
			if($type_list == "window"){
				$var_id_for_nav = "id_nav_win_cat_".$count_k;
				$WinId.= "$('#".$var_id_for_nav."').nyroModal();";
			}
			$NavShow.= " / <a href='".$arrNav[$i]["link"]."' id='".$var_id_for_nav."'>".$arrNav[$i]["name"]."</a>";
			$count_k++;
		}
		$return_arr["NavShow"]	 = $NavShow;
		$return_arr["WinId"]	 = $WinId;
		return($return_arr);
	}

	function spr_navigation_list_dir_id($sql, $arrTableSpr, $path_lnk, $arrNav = array(), $start_id_arr = 0, $id_root = "", $type_list = "window"){
		
		if($arrTableSpr["table"]['order'] == ""){
			$arrTableSpr["table"]['order'] = $arrTableSpr["table"]["directory_type"]." DESC, ".$arrTableSpr["table"]["directory_name"]." ASC";
		}
		
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]['name']." where ".$arrTableSpr["table"]['PrimaryKey']."='".$id_root."' and ".$arrTableSpr["table"]['StatusField']."='1' ORDER BY ".$arrTableSpr["table"]['order']);
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$arrNav[$start_id_arr]["name"] = $query[$arrTableSpr["table"]['directory_name']];

			if($_GET["field_type"] != ""){$field_type = "&field_type=".spr_JsUrlDecode($_GET["field_type"]);}else{$field_type = "";} // - тип поля
			if($type_list == "window"){
				$arrNav[$start_id_arr]["link"] = $path_lnk.$field_type."&".$arrTableSpr["table"]['PrimaryKey']."=".$query[$arrTableSpr["table"]['PrimaryKey']]."&".$arrTableSpr["table"]['directory_root']."=".$query[$arrTableSpr["table"]['PrimaryKey']];
			}
			else{
				$arrNav[$start_id_arr]["link"] = $path_lnk.$field_type."&".$arrTableSpr["table"]['PrimaryKey']."=".$query[$arrTableSpr["table"]['PrimaryKey']];
			}
			
			$start_id_arr++;
			$arrNav1 = array();
			$arrNav1 = spr_navigation_list_dir_id($sql, $arrTableSpr, $path_lnk, $arrNav, $start_id_arr, $query[$arrTableSpr["table"]['directory_root']], $type_list);
			$arrNav	 = $arrNav1 + $arrNav;
			return($arrNav);
		}	
		return(array());
	}
	
	function spr_navigation_show_dir_id($sql, $arrTableSpr, $path_lnk, $arrNav = array(), $start_id_arr = 0, $id_root = 0, $type_list = "window"){

		$return_arr = array();

		$field_type = "&field_type=directory_id";

		$arrNav	 = spr_navigation_list_dir_id($sql, $arrTableSpr, $path_lnk.$field_type, array(), $start_id_arr, $id_root, $type_list);
	
		if($type_list == "window"){
			$arrNav[] = array("name" => "на начало","link" => $path_lnk.$field_type,);
		}
		
		$count_k	 = 0;
		$NavShow	 = "";
		$WinId		 = "";
		for($i = count($arrNav)-1; $i >= 0; $i--){
			$var_id_for_nav = "id_nav_cat_".$count_k;
			if($type_list == "window"){
				$var_id_for_nav = "id_nav_win_cat_".$count_k;
				$WinId.= "$('#".$var_id_for_nav."').nyroModal();";
			}
			$NavShow.= " / <a href='".$arrNav[$i]["link"]."' id='".$var_id_for_nav."'>".$arrNav[$i]["name"]."</a>";
			$count_k++;
		}
		$return_arr["NavShow"]	 = $NavShow;
		$return_arr["WinId"]	 = $WinId;
		return($return_arr);
			
	}

	// получение кода выбранного элемента
	function spr_GetSelectedCode($sql, $arrTableSpr, $selected_val){
		// $selected_val = (int)$selected_val;
		// if($selected_val == 0){return(false);}
		if(empty($arrTableSpr["table"])){
			return false;
		}
		$query123["id"] = 0;
		$result123 = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]['name']." 
		WHERE `".$arrTableSpr["table"]['directory_name']."`='".$selected_val."'");
		if($sql->sql_rows($result123)){
			$query123 = $sql->sql_array($result123);
			return($query123[$arrTableSpr["table"]["PrimaryKey"]]);
		}
		else{
			return(false);
		}
	}
	// получение кода выбранного элемента, если тип directory_id
	function spr_GetSelectedCodeById($sql, $arrTableSpr, $selected_val){
		if(empty($arrTableSpr["table"])){
			return false;
		}
		$query123["id"] = 0;
		$result123 = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]['name']." 
		WHERE `".$arrTableSpr["table"]['PrimaryKey']."`='".$selected_val."'");
		if($sql->sql_rows($result123)){
			$query123 = $sql->sql_array($result123);
			return($query123[$arrTableSpr["table"]["PrimaryKey"]]);
		}
			return false;
	}

	// список записей справочника для окна для текстовых полей
	function spr_list_text($sql, $arrSetting, $arrTableSpr, $path_lnk, $var_where){
		$return_arr = array();
		$return_arr["list"]		 = "";
		$return_arr["WinNMId"]	 = "";

		if($arrTableSpr["table"]['order'] == ""){
			$arrTableSpr["table"]['order'] = $arrTableSpr["table"]["directory_type"]." DESC, ".$arrTableSpr["table"]["directory_name"]." ASC";
		}

		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]['name']." WHERE ".$var_where." AND ".$arrTableSpr["table"]['StatusField']."='1' ORDER BY ".$arrTableSpr["table"]['order']);
		if($sql->sql_rows($result)){
			$count_k = 0;
			while($query = $sql->sql_array($result)){
				$count_k++;
				$var_id_for_cat = "id_".$query[$arrTableSpr["table"]['PrimaryKey']]."_cat_".$count_k;	
				$var_id_for_row = "id_".$query[$arrTableSpr["table"]['PrimaryKey']]."_row_".$count_k;	
				$return_var = "";
				
				// если это категория
				if($query[$arrTableSpr["table"]['directory_type']] == "1"){
					$return_var.= spr_windowSetTpl("<img src='".$arrSetting['Path']['ico']."/folder.gif'/>", "ico");
					
					$return_var.= spr_windowSetTpl("
						<a 
							href='".$path_lnk.
								"&".$arrTableSpr["table"]['PrimaryKey']."=".$query[$arrTableSpr["table"]['PrimaryKey']].
								"&".$arrTableSpr["table"]['directory_root']."=".$query[$arrTableSpr["table"]['PrimaryKey']]."' 
							id='".$var_id_for_cat."'
							title='".$var_id_for_cat."'
						>"
						.$query[$arrTableSpr["table"]['directory_name']]
						.(($arrTableSpr["table"]['directory_name2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name2']]:"")
						.(($arrTableSpr["table"]['directory_name3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name3']]:"")
						.(($arrTableSpr["table"]['directory_name4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name4']]:"")
						.(($arrTableSpr["table"]['directory_name5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name5']]:"")
						."</a>", "list_block");
					$return_arr["WinNMId"].= "$('#".$var_id_for_cat."').nyroModal();";
				}
				// если это запись справочника, не категория
				else{
				
					// jsAddFieldPlus
				
					$return_var.= spr_windowSetTpl("<img src='".$arrSetting['Path']['ico']."/group-checked.gif'/>", "ico");
					$return_var.= spr_windowSetTpl("<a href='javascript:void(0);' OnClick=\"
					jsAddField('".$_GET['irn']."','"
					// .$query[$arrTableSpr["table"]['directory_name']]
					.$query[$arrTableSpr["table"]['directory_name_edit']]
					.(($arrTableSpr["table"]['directory_name_edit2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit2']]:"")
					.(($arrTableSpr["table"]['directory_name_edit3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit3']]:"")
					.(($arrTableSpr["table"]['directory_name_edit4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit4']]:"")
					.(($arrTableSpr["table"]['directory_name_edit5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit5']]:"")

					."');$.nmTop().close();\">"
					// .$query[$arrTableSpr["table"]['directory_name']]
					.(($arrTableSpr[$arrTableSpr["table"]['directory_name']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name']]."' style='width:50px;height:50px;' >":$query[$arrTableSpr["table"]['directory_name']])
					.(($arrTableSpr["table"]['directory_name2']!="" && $query[$arrTableSpr["table"]['directory_name2']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name2']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name2']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name2']]):"")
					.(($arrTableSpr["table"]['directory_name3']!="" && $query[$arrTableSpr["table"]['directory_name3']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name3']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name3']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name3']]):"")
					.(($arrTableSpr["table"]['directory_name4']!="" && $query[$arrTableSpr["table"]['directory_name4']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name4']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name4']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name4']]):"")
					.(($arrTableSpr["table"]['directory_name5']!="" && $query[$arrTableSpr["table"]['directory_name5']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name5']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name5']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name5']]):"")
					
					."</a>", "list_block");
					
				}
				// разрешен выбор категории
				if($arrTableSpr["table"]["directory_NoSelectCat"] == 0){
					$return_var.= spr_windowSetTpl("<a href='#' OnClick=\"
					jsAddField('".$_GET['irn']."','"
					.$query[$arrTableSpr["table"]['directory_name_edit']]
					.(($arrTableSpr["table"]['directory_name_edit2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit2']]:"")
					.(($arrTableSpr["table"]['directory_name_edit3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit3']]:"")
					.(($arrTableSpr["table"]['directory_name_edit4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit4']]:"")
					.(($arrTableSpr["table"]['directory_name_edit5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit5']]:"")
					."');$.nmTop().close();\" title='Выбрать'><img src='".$arrSetting['Path']['ico']."/accept.gif'/></a>", "ico","right","center");
				}
				$return_arr["list"].= spr_windowSetTpl($return_var, "row");
			}
		}
		return($return_arr);
	}

	// для множественного выбора
	function spr_list_text_ms($sql, $arrSetting, $arrTableSpr, $path_lnk, $var_where, $MultiSelectSep = ", "){
		$return_arr = array();
		$return_arr["list"]		 = "";
		$return_arr["WinNMId"]	 = "";

		if($arrTableSpr["table"]['order'] == ""){
			$arrTableSpr["table"]['order'] = $arrTableSpr["table"]["directory_type"]." DESC, ".$arrTableSpr["table"]["directory_name"]." ASC";
		}

		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]['name']." WHERE ".$var_where." AND ".$arrTableSpr["table"]['StatusField']."='1' ORDER BY ".$arrTableSpr["table"]['order']);
		if($sql->sql_rows($result)){
			$count_k = 0;
			while($query = $sql->sql_array($result)){
				$count_k++;
				$var_id_for_cat = "id_".$query[$arrTableSpr["table"]['PrimaryKey']]."_cat_".$count_k;	
				$var_id_for_row = "id_".$query[$arrTableSpr["table"]['PrimaryKey']]."_row_".$count_k;	
				$return_var = "";
				
				// если это категория
				if($query[$arrTableSpr["table"]['directory_type']] == "1"){
					$return_var.= spr_windowSetTpl("<img src='".$arrSetting['Path']['ico']."/folder.gif'/>", "ico");
					
					$return_var.= spr_windowSetTpl("
						<a 
							href='".$path_lnk.
								"&".$arrTableSpr["table"]['PrimaryKey']."=".$query[$arrTableSpr["table"]['PrimaryKey']].
								"&".$arrTableSpr["table"]['directory_root']."=".$query[$arrTableSpr["table"]['PrimaryKey']]."' 
							id='".$var_id_for_cat."'
							title='".$var_id_for_cat."'
						>"
						.$query[$arrTableSpr["table"]['directory_name']]
						.(($arrTableSpr["table"]['directory_name2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name2']]:"")
						.(($arrTableSpr["table"]['directory_name3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name3']]:"")
						.(($arrTableSpr["table"]['directory_name4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name4']]:"")
						.(($arrTableSpr["table"]['directory_name5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name5']]:"")
						."</a>", "list_block");
					$return_arr["WinNMId"].= "$('#".$var_id_for_cat."').nyroModal();";
				}
				// если это запись справочника, не категория
				else{
				
					// jsAddFieldPlus
				
					$return_var.= spr_windowSetTpl("<img src='".$arrSetting['Path']['ico']."/group-checked.gif'/>", "ico");

					$return_var.= spr_windowSetTpl("<a href='javascript:void(0);' OnClick=\"
					if(document.getElementById('".$_GET['irn']."').value == ''){
						document.getElementById('".$_GET['irn']."').value	 = '".$query[$arrTableSpr["table"]['directory_name_edit']]
							.(($arrTableSpr["table"]['directory_name_edit2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit2']]:"")
							.(($arrTableSpr["table"]['directory_name_edit3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit3']]:"")
							.(($arrTableSpr["table"]['directory_name_edit4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit4']]:"")
							.(($arrTableSpr["table"]['directory_name_edit5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit5']]:"")
							."';
					}
					else{
						document.getElementById('".$_GET['irn']."').value	 = document.getElementById('".$_GET['irn']."').value + '".$MultiSelectSep."' + '"
						.$query[$arrTableSpr["table"]['directory_name_edit']]
						.(($arrTableSpr["table"]['directory_name_edit2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit2']]:"")
						.(($arrTableSpr["table"]['directory_name_edit3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit3']]:"")
						.(($arrTableSpr["table"]['directory_name_edit4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit4']]:"")
						.(($arrTableSpr["table"]['directory_name_edit5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit5']]:"")
						."';
					}
					\">"
					.(($arrTableSpr[$arrTableSpr["table"]['directory_name']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name']]."' style='width:50px;height:50px;' >":$query[$arrTableSpr["table"]['directory_name']])
					.(($arrTableSpr["table"]['directory_name2']!="" && $query[$arrTableSpr["table"]['directory_name2']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name2']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name2']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name2']]):"")
					.(($arrTableSpr["table"]['directory_name3']!="" && $query[$arrTableSpr["table"]['directory_name3']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name3']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name3']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name3']]):"")
					.(($arrTableSpr["table"]['directory_name4']!="" && $query[$arrTableSpr["table"]['directory_name4']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name4']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name4']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name4']]):"")
					.(($arrTableSpr["table"]['directory_name5']!="" && $query[$arrTableSpr["table"]['directory_name5']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name5']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name5']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name5']]):"")
					
					."</a>", "list_block");
				}
				// разрешен выбор категории
				if($arrTableSpr["table"]["directory_NoSelectCat"] == 0){
					$return_var.= spr_windowSetTpl("<a href='#' OnClick=\"
					if(document.getElementById('".$_GET['irn']."').value == ''){
						document.getElementById('".$_GET['irn']."').value	 = '".$query[$arrTableSpr["table"]['directory_name_edit']]
							.(($arrTableSpr["table"]['directory_name_edit2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit2']]:"")
							.(($arrTableSpr["table"]['directory_name_edit3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit3']]:"")
							.(($arrTableSpr["table"]['directory_name_edit4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit4']]:"")
							.(($arrTableSpr["table"]['directory_name_edit5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit5']]:"")
							."';
					}
					else{
						document.getElementById('".$_GET['irn']."').value	 = document.getElementById('".$_GET['irn']."').value + '".$MultiSelectSep."' + '"
						.$query[$arrTableSpr["table"]['directory_name_edit']]
						.(($arrTableSpr["table"]['directory_name_edit2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit2']]:"")
						.(($arrTableSpr["table"]['directory_name_edit3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit3']]:"")
						.(($arrTableSpr["table"]['directory_name_edit4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit4']]:"")
						.(($arrTableSpr["table"]['directory_name_edit5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit5']]:"")
						."';
					}
					\" title='Выбрать'><img src='".$arrSetting['Path']['ico']."/accept.gif'/></a>", "ico","right","center");
				}
				$return_arr["list"].= spr_windowSetTpl($return_var, "row");
			}
		}
		return($return_arr);
	}

	// имя элемента справочника по ID
	function spr_get_element($sql, $arrTableSpr, $selected_id = ""){
		$return_var = "";
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]["name"]." where `".$arrTableSpr["table"]["PrimaryKey"]."`='".$selected_id."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$return_var = $query[$arrTableSpr["table"]["directory_name"]];
		}
		return($return_var);
	}
	
	// полное имя элемента справочника по ID
	function spr_get_element_nav($sql, $arrTableSpr, $selected_id = "", $str = ""){
		$return_var = "";
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]["name"]." where `".$arrTableSpr["table"]["PrimaryKey"]."`='".$selected_id."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$return_var = " / ".$query[$arrTableSpr["table"]["directory_name"]];
			$str = spr_get_element_nav($sql, $arrTableSpr, $query[$arrTableSpr["table"]["directory_root"]],$return_var).$str;
		}
		return($str);
		
	}
	
	// имя элемента справочника по ID для списка
	function spr_get_element_for_list($sql, $arrTableSpr, $selected_id = ""){
		$return_var = "";
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]["name"]." where `".$arrTableSpr["table"]["PrimaryKey"]."`='".$selected_id."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$return_var = $query[$arrTableSpr["table"]["directory_name"]];
			if($arrTableSpr["table"]["directory_name2"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name2"]];}
			if($arrTableSpr["table"]["directory_name3"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name3"]];}
			if($arrTableSpr["table"]["directory_name4"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name4"]];}
			if($arrTableSpr["table"]["directory_name5"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name5"]];}
		}
		return($return_var);
	}
	// полное имя элемента справочника по ID для списка
	function spr_get_element_nav_for_list($sql, $arrTableSpr, $selected_id = "", $str = ""){
		$return_var = "";
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]["name"]." where `".$arrTableSpr["table"]["PrimaryKey"]."`='".$selected_id."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$return_var = " / ".$query[$arrTableSpr["table"]["directory_name"]];
			if($arrTableSpr["table"]["directory_name2"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name2"]];}
			if($arrTableSpr["table"]["directory_name3"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name3"]];}
			if($arrTableSpr["table"]["directory_name4"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name4"]];}
			if($arrTableSpr["table"]["directory_name5"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name5"]];}
			
			$str = spr_get_element_nav_for_list($sql, $arrTableSpr, $query[$arrTableSpr["table"]["directory_root"]],$return_var).$str;
		}
		return($str);
		
	}
	// имя элемента справочника по ID для списка
	function spr_get_element_for_edit($sql, $arrTableSpr, $selected_id = ""){
		$return_var = "";
		if(isset($arrTableSpr["table"])){
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]["name"]." where `".$arrTableSpr["table"]["PrimaryKey"]."`='".$selected_id."'");
			if($sql->sql_rows($result)){
				$query = $sql->sql_array($result);
				$return_var = $query[$arrTableSpr["table"]["directory_name_edit"]];
				if($arrTableSpr["table"]["directory_name_edit2"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit2"]];}
				if($arrTableSpr["table"]["directory_name_edit3"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit3"]];}
				if($arrTableSpr["table"]["directory_name_edit4"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit4"]];}
				if($arrTableSpr["table"]["directory_name_edit5"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit5"]];}
			}
		}
		return($return_var);
	}
	// полное имя элемента справочника по ID для списка
	function spr_get_element_nav_for_edit($sql, $arrTableSpr, $selected_id = "", $str = ""){
		$return_var = "";
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]["name"]." where `".$arrTableSpr["table"]["PrimaryKey"]."`='".$selected_id."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$return_var = " / ".$query[$arrTableSpr["table"]["directory_name_edit"]];
			if($arrTableSpr["table"]["directory_name_edit2"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit2"]];}
			if($arrTableSpr["table"]["directory_name_edit3"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit3"]];}
			if($arrTableSpr["table"]["directory_name_edit4"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit4"]];}
			if($arrTableSpr["table"]["directory_name_edit5"] != ""){$return_var.= "; ".$query[$arrTableSpr["table"]["directory_name_edit5"]];}
			
			$str = spr_get_element_nav_for_edit($sql, $arrTableSpr, $query[$arrTableSpr["table"]["directory_root"]],$return_var).$str;
		}
		return($str);
		
	}

	// получение массива с данными для поляе типа select и radio
	function spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key){

		$return_arr = array();

		$TblCfg = new class_ini();
		$TblCfg->fINIFileName = $arrSetting['Path']['tbldata']."/".$TblSetting[$key]['directory_table']."/".$TblSetting[$key]['directory_table'].".ini";
		$TblCfg->fINIInitArray();
		$TblSpr = $TblCfg->fINIArray;
		
		
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$TblSetting[$key]['directory_table']." where `".$TblSpr["table"]["StatusField"]."`='1' ORDER BY ".$TblSpr["table"]["directory_name"]." asc");
		if($sql->sql_rows($result)){
			while($query = $sql->sql_array($result)){
				$return_arr[$query[$TblSpr["table"]["PrimaryKey"]]] = $query[$TblSpr["table"]["directory_name"]];	
			}
		
		}
		return($return_arr);
	}
	
	// для выбора из справчника
	function spr_list_dir_id($sql, $arrSetting, $arrTableSpr, $path_lnk, $var_where){
		$return_arr = array();
		$return_arr["list"]		 = "";
		$return_arr["WinNMId"]	 = "";
	
		if($arrTableSpr["table"]['order'] == ""){
			$arrTableSpr["table"]['order'] = $arrTableSpr["table"]["directory_type"]." DESC, ".$arrTableSpr["table"]["directory_name"]." ASC";
		}
		
		$result = $sql->sql_query("
			SELECT * 
			FROM  ".$sql->prefix_db.$arrTableSpr["table"]['name']." 
			WHERE 
				".$var_where." 
				AND ".$arrTableSpr["table"]['StatusField']."='1' 
			ORDER BY ".$arrTableSpr["table"]['order']);
		if($sql->sql_rows($result)){
			$count_k = 0;
			while($query = $sql->sql_array($result)){
				$count_k++;
				$var_id_for_cat = "id_".$query[$arrTableSpr["table"]['PrimaryKey']]."_cat_".$count_k;	
				$var_id_for_row = "id_".$query[$arrTableSpr["table"]['PrimaryKey']]."_row_".$count_k;	
				$return_var = "";
			
				// это то, что будет выводиться в поле после выбора
				// если установлено выводить полный путь к записи 
				if($arrTableSpr["table"]["directory_UseFullPath"] != 0){
					//$ret_name_show = spr_get_element_nav($sql,$arrTableSpr, $query[$arrTableSpr["table"]['PrimaryKey']]);
					$ret_name_show = spr_get_element_nav_for_list($sql,$arrTableSpr, $query[$arrTableSpr["table"]['PrimaryKey']]);
				}
				else{
					$ret_name_show = $query[$arrTableSpr["table"]['directory_name']];
				}
				
				// если это категория
				if($query[$arrTableSpr["table"]['directory_type']] == "1"){
					$return_var.= spr_windowSetTpl("<img src='".$arrSetting['Path']['ico']."/folder.gif'/>", "ico");
					$return_var.= spr_windowSetTpl("<a href='".$path_lnk."&".$arrTableSpr["table"]['PrimaryKey']."=".$query[$arrTableSpr["table"]['PrimaryKey']]."&".$arrTableSpr["table"]['directory_root']."=".$query[$arrTableSpr["table"]['PrimaryKey']]."' id='".$var_id_for_cat."'>"
					.(($arrTableSpr[$arrTableSpr["table"]['directory_name']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name']]."' style='width:50px;height:50px;' >":$query[$arrTableSpr["table"]['directory_name']])
					.(($arrTableSpr["table"]['directory_name2']!="" && $query[$arrTableSpr["table"]['directory_name2']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name2']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name2']]."' style='width:40px;height:40px;'>":$query[$arrTableSpr["table"]['directory_name2']]):"")
					.(($arrTableSpr["table"]['directory_name3']!="" && $query[$arrTableSpr["table"]['directory_name3']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name3']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name3']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name3']]):"")
					.(($arrTableSpr["table"]['directory_name4']!="" && $query[$arrTableSpr["table"]['directory_name4']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name4']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name4']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name4']]):"")
					.(($arrTableSpr["table"]['directory_name5']!="" && $query[$arrTableSpr["table"]['directory_name5']] != "")?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name5']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name5']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name5']]):"")
					."</a>", "list_block");
					$return_arr["WinNMId"].= "$('#".$var_id_for_cat."').nyroModal();";
				}
				// если это запись справочника, не категория
				else{
					$return_var.= spr_windowSetTpl("<img src='".$arrSetting['Path']['ico']."/group-checked.gif'/>", "ico");
					$return_var.= spr_windowSetTpl("<a href='#' OnClick=\"
					jsAddField(
					'".$_GET['irn']."','"
					.$query[$arrTableSpr["table"]['PrimaryKey']]
					."');
					jsAddField('".$_GET['irn']."_show"."','".$ret_name_show."'
					);$.nmTop().close();\">"
					.(($arrTableSpr[$arrTableSpr["table"]['directory_name']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name']]."' style='width:50px;height:50px;' >":$query[$arrTableSpr["table"]['directory_name']])
					.(($arrTableSpr["table"]['directory_name2']!="" && $query[$arrTableSpr["table"]['directory_name2']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name2']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name2']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name2']]):"")
					.(($arrTableSpr["table"]['directory_name3']!="" && $query[$arrTableSpr["table"]['directory_name3']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name3']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name3']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name3']]):"")
					.(($arrTableSpr["table"]['directory_name4']!="" && $query[$arrTableSpr["table"]['directory_name4']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name4']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name4']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name4']]):"")
					.(($arrTableSpr["table"]['directory_name5']!="" && $query[$arrTableSpr["table"]['directory_name5']] != "" )?"; ".(($arrTableSpr[$arrTableSpr["table"]['directory_name5']]['type'] == "image")?"<img src='".$query[$arrTableSpr["table"]['directory_name5']]."' style='width:40px;height:40px;' >":$query[$arrTableSpr["table"]['directory_name5']]):"")

					
					."</a>", "list_block");
				}
				// разрешен выбор категории
				//if($allow_selected_category){
				if($arrTableSpr["table"]["directory_NoSelectCat"] == 0){
					$return_var.= spr_windowSetTpl("<a href='#' OnClick=\"
					jsAddField('".$_GET['irn']."',
					'"
					.$query[$arrTableSpr["table"]['PrimaryKey']]
					."');
					jsAddField('".$_GET['irn']."_show"."','".$ret_name_show."');$.nmTop().close();\" title='Выбрать'><img src='".$arrSetting['Path']['ico']."/accept.gif'/></a>", "ico","right","center");
				}
				$return_arr["list"].= spr_windowSetTpl($return_var, "row");
			}
		}
		return($return_arr);
	}

	// TPL
	function spr_WindowSetHead($arrSetting){
		$return_var = "";
		$return_var.= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
		$return_var.= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
		$return_var.= "<head>";
		$return_var.= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\" />";
		$return_var.= "<title>Выбор</title>";
		$return_var.= "<link rel=\"stylesheet\" href=\"".$arrSetting['Path']['tpl']."/default/css/table.css\" />";
		$return_var.= "</head>";
		$return_var.= "<body>";
		$return_var.= "";
		return($return_var);
	}
	function spr_WindowSetFoot($arrSetting, $nyroModal = ""){
		$return_var = "";
		$return_var.= "";
		$return_var.= '<script type="text/javascript">$(function() {';
		$return_var.= $nyroModal;
		$return_var.= '});</script>';
		$return_var.= "</body>";
		$return_var.= "</html>";
		$return_var.= "";
		$return_var.= "";
		return($return_var);
	}
	function spr_windowSetTpl($text_data = "", $type = "row", $position = "left",$position_txt="left"){
		if($position == ""){$position = "left";}
		if($position_txt == ""){$position_txt = "left";}
		
		$return_var = "";
		if($type == "row"){
			$return_var.= "<div style=\"width: 99%;text-align: ".$position_txt."; border: 1px solid #cdcdcd;margin:1px 0px 1px 0px;padding: 3px;background: #FFFFFF;\">";
			$return_var.= $text_data;
			$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
			$return_var.= "</div>";
		}
		elseif($type == "nav"){
			$return_var.= "<div style=\"width: 99%;text-align: ".$position_txt."; border: 1px solid #cdcdcd;margin:1px 0px 1px 0px;padding: 3px;background: #ececec;\">";
			$return_var.= $text_data;
			$return_var.= "</div>";	
		}
		elseif($type == "ico"){
			$return_var.= "<div style=\"float: ".$position.";width: 2.5%;text-align: ".$position_txt.";border: 0px solid #cdcdcd;margin:2px 0px 2px 0px;\">";
			$return_var.= $text_data;
			$return_var.= "</div>";
		}
		elseif($type == "list_block"){
			$return_var.= "<div style=\"float: ".$position.";width: 90%;text-align: ".$position_txt.";border: 0px solid #cdcdcd;margin:2px 0px 2px 0px;\">";
			$return_var.= $text_data;
			$return_var.= "</div>";
		}
		elseif($type == "min_block"){
			$return_var.= "<div style=\"float:".$position.";width: 60px;text-align: center;border: 0px solid #cdcdcd;margin:2px 0px 2px 0px;\">";
			$return_var.= $text_data;
			$return_var.= "</div>";
		}		
		elseif($type == "max_block"){
			$return_var.= "<div style=\"float:".$position.";width: 200px;text-align: ".$position_txt.";border: 0px solid #cdcdcd;margin:2px 0px 2px 0px;\">";
			$return_var.= $text_data;
			$return_var.= "</div>";
		}	
		else{
			$return_var.= "<div style=\"float:left;width: 400px;text-align: left;border: 0px solid #cdcdcd;margin:2px 0px 2px 0px;\">";
			$return_var.= $text_data;
			$return_var.= "</div>";		
		}
		
		return($return_var);
	}

	function spr_JsUrlDecode($str) { 
		$js_rus_unicode['%20'] = ' '; 
		$js_rus_unicode['%2C'] = ','; 
		$js_rus_unicode['%21'] = '!'; 
		$js_rus_unicode['%22'] = '"'; 
		$js_rus_unicode['%3B'] = ';'; 
		$js_rus_unicode['%25'] = '%'; 
		$js_rus_unicode['%3A'] = ':'; 
		$js_rus_unicode['%3F'] = '?'; 
		$js_rus_unicode['%28'] = '('; 
		$js_rus_unicode['%29'] = ')'; 
		$js_rus_unicode['%7E'] = '~'; 
		$js_rus_unicode['%23'] = '#'; 
		$js_rus_unicode['%24'] = '$'; 
		$js_rus_unicode['%5E'] = '^'; 
		$js_rus_unicode['%26'] = '&'; 
		$js_rus_unicode['%3D'] = '='; 
		$js_rus_unicode['%27'] = "'"; 
		$js_rus_unicode['%u2116'] = '№';
		
		$js_rus_unicode['%u0451'] = 'ё'; 
		$js_rus_unicode['%u0439'] = 'й'; 
		$js_rus_unicode['%u0446'] = 'ц'; 
		$js_rus_unicode['%u0443'] = 'у'; 
		$js_rus_unicode['%u043A'] = 'к'; 
		$js_rus_unicode['%u0435'] = 'е'; 
		$js_rus_unicode['%u043D'] = 'н'; 
		$js_rus_unicode['%u0433'] = 'г'; 
		$js_rus_unicode['%u0448'] = 'ш'; 
		$js_rus_unicode['%u0449'] = 'щ'; 
		$js_rus_unicode['%u0437'] = 'з'; 
		$js_rus_unicode['%u0445'] = 'х'; 
		$js_rus_unicode['%u044A'] = 'ъ'; 
		$js_rus_unicode['%u0444'] = 'ф'; 
		$js_rus_unicode['%u044B'] = 'ы'; 
		$js_rus_unicode['%u0432'] = 'в'; 
		$js_rus_unicode['%u0430'] = 'а'; 
		$js_rus_unicode['%u043F'] = 'п'; 
		$js_rus_unicode['%u0440'] = 'р'; 
		$js_rus_unicode['%u043E'] = 'о'; 
		$js_rus_unicode['%u043B'] = 'л'; 
		$js_rus_unicode['%u0434'] = 'д'; 
		$js_rus_unicode['%u0436'] = 'ж'; 
		$js_rus_unicode['%u044D'] = 'э'; 
		$js_rus_unicode['%u044F'] = 'я'; 
		$js_rus_unicode['%u0447'] = 'ч'; 
		$js_rus_unicode['%u0441'] = 'с'; 
		$js_rus_unicode['%u043C'] = 'м'; 
		$js_rus_unicode['%u0438'] = 'и'; 
		$js_rus_unicode['%u0442'] = 'т'; 
		$js_rus_unicode['%u044C'] = 'ь'; 
		$js_rus_unicode['%u0431'] = 'б'; 
		$js_rus_unicode['%u044E'] = 'ю'; 
		$js_rus_unicode['%u0401'] = 'Ё'; 
		$js_rus_unicode['%u0419'] = 'Й'; 
		$js_rus_unicode['%u0426'] = 'Ц'; 
		$js_rus_unicode['%u0423'] = 'У'; 
		$js_rus_unicode['%u041A'] = 'К'; 
		$js_rus_unicode['%u0415'] = 'Е'; 
		$js_rus_unicode['%u041D'] = 'Н'; 
		$js_rus_unicode['%u0413'] = 'Г'; 
		$js_rus_unicode['%u0428'] = 'Ш'; 
		$js_rus_unicode['%u0429'] = 'Щ'; 
		$js_rus_unicode['%u0417'] = 'З'; 
		$js_rus_unicode['%u0425'] = 'Х'; 
		$js_rus_unicode['%u042A'] = 'Ъ'; 
		$js_rus_unicode['%u0424'] = 'Ф'; 
		$js_rus_unicode['%u042B'] = 'Ы'; 
		$js_rus_unicode['%u0412'] = 'В'; 
		$js_rus_unicode['%u0410'] = 'А'; 
		$js_rus_unicode['%u041F'] = 'П'; 
		$js_rus_unicode['%u0420'] = 'Р'; 
		$js_rus_unicode['%u041E'] = 'О'; 
		$js_rus_unicode['%u041B'] = 'Л'; 
		$js_rus_unicode['%u0414'] = 'Д'; 
		$js_rus_unicode['%u0416'] = 'Ж'; 
		$js_rus_unicode['%u042D'] = 'Э'; 
		$js_rus_unicode['%u042F'] = 'Я'; 
		$js_rus_unicode['%u0427'] = 'Ч'; 
		$js_rus_unicode['%u0421'] = 'С'; 
		$js_rus_unicode['%u041C'] = 'М'; 
		$js_rus_unicode['%u0418'] = 'И'; 
		$js_rus_unicode['%u0422'] = 'Т'; 
		$js_rus_unicode['%u042C'] = 'Ь'; 
		$js_rus_unicode['%u0411'] = 'Б'; 
		$js_rus_unicode['%u042E'] = 'Ю'; 
		 
		foreach ($js_rus_unicode as $k=>$v) { 
			$str = str_replace($k,$v,$str); 
		} 
		$str = urldecode($str); 
		return($str); 
	} 
	
	function spr_JsUrlEscape($str){
		
		$js_rus_unicode = array(
		' ' => '%20',
		',' => '%2C',
		'!' => '%21',
		'"' => '%22',
		';' => '%3B',
		'%' => '%25',
		':' => '%3A',
		'?' => '%3F',
		'(' => '%28',
		')' => '%29',
		'~' => '%7E',
		'#' => '%23',
		'$' => '%24',
		'^' => '%5E',
		'&' => '%26',
		'=' => '%3D',
		"'" => '%27',
		'№' => '%u2116',
		
		'А' => '%u0410', 
		'Б' => '%u0411', 
		'В' => '%u0412', 
		'Г' => '%u0413', 
		'Д' => '%u0414', 
		'Е' => '%u0415', 
		'Ё' => '%u0401', 
		'Ж' => '%u0416', 
		'З' => '%u0417', 
		'И' => '%u0418',
		'Й' => '%u0419',
		'К' => '%u041A',
		'Л' => '%u041B',
		'М' => '%u041C',
		'Н' => '%u041D',
		'О' => '%u041E',
		'П' => '%u041F',
		'Р' => '%u0420',
		'С' => '%u0421',
		'Т' => '%u0422',
		'У' => '%u0423',
		'Ф' => '%u0424',
		'Х' => '%u0425',
		'Ц' => '%u0426',
		'Ч' => '%u0427',
		'Ш' => '%u0428',
		'Щ' => '%u0429',
		'Ъ' => '%u042A',
		'Ы' => '%u042B',
		'Ь' => '%u042C',
		'Э' => '%u042D',
		'Ю' => '%u042E',
		'Я' => '%u042F',

		'а' => '%u0430',
		'б' => '%u0431',
		'в' => '%u0432',
		'г' => '%u0433',
		'д' => '%u0434',
		'е' => '%u0435',
		'ё' => '%u0451',
		'ж' => '%u0436',
		'з' => '%u0437',
		'и' => '%u0438',
		'й' => '%u0439',
		'к' => '%u043A',
		'л' => '%u043B',
		'м' => '%u043C',
		'н' => '%u043D',
		'о' => '%u043E',
		'п' => '%u043F',
		'р' => '%u0440',
		'с' => '%u0441',
		'т' => '%u0442',
		'у' => '%u0443',
		'ф' => '%u0444',
		'х' => '%u0445',
		'ц' => '%u0446',
		'ч' => '%u0447',
		'ш' => '%u0448',
		'щ' => '%u0449',
		'ъ' => '%u044A',
		'ы' => '%u044B',
		'ь' => '%u044C',
		'э' => '%u044D',
		'ю' => '%u044E',
		'я' => '%u044F',
		);
		
		
		return strtr($str, $js_rus_unicode);
	}

	
	// для формы редактирования
	function spr_selectfield($sql, $arrSetting, $TblSetting, $arrTableSpr, $key, $qChange, $TblName, $TblFieldPrimaryKey, $FirstId = "0", $width_field = 350){
		$return_var = "";
		$return_var.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;\">";
		$return_var.= "<select name='".$TblSetting[$key]['name']."' id='".$TblSetting[$key]['name']."' style='width: ".((int)$width_field - 29)."px;'>";
			$return_var.= "<option value='".$FirstId."' ".(($qChange[$TblSetting[$key]['name']] == $FirstId)?"selected":"")."></option>";
			$return_var.= spr_RootListSelect($sql, $arrTableSpr, $FirstId, "  -  ", $qChange[$TblSetting[$key]['name']]);
		$return_var.= "</select>"; 
		
		if($_GET[$TblFieldPrimaryKey] != 0 && $qChange[$TblSetting[$key]['name']] != $FirstId){
			$up_level_lnk = (isset($arrTableSpr["uplevel_link"]) && $arrTableSpr["uplevel_link"] != "")?$arrTableSpr["uplevel_link"]:"?tbl=".$TblName."&".$TblFieldPrimaryKey."=".$qChange[$TblSetting[$key]['name']]."&event=edit";
			$return_var.= " <a href='".$up_level_lnk."' title='перейти'><img src='".$arrSetting['Path']['ico']."/uplevel.gif'/></a>";
		}
		$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_var.= "</div>";
		return($return_var);
	}

	function spr_RootListSelect($sql, $arrTableSpr, $id_root = "", $sep = "-", $selected_id = ""){
		$return_var = "";
		
		$SelectVar = "select * from `".$sql->prefix_db.$arrTableSpr["table_name"]."` where `".$arrTableSpr["id_root"]."`='".$id_root."' and ".$arrTableSpr["field_status"]."='1' and ".$arrTableSpr["field_type_row"]."='1'";
		$result = $sql->sql_query($SelectVar);
		if($sql->sql_rows($result)){
			while($query = $sql->sql_array($result)){
				$return_var.= "<option value='".$query[$arrTableSpr["id"]]."' ".(($query[$arrTableSpr["id"]] == $selected_id)?"selected":"").">".$sep.$query[$arrTableSpr["field_name"]]."</option>";
				$return_var.= spr_RootListSelect($sql, $arrTableSpr, $query[$arrTableSpr["id"]], $sep.$sep,$selected_id);
			}
		}
		return($return_var);
	}

	// для справоников где основной идентификатор не PrimaryKey
	function spr_GetIdbyCode($sql, $code_val = "", $arrTableSpr = array()){
		$return_var = 0;
		$code = trim($code_val);
		if($code == ""){return($return_var);}
		
		$result = $sql->sql_query("select * from `".$sql->prefix_db.$arrTableSpr["table_name"]."` where `".$arrTableSpr["id"]."`='".$code."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$return_var = $query[$arrTableSpr["PrimaryKey"]];
		}
		return($return_var);
	}
	
	
	//поле выбора для поля с типом directory_id
	// поле с выбором
	function spr_frmInputAddField($field_name = "", $field_data = "", $field_data2 = "", $AddButtonName = "", $WindowDataFile = "", $addJsFunc = 'jsAddField',$width_field = 350, $ro = false){

		$stl_ro = ($ro)?"border: 1px solid #cccccc;":"";
		
		if($AddButtonName == ""){$AddButtonName = "AddButtonName";}
		$return_var = "";
		$arr_ro = array();
		if($ro){$arr_ro = array("readonly"=>"readonly",);}
		
		$return_var.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		$return_var.= frmInput(array("type"=>"hidden", "name"=>$field_name, "id"=>$field_name, "style"=>"width: ".((int)$width_field - 60)."px; float:left;", "value"=>$field_data, ));
		$return_var.= frmInput(array("type"=>"text", "name"=>$field_name."_show", "id"=>$field_name."_show", "style"=>"width: ".((int)$width_field - 60)."px; float:left;", "value"=>$field_data2, ) + $arr_ro);
		if(!$ro){
			$return_var.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"X", "OnClick"=>"".$addJsFunc."('".$field_name."','');".$addJsFunc."('".$field_name."_show','');", "title"=>"Сбросить"));
			$return_var.= frmInput(array("type"=>"button", "name"=>$AddButtonName, "id"=>$AddButtonName, "style"=>"width: 28px; float:right;", "value"=>"...", "class"=>"btn_add", "href"=>"".$WindowDataFile."","title"=>"Выбрать из справочника"));
		}
		$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_var.= "</div>";
		$return_var.= '<script type="text/javascript">$(function() {$("#'.$AddButtonName.'").nyroModal();});</script>';
		
		return($return_var);
	}
?>