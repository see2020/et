<?php
/**
 * func_spr.php - ������� ��� ������ � �������� � ����� ����������
 *
 */
	// $arrTableSpr["table_name"]			 = "tst_spr"; // ������� �����������
	// $arrTableSpr["order_by"]				 = "type_row desc, name asc"; // ������� ����������
	// $arrTableSpr["PrimaryKey"]			 = "id";
	// $arrTableSpr["id"]					 = "id"; // ������������� ������
	// $arrTableSpr["id_root"]				 = "id_root"; // ���� � ���������� ������������ ������
	// $arrTableSpr["field_name"]			 = "id"; // �������� ���� ��� ������ � �������
	// $arrTableSpr["field_name_show"]		 = "name"; // ��� �������� ���� ��� ����� �� id
	// $arrTableSpr["field_status"]			 = "st";// ���� �������
	// $arrTableSpr["field_type_row"]		 = "type_row"; // ��� ������ ��������� ��� ��� 0/1
	// $arrTableSpr["input_return_name"]		 = "id_spr"; // � ����� ���� ���������� �������� �������� ������
	// $arrTableSpr["input_return_name_show"]	 = "id_spr_show"; // ��� �������� ���� ��� ����� �� id
	// $arrTableSpr["uplevel_link"]				 = "";

	// ��������� �� ��������
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
			$arrNav[] = array("name" => "�� ������","link" => $path_lnk,);
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

			if($_GET["field_type"] != ""){$field_type = "&field_type=".spr_JsUrlDecode($_GET["field_type"]);}else{$field_type = "";} // - ��� ����
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
			$arrNav[] = array("name" => "�� ������","link" => $path_lnk.$field_type,);
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

	// ��������� ���� ���������� ��������
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
	// ��������� ���� ���������� ��������, ���� ��� directory_id
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

	// ������ ������� ����������� ��� ���� ��� ��������� �����
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
				
				// ���� ��� ���������
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
				// ���� ��� ������ �����������, �� ���������
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
				// �������� ����� ���������
				if($arrTableSpr["table"]["directory_NoSelectCat"] == 0){
					$return_var.= spr_windowSetTpl("<a href='#' OnClick=\"
					jsAddField('".$_GET['irn']."','"
					.$query[$arrTableSpr["table"]['directory_name_edit']]
					.(($arrTableSpr["table"]['directory_name_edit2']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit2']]:"")
					.(($arrTableSpr["table"]['directory_name_edit3']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit3']]:"")
					.(($arrTableSpr["table"]['directory_name_edit4']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit4']]:"")
					.(($arrTableSpr["table"]['directory_name_edit5']!="")?"; ".$query[$arrTableSpr["table"]['directory_name_edit5']]:"")
					."');$.nmTop().close();\" title='�������'><img src='".$arrSetting['Path']['ico']."/accept.gif'/></a>", "ico","right","center");
				}
				$return_arr["list"].= spr_windowSetTpl($return_var, "row");
			}
		}
		return($return_arr);
	}

	// ��� �������������� ������
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
				
				// ���� ��� ���������
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
				// ���� ��� ������ �����������, �� ���������
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
				// �������� ����� ���������
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
					\" title='�������'><img src='".$arrSetting['Path']['ico']."/accept.gif'/></a>", "ico","right","center");
				}
				$return_arr["list"].= spr_windowSetTpl($return_var, "row");
			}
		}
		return($return_arr);
	}

	// ��� �������� ����������� �� ID
	function spr_get_element($sql, $arrTableSpr, $selected_id = ""){
		$return_var = "";
		$result = $sql->sql_query("SELECT * FROM  ".$sql->prefix_db.$arrTableSpr["table"]["name"]." where `".$arrTableSpr["table"]["PrimaryKey"]."`='".$selected_id."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			$return_var = $query[$arrTableSpr["table"]["directory_name"]];
		}
		return($return_var);
	}
	
	// ������ ��� �������� ����������� �� ID
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
	
	// ��� �������� ����������� �� ID ��� ������
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
	// ������ ��� �������� ����������� �� ID ��� ������
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
	// ��� �������� ����������� �� ID ��� ������
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
	// ������ ��� �������� ����������� �� ID ��� ������
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

	// ��������� ������� � ������� ��� ����� ���� select � radio
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
	
	// ��� ������ �� ����������
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
			
				// ��� ��, ��� ����� ���������� � ���� ����� ������
				// ���� ����������� �������� ������ ���� � ������ 
				if($arrTableSpr["table"]["directory_UseFullPath"] != 0){
					//$ret_name_show = spr_get_element_nav($sql,$arrTableSpr, $query[$arrTableSpr["table"]['PrimaryKey']]);
					$ret_name_show = spr_get_element_nav_for_list($sql,$arrTableSpr, $query[$arrTableSpr["table"]['PrimaryKey']]);
				}
				else{
					$ret_name_show = $query[$arrTableSpr["table"]['directory_name']];
				}
				
				// ���� ��� ���������
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
				// ���� ��� ������ �����������, �� ���������
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
				// �������� ����� ���������
				//if($allow_selected_category){
				if($arrTableSpr["table"]["directory_NoSelectCat"] == 0){
					$return_var.= spr_windowSetTpl("<a href='#' OnClick=\"
					jsAddField('".$_GET['irn']."',
					'"
					.$query[$arrTableSpr["table"]['PrimaryKey']]
					."');
					jsAddField('".$_GET['irn']."_show"."','".$ret_name_show."');$.nmTop().close();\" title='�������'><img src='".$arrSetting['Path']['ico']."/accept.gif'/></a>", "ico","right","center");
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
		$return_var.= "<title>�����</title>";
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
		$js_rus_unicode['%u2116'] = '�';
		
		$js_rus_unicode['%u0451'] = '�'; 
		$js_rus_unicode['%u0439'] = '�'; 
		$js_rus_unicode['%u0446'] = '�'; 
		$js_rus_unicode['%u0443'] = '�'; 
		$js_rus_unicode['%u043A'] = '�'; 
		$js_rus_unicode['%u0435'] = '�'; 
		$js_rus_unicode['%u043D'] = '�'; 
		$js_rus_unicode['%u0433'] = '�'; 
		$js_rus_unicode['%u0448'] = '�'; 
		$js_rus_unicode['%u0449'] = '�'; 
		$js_rus_unicode['%u0437'] = '�'; 
		$js_rus_unicode['%u0445'] = '�'; 
		$js_rus_unicode['%u044A'] = '�'; 
		$js_rus_unicode['%u0444'] = '�'; 
		$js_rus_unicode['%u044B'] = '�'; 
		$js_rus_unicode['%u0432'] = '�'; 
		$js_rus_unicode['%u0430'] = '�'; 
		$js_rus_unicode['%u043F'] = '�'; 
		$js_rus_unicode['%u0440'] = '�'; 
		$js_rus_unicode['%u043E'] = '�'; 
		$js_rus_unicode['%u043B'] = '�'; 
		$js_rus_unicode['%u0434'] = '�'; 
		$js_rus_unicode['%u0436'] = '�'; 
		$js_rus_unicode['%u044D'] = '�'; 
		$js_rus_unicode['%u044F'] = '�'; 
		$js_rus_unicode['%u0447'] = '�'; 
		$js_rus_unicode['%u0441'] = '�'; 
		$js_rus_unicode['%u043C'] = '�'; 
		$js_rus_unicode['%u0438'] = '�'; 
		$js_rus_unicode['%u0442'] = '�'; 
		$js_rus_unicode['%u044C'] = '�'; 
		$js_rus_unicode['%u0431'] = '�'; 
		$js_rus_unicode['%u044E'] = '�'; 
		$js_rus_unicode['%u0401'] = '�'; 
		$js_rus_unicode['%u0419'] = '�'; 
		$js_rus_unicode['%u0426'] = '�'; 
		$js_rus_unicode['%u0423'] = '�'; 
		$js_rus_unicode['%u041A'] = '�'; 
		$js_rus_unicode['%u0415'] = '�'; 
		$js_rus_unicode['%u041D'] = '�'; 
		$js_rus_unicode['%u0413'] = '�'; 
		$js_rus_unicode['%u0428'] = '�'; 
		$js_rus_unicode['%u0429'] = '�'; 
		$js_rus_unicode['%u0417'] = '�'; 
		$js_rus_unicode['%u0425'] = '�'; 
		$js_rus_unicode['%u042A'] = '�'; 
		$js_rus_unicode['%u0424'] = '�'; 
		$js_rus_unicode['%u042B'] = '�'; 
		$js_rus_unicode['%u0412'] = '�'; 
		$js_rus_unicode['%u0410'] = '�'; 
		$js_rus_unicode['%u041F'] = '�'; 
		$js_rus_unicode['%u0420'] = '�'; 
		$js_rus_unicode['%u041E'] = '�'; 
		$js_rus_unicode['%u041B'] = '�'; 
		$js_rus_unicode['%u0414'] = '�'; 
		$js_rus_unicode['%u0416'] = '�'; 
		$js_rus_unicode['%u042D'] = '�'; 
		$js_rus_unicode['%u042F'] = '�'; 
		$js_rus_unicode['%u0427'] = '�'; 
		$js_rus_unicode['%u0421'] = '�'; 
		$js_rus_unicode['%u041C'] = '�'; 
		$js_rus_unicode['%u0418'] = '�'; 
		$js_rus_unicode['%u0422'] = '�'; 
		$js_rus_unicode['%u042C'] = '�'; 
		$js_rus_unicode['%u0411'] = '�'; 
		$js_rus_unicode['%u042E'] = '�'; 
		 
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
		'�' => '%u2116',
		
		'�' => '%u0410', 
		'�' => '%u0411', 
		'�' => '%u0412', 
		'�' => '%u0413', 
		'�' => '%u0414', 
		'�' => '%u0415', 
		'�' => '%u0401', 
		'�' => '%u0416', 
		'�' => '%u0417', 
		'�' => '%u0418',
		'�' => '%u0419',
		'�' => '%u041A',
		'�' => '%u041B',
		'�' => '%u041C',
		'�' => '%u041D',
		'�' => '%u041E',
		'�' => '%u041F',
		'�' => '%u0420',
		'�' => '%u0421',
		'�' => '%u0422',
		'�' => '%u0423',
		'�' => '%u0424',
		'�' => '%u0425',
		'�' => '%u0426',
		'�' => '%u0427',
		'�' => '%u0428',
		'�' => '%u0429',
		'�' => '%u042A',
		'�' => '%u042B',
		'�' => '%u042C',
		'�' => '%u042D',
		'�' => '%u042E',
		'�' => '%u042F',

		'�' => '%u0430',
		'�' => '%u0431',
		'�' => '%u0432',
		'�' => '%u0433',
		'�' => '%u0434',
		'�' => '%u0435',
		'�' => '%u0451',
		'�' => '%u0436',
		'�' => '%u0437',
		'�' => '%u0438',
		'�' => '%u0439',
		'�' => '%u043A',
		'�' => '%u043B',
		'�' => '%u043C',
		'�' => '%u043D',
		'�' => '%u043E',
		'�' => '%u043F',
		'�' => '%u0440',
		'�' => '%u0441',
		'�' => '%u0442',
		'�' => '%u0443',
		'�' => '%u0444',
		'�' => '%u0445',
		'�' => '%u0446',
		'�' => '%u0447',
		'�' => '%u0448',
		'�' => '%u0449',
		'�' => '%u044A',
		'�' => '%u044B',
		'�' => '%u044C',
		'�' => '%u044D',
		'�' => '%u044E',
		'�' => '%u044F',
		);
		
		
		return strtr($str, $js_rus_unicode);
	}

	
	// ��� ����� ��������������
	function spr_selectfield($sql, $arrSetting, $TblSetting, $arrTableSpr, $key, $qChange, $TblName, $TblFieldPrimaryKey, $FirstId = "0", $width_field = 350){
		$return_var = "";
		$return_var.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;\">";
		$return_var.= "<select name='".$TblSetting[$key]['name']."' id='".$TblSetting[$key]['name']."' style='width: ".((int)$width_field - 29)."px;'>";
			$return_var.= "<option value='".$FirstId."' ".(($qChange[$TblSetting[$key]['name']] == $FirstId)?"selected":"")."></option>";
			$return_var.= spr_RootListSelect($sql, $arrTableSpr, $FirstId, "  -  ", $qChange[$TblSetting[$key]['name']]);
		$return_var.= "</select>"; 
		
		if($_GET[$TblFieldPrimaryKey] != 0 && $qChange[$TblSetting[$key]['name']] != $FirstId){
			$up_level_lnk = (isset($arrTableSpr["uplevel_link"]) && $arrTableSpr["uplevel_link"] != "")?$arrTableSpr["uplevel_link"]:"?tbl=".$TblName."&".$TblFieldPrimaryKey."=".$qChange[$TblSetting[$key]['name']]."&event=edit";
			$return_var.= " <a href='".$up_level_lnk."' title='�������'><img src='".$arrSetting['Path']['ico']."/uplevel.gif'/></a>";
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

	// ��� ����������� ��� �������� ������������� �� PrimaryKey
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
	
	
	//���� ������ ��� ���� � ����� directory_id
	// ���� � �������
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
			$return_var.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"X", "OnClick"=>"".$addJsFunc."('".$field_name."','');".$addJsFunc."('".$field_name."_show','');", "title"=>"��������"));
			$return_var.= frmInput(array("type"=>"button", "name"=>$AddButtonName, "id"=>$AddButtonName, "style"=>"width: 28px; float:right;", "value"=>"...", "class"=>"btn_add", "href"=>"".$WindowDataFile."","title"=>"������� �� �����������"));
		}
		$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_var.= "</div>";
		$return_var.= '<script type="text/javascript">$(function() {$("#'.$AddButtonName.'").nyroModal();});</script>';
		
		return($return_var);
	}
?>