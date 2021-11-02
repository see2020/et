<?php
	//inc.aj.spr.autocomplete.php.php
	
	//$path_lnk = "spr_window.php?tmp=1";
	$path_lnk = "aj.php?af=spr.window&tmp=1";
	
	// $cmsPathRelative."/aj.php?af=spr.window".
	// "&selected_val=".spr_JsUrlEscape($qChange[$TblSetting[$key]['name']]).
	// "&field_type=".$TblSetting[$key]['type']. - тип поля поле текстовое изсправчника или связь по идентификатору
	// "&tbl_spr=".$TblSetting[$key]['directory_table']. - таблица справочника
	// "&irn=".$TblSetting[$key]['name'], - имя поля формы для возврата из окошка (если связь по id, то указываем идентификатор)

	//href="./aj.php?af=spr.window&selected_val=&field_type=directory_name&tbl_spr=changearea&irn=change_area"

	// $_GET['irn'] - имя поля формы для возврата из окошка (если связь по id, то указываем идентификатор)
	include($arrSetting["Path"]["class"]."/a.charset.php");
	$txt->txtSpaceRw = true;	

	$NyroId1 = "";
	$NyroId2 = "";

	//если вкл. множественный выбор
	$MultiSelect	 = 0;
	$MultiSelectSep	 = "";
	if(isset($_GET["ms"])){
		$MultiSelect	 = (int)$_GET["ms"];
		$MultiSelectSep	 = $_GET["mss"];
	}

	if($_GET["field_type"] != ""){
		$field_type = spr_JsUrlDecode($_GET["field_type"]);
		$path_lnk.="&field_type=".$field_type;
	}else{$field_type = "";} // - тип поля


	if($_GET["tbl_spr"] != ""){
		$tbl_spr_cfg = tblGetConfig($_GET["tbl_spr"],$arrSetting);
		if(!$tbl_spr_cfg["table"]['is_directory']){
			echo Message("Таблица: ".$_GET["tbl_spr"].", не является справочником!","error");
			exit;
		}
		$path_lnk.= "&tbl_spr=".$_GET['tbl_spr']."&irn=".$_GET['irn'];
	}
	else{
		echo Message("tbl_spr - Не определено","error");
		exit;
	}
	
	if(isset($_GET["selected_val"]) && $_GET["selected_val"] != ""){$selected_val = spr_JsUrlDecode($_GET["selected_val"]);}else{$selected_val = "";} // - то, что выбрано в данный момент

	$show_window_content = "";


	$SelectedCodeTablePrimaryKey = 0;
	// получение кода выбранного элемента
	if($tmp_code = spr_GetSelectedCode($sql, $tbl_spr_cfg, $selected_val)){
		// $_GET[$tbl_spr_cfg["table"]["PrimaryKey"]] = $tmp_code;
		$SelectedCodeTablePrimaryKey = $tmp_code;
	}
	
	if(isset($_GET[$tbl_spr_cfg["table"]['directory_root']]) && (int)$_GET[$tbl_spr_cfg["table"]['directory_root']] > 0){
		$SelectedCodeTablePrimaryKey = (int)$_GET[$tbl_spr_cfg["table"]['directory_root']];
	}
	
	$now_link = $path_lnk.((isset($SelectedCodeTablePrimaryKey) && $SelectedCodeTablePrimaryKey != 0)?"&".$tbl_spr_cfg["table"]["PrimaryKey"]."=".$SelectedCodeTablePrimaryKey:"");
	$add_link = $now_link.((isset($_GET["add"]))?"&add=".$_GET["add"]:"");
	
	//$path_lnk.="&tbl_spr=".$_GET['tbl_spr']."&irn=".$_GET['irn'];
	$return_var = "";
	if($tbl_spr_cfg["table"]["directory_NoAddInWindow"] != 0){}
	else{
		//if(usr_Access("edit")){
			$return_var.= spr_windowSetTpl("<a href='".$now_link."&add=yes' id='id_add_row' title='Добавить запись'><img src='".$arrSetting['Path']['ico']."/add.gif'/></a>", "ico");
		//}
	}
	
	$return_var.= spr_windowSetTpl(
	"<form method='post' action='".$path_lnk."' id='srch_form' style='padding: 0; margin: 0;'>".
	"<input type='text' name='field_srch' id='field_srch' style='width: 150px; ' value='".((isset($_POST["field_srch"]) && $_POST["field_srch"] != "")?charset_x_win($_POST["field_srch"]):"")."'  />".
	"<input type='submit' name='go_srch' id='go_srch' value='>>' style='width: 28px;'>".
	"</form>", "max_block","left");

	$return_var.= spr_windowSetTpl("<a href='".$path_lnk."' id='id_refr'><img src='".$arrSetting['Path']['ico']."/refresh.gif'/></a>", "ico");
	
	$return_var.= spr_windowSetTpl("<a href='javascript:void(0);' OnClick=\"$.nmTop().close();\">Закрыть</a>", "min_block", "right");
	$return_var.= spr_windowSetTpl("<a href='".$path_lnk."' id='id_back'>В начало</a>", "min_block", "right");
	$show_window_content.= spr_windowSetTpl($return_var, "row");

	$var_where		 = " ".$tbl_spr_cfg["table"]['directory_root']."='0' ";
	$id_selected_cat = "0";
	if($SelectedCodeTablePrimaryKey != 0){
		$id_selected_cat = $SelectedCodeTablePrimaryKey;
		$var_where = " ".$tbl_spr_cfg["table"]['directory_root']."='".$id_selected_cat."' ";
		
		if($field_type == "directory_id"){
			$nav_arr = spr_navigation_show_dir_id($sql,$tbl_spr_cfg, $path_lnk, array(),0,$id_selected_cat,"window");
		}
		else{
			$nav_arr = spr_navigation_show($sql,$tbl_spr_cfg, $path_lnk, array(),0,$id_selected_cat,"window");
		}
		$show_window_content.= spr_windowSetTpl($nav_arr["NavShow"],"nav");
		$NyroId2.= $nav_arr["WinId"];
	}

	
	$tmpPrefixName = "sw_";
	if(isset($_POST["dfghetserhdfjgj"]) && $_POST["dfghetserhdfjgj"] != ""){
		
		foreach($tbl_spr_cfg["sortfieldform"] as $key=>$val){
			if($tbl_spr_cfg[$key]['editable'] == 1 && $tbl_spr_cfg[$key]['type'] != 'support'){

				if($tbl_spr_cfg[$key]['type'] == "date"){
					$arr[$tbl_spr_cfg[$key]["name"]] = ($_POST[$tmpPrefixName.$tbl_spr_cfg[$key]["name"]] == "")?0:strtotime($_POST[$tmpPrefixName.$tbl_spr_cfg[$key]["name"]]);
				}
				elseif($tbl_spr_cfg[$key]['type']=='varbool'){$arr[$tbl_spr_cfg[$key]["name"]] = (isset($_POST[$tmpPrefixName.$tbl_spr_cfg[$key]["name"]]))?"1":"0";}
				else{
					if($_POST[$tmpPrefixName.$tbl_spr_cfg[$key]["name"]] == "" && $tbl_spr_cfg[$key]["default"] != ""){
						$_POST[$tmpPrefixName.$tbl_spr_cfg[$key]["name"]] = charset_x_win($tbl_spr_cfg[$key]["default"]);
					}
					$arr[$tbl_spr_cfg[$key]["name"]] = charset_x_win($_POST[$tmpPrefixName.$tbl_spr_cfg[$key]["name"]]);
				}
				$readonly_var = false;
			}
		}
		
		
		$ArrFV = $sql->sql_ExpandArr($arr);
		if($sql->sql_insert($tbl_spr_cfg["table"]['name'],$ArrFV['ListField'],$ArrFV['ListValue'])){$ut->utLog(__FILE__ . " - запись сохранена");}else{$ut->utLog(__FILE__ . " - ошибка сохранения записи");}
		//$sql->sql_insertLastId
		
		if($field_type == "directory_id"){
			$show_window_content.= Message("Добавлена запись: 
			<a href='#' OnClick=\"
					jsAddField(
					'".$_GET['irn']."',
					'".$sql->sql_insertLastId."'
					);
					jsAddField(
					'".$_GET['irn']."_show"."',
					'".spr_get_element_nav($sql, $tbl_spr_cfg, $sql->sql_insertLastId)."'
					);$.nmTop().close();\">".$arr[$tbl_spr_cfg["table"]['directory_name']]."</a>");
		}
		else{
			$show_window_content.= Message("Добавлена запись: 
			<a href='#' OnClick=\"
					jsAddField(
					'".$_GET['irn']."',
					'".$arr[$tbl_spr_cfg["table"]['directory_name']]."'
					);$.nmTop().close();\">".$arr[$tbl_spr_cfg["table"]['directory_name']]."</a>");
		}
		unset($_POST["dfghetserhdfjgj"]);
	}

	if(isset($_GET["add"])){
	
// $tbl_spr_cfg = tblGetConfig($_GET["tbl_spr"],$arrSetting);
// echo  Message("name: ".$tbl_spr_cfg["table"]["name"],"error");
// echo  Message("is_directory: ".$tbl_spr_cfg["table"]["is_directory"],"error");
// echo  Message(ParseArrForLog($tbl_spr_cfg["table"]),"error");
// exit;

$return_var = "";

function spr_RootListSelect123456($sql, $arrTableSpr, $id_root = "", $sep = "-", $selected_id = ""){
	$return_var = "";
	$SelectVar = "SELECT * FROM `".$sql->prefix_db.$arrTableSpr["table"]["name"]."` 
	WHERE `".$arrTableSpr["table"]["directory_root"]."`='".$id_root."' 
	AND ".$arrTableSpr["table"]["StatusField"]."='1' 
	AND ".$arrTableSpr["table"]["directory_type"]."='1'";
	$result = $sql->sql_query($SelectVar);
	if($sql->sql_rows($result)){
		while($query = $sql->sql_array($result)){
			$return_var.= "<option value='".$query[$arrTableSpr["table"]["PrimaryKey"]]."' ".(($arrTableSpr["table"]["PrimaryKey"] == $selected_id)?"selected":"").">".$sep.$query[$arrTableSpr["table"]["directory_name"]]."</option>";
			$return_var.= spr_RootListSelect123456($sql, $arrTableSpr, $query[$arrTableSpr["table"]["PrimaryKey"]], $sep.$sep,$selected_id);
		}
	}
	return($return_var);
}
function frmInputDateAdd123456($arrTableSpr, $field_name_real = "", $field_name = "", $field_value = "", $attributes = array(),$width_field = "350",$ro = false){
	if($field_name == ""){return(false);}

	$tmp_dt = date($arrTableSpr[$field_name_real]['dateformat'],$field_value);
	if(is_numeric($field_value)){
		if($field_value == 0){
			//$tmp_dt = "";
			$tmp_dt = date($arrTableSpr[$field_name_real]['dateformat']);
		}
	}
	//$tmp_dt = $field_name_real;
	if(!$ro){
		$attr = array("type" => "text", "name" => $field_name, "value" => $tmp_dt,"style"=>"width: ".((int)$width_field - 22)."px; ",);
	}
	else{
		$attr = array("type" => "text", "name" => $field_name, "value" => $tmp_dt,"style"=>"width: ".((int)$width_field - 22)."px; ","readonly"=>"readonly",);
	}
	$attributes = $attr + $attributes;
/*
	$dt_format = strtr($arrTableSpr[$field_name_real]['dateformat'],array(
					"Y"=>"%Y", "m"=>"%m", "d"=>"%d", 
					"H"=>"%H", "h"=>"%h", "i"=>"%M", "s"=>"%S",
					)
			);
	//ifFormat: \"%Y-%m-%d %H:%M:00\", 
	$tmp_var = "<script type=\"text/javascript\">
		jQuery(document).ready(function() {
			jQuery(\"#".$field_name."\").dynDateTime({
				showsTime: false,
				ifFormat: \"".$dt_format."\", 
				align: \"BL\",
				electric: false,
				singleClick: true,
				firstDay: 1,
				button: \".next()\" //next sibling
			});
		});
	</script>";
*/
	$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
	
	$return_var = "";
	$return_var.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
	$return_var.= frmInput(SetAttributes($attributes));
/*	
	if(!$ro){
		$return_var.= "<input type='image' src='./components/ico/calendar.gif' style='margin-bottom:-5px;' />";
		$return_var.= $tmp_var;
	}
*/
	$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
	$return_var.= "</div>";
	return($return_var);
}


//ob_start();
$return_var.= "<form method='post' action='".$now_link."' id='form_edit_window' style='padding: 0; margin: 0;'>";
$return_var.= "<input type='hidden' name='dfghetserhdfjgj' id='dfghetserhdfjgj' value='qqqqqqqq'  />";
$width_field_def	 = (isset($tbl_spr_cfg["table"]["FormFieldWidth"]))?$tbl_spr_cfg["table"]["FormFieldWidth"]:500;
$height_field_def	 = (isset($tbl_spr_cfg["table"]["FormFieldHeight"]))?$tbl_spr_cfg["table"]["FormFieldHeight"]:100;
$readonly_var		 = false;
foreach($tbl_spr_cfg["sortfieldform"] as $key=>$val){
	if($tbl_spr_cfg[$key]['editable'] == 1 && $tbl_spr_cfg[$key]['type'] != 'support'){

		$nm_field = frmGetName($tbl_spr_cfg,$key);
		
		// если запись новая, то разрешаем редактировать поля только для чтения
		//if($_GET[$TblFieldPrimaryKey] !=0 ){$readonly_var = ($tbl_spr_cfg[$key]['readonly'] == 1)?true:false;}
		
		// если запись чем то заполнена
		// if($tbl_spr_cfg[$key]['type'] == "number"){if($qChange[$tbl_spr_cfg[$key]['name']] != 0){$readonly_var = ($tbl_spr_cfg[$key]['readonly'] == 1)?true:false;}}
		// else{if($qChange[$tbl_spr_cfg[$key]['name']] != ""){$readonly_var = ($tbl_spr_cfg[$key]['readonly'] == 1)?true:false;}}
		
		$width_field	 = (isset($tbl_spr_cfg[$key]["FormFieldWidth"]))?$tbl_spr_cfg[$key]["FormFieldWidth"]:$width_field_def;
		$height_field	 = (isset($tbl_spr_cfg[$key]["FormFieldHeight"]))?$tbl_spr_cfg[$key]["FormFieldHeight"]:$height_field_def;
		
		// для перехода не предыдущий уровень 
		if($tbl_spr_cfg[$key]['name'] == $tbl_spr_cfg["table"]['directory_root']){
			if($tbl_spr_cfg["table"]['is_directory'] == "1"){
				$return_var.= "<span class='field_name'>".$nm_field.":</span><br>"; 
				$return_var.= "<select name='".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."' id='".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."' style='width: ".((int)$width_field - 29)."px;'>";
				$return_var.= "<option value='0' selected></option>";
				$return_var.= spr_RootListSelect123456($sql, $tbl_spr_cfg, 0, "  -  ", $id_selected_cat);
				$return_var.= "</select>"; 
			}
			else{
				$return_var.= "<span class='field_name'>".$nm_field.":</span><br>";
				$return_var.= frmInputText($tmpPrefixName.$tbl_spr_cfg[$key]['name'], $tbl_spr_cfg[$key]['default'], array("id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'],),$width_field,$readonly_var);
			}
			$return_var.= "<br>";
		}
		elseif($tbl_spr_cfg[$key]['type'] == "number"){
			$return_var.= "<span class='field_name'>".$nm_field.":</span><br>";
			$st_all = "margin: 0;padding: 0;";
			$number_var = "";
			$number_var.= "<div class=\"sel_field\" style=\"".$st_all."width: ".$width_field."px;\">";
			if(!$readonly_var){
				$number_var.= frmInput(array("type"=>"text", "name"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'], "style"=>"width: ".((int)$width_field - 56 - 56 - 56 - 28)."px; ", "value"=>(int)$tbl_spr_cfg[$key]['default'],"id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'], ));
				$number_var.= frmInput(array("type" => "button", "value" => "+100", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value = parseInt(document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value) + 100;",));
				$number_var.= frmInput(array("type" => "button", "value" => "+10", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value = parseInt(document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value) + 10;",));
				$number_var.= frmInput(array("type" => "button", "value" => "+", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value = parseInt(document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value) + 1;",));
				$number_var.= frmInput(array("type" => "button", "value" => "0", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value = 0;",));
				$number_var.= frmInput(array("type" => "button", "value" => "-", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value = parseInt(document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value) - 1;",));
				$number_var.= frmInput(array("type" => "button", "value" => "-10", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value = parseInt(document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value) - 10;",));
				$number_var.= frmInput(array("type" => "button", "value" => "-100", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value = parseInt(document.getElementById('".$tmpPrefixName.$tbl_spr_cfg[$key]['name']."').value) - 100;",));
			}
			else{
				$number_var.= frmInput(array("type"=>"text", "name"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'], "readonly"=>"readonly", "style"=>"".$st_all."width: ".((int)$width_field - 56 - 56 - 56)."px; ", "value"=>0,"id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'], ));							
			}
			$number_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
			$number_var.= "</div>";
			$return_var.= $number_var;
			$number_var = "";
			$return_var.= "<br>";
		}
		elseif($tbl_spr_cfg[$key]['type'] == "selectarea"){
			$return_var.= "<span class='field_name'>".$nm_field.":</span><br>";
			if($tbl_spr_cfg[$key]['directory_table'] != ""){
				$tmp_arr_td = spr_GetArrTypeData($sql, $arrSetting, $tbl_spr_cfg, $key);
			}
			else{
				$tmp_arr_td = GetArrTypeData($tbl_spr_cfg[$key]['type_data']);
			}
			$return_var.= frmSelect($tmpPrefixName.$tbl_spr_cfg[$key]['name'], $tmp_arr_td, $tbl_spr_cfg[$key]['default'], array("id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'],"style"=>"width: ".$width_field."px;",));
			unset($tmp_arr_td);
			$return_var.= "<br>";
		}
		elseif($tbl_spr_cfg[$key]['type'] == "varbool"){
			$return_var.= "<span class='field_name'>".$nm_field.": </span>";
			$return_var.= frmInputCheckbox($tmpPrefixName.$tbl_spr_cfg[$key]['name'], $tbl_spr_cfg[$key]['default'], array("id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'],));
			$return_var.= "<br>";
		}
		elseif($tbl_spr_cfg[$key]['type'] == "password"){
			$return_var.= "<span class='field_name'>".$nm_field.":</span><br>";
			$return_var.= frmInputPassword($tmpPrefixName.$tbl_spr_cfg[$key]['name'], $tbl_spr_cfg[$key]['default'], array("id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'],),$width_field,$readonly_var);
			$return_var.= "<br>";
		}
		elseif($tbl_spr_cfg[$key]['type'] == "date"){
			$return_var.= "<span class='field_name'>".$nm_field.":</span><br>";
			$return_var.= frmInputDateAdd123456($tbl_spr_cfg, $tbl_spr_cfg[$key]['name'], $tmpPrefixName.$tbl_spr_cfg[$key]['name'], (int)$tbl_spr_cfg[$key]['default'], array("id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'],),$width_field,$readonly_var);
			$return_var.= "<br>";
		}
		elseif($tbl_spr_cfg[$key]['type'] == "radiobutton"){
			$return_var.= "<span class='field_name'>".$nm_field.": </span>";
			if($tbl_spr_cfg[$key]['directory_table'] != ""){
				$tmp_arr_td = spr_GetArrTypeData($sql, $arrSetting, $tbl_spr_cfg, $key);
			}
			else{
				$tmp_arr_td = GetArrTypeData($tbl_spr_cfg[$key]['type_data']);
			}
			$return_var.= frmRadio($tmpPrefixName.$tbl_spr_cfg[$key]['name'], $tmp_arr_td, $tbl_spr_cfg[$key]['default'], array());
			unset($tmp_arr_td);
			$return_var.= "<br>";
		}
		elseif($tbl_spr_cfg[$key]['type'] == "textarea"){
			$return_var.= "<span class='field_name'>".$nm_field.":</span><br>";
			$return_var.= frmTextarea($tmpPrefixName.$tbl_spr_cfg[$key]['name'], $tbl_spr_cfg[$key]['default'], array("id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'],"style"=>"width: ".$width_field."px; height:".$height_field."px;",),$readonly_var);
			$return_var.= "<br>";
		}
		else{
			$return_var.= "<span class='field_name'>".$nm_field.":</span><br>";
			$return_var.= frmInputText($tmpPrefixName.$tbl_spr_cfg[$key]['name'], $tbl_spr_cfg[$key]['default'], array("id"=>$tmpPrefixName.$tbl_spr_cfg[$key]['name'],),$width_field,$readonly_var);
			$return_var.= "<br>";
		}
		$readonly_var = false;
		
	}
}
$return_var.= frmInput(array("type"=>"submit","name"=>"SaveEditRowWindow","id"=>"SaveEditRowWindow","value"=>"Сохранить","title"=>"",));
$return_var.= "</form>";

		$show_window_content.= $return_var;
	}
	else{
		if(isset($_POST["field_srch"]) && $_POST["field_srch"] != ""){$var_where = " ".$tbl_spr_cfg["table"]['directory_name']." LIKE '%".trim(charset_x_win($_POST["field_srch"]))."%' ";}		
		
		if($field_type == "directory_id"){
			$spr_list_arr = spr_list_dir_id_1($sql, $arrSetting, $tbl_spr_cfg, $path_lnk, $var_where);
		}
		else{
			if(!$MultiSelect){
				$spr_list_arr = spr_list_text($sql, $arrSetting, $tbl_spr_cfg, $path_lnk, $var_where);
			}
			else{
				$spr_list_arr = spr_list_text_ms($sql, $arrSetting, $tbl_spr_cfg, $path_lnk, $var_where, $MultiSelectSep);
			}
		}
		$show_window_content.= $spr_list_arr["list"];
		$NyroId1 = $spr_list_arr["WinNMId"];		
	}
	
	$nyroModal = '$("#id_add_row").nyroModal();$("#form_edit_window").nyroModal();
	$("#srch_form").nyroModal();$("#id_refr").nyroModal();$("#id_back").nyroModal();'.$NyroId1.$NyroId2;
	$show_window_content.= spr_WindowSetFoot($arrSetting, $nyroModal);
	
	echo "<div style=\"width: 800px;\">";
	echo $show_window_content;
	echo "</div>";
?>