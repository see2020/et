<?php
/**
 * inc.aj.cfg.window.function.php - окно выбора функций в настройках
 */
	
	$path_lnk = "aj.php?af=cfg.window.function".((isset($_GET["tbl"]))?"&tbl=".$_GET['tbl']:"").((isset($_GET["ret_field"]))?"&ret_field=".$_GET['ret_field']:"");

	include($arrSetting["Path"]["class"]."/a.charset.php");
	$TblNameDefault	 = (!isset($arrSetting['Table']['DefaultTable']) || $arrSetting['Table']['DefaultTable'] == "")?"":$arrSetting['Table']['DefaultTable'];
	$TblName		 = (isset($_GET['tbl']))?trim($_GET['tbl']):$TblNameDefault;
	$TblSetting		 = array();
	include(GetIncFile($arrSetting,"inc.tables.config.set.php", ""));
	
	$sql->sql_connect();

	$TblPathFunction = $arrSetting["Path"]["tbldata"]."/".$TblName."/tFunction";

	$nyroModal1	 = "";
	
	$show_window_content = "";
	$show_window_content.= spr_WindowSetHead($arrSetting);

	$return_var.= spr_windowSetTpl("<a href='".$path_lnk."' id='id_refr'><img src='".$arrSetting['Path']['ico']."/refresh.gif'/></a>", "ico");
	$return_var.= spr_windowSetTpl("<a href='".$path_lnk."&event=add_func' id='id_add_func'>Новая функция</a>", "max_block","left");
	
	$return_var.= spr_windowSetTpl("<a href='javascript:void(0);' OnClick=\"$.nmTop().close();\">Закрыть</a>", "min_block", "right");
	$return_var.= spr_windowSetTpl("<a href='".$path_lnk."' id='id_back'>В начало</a>", "min_block", "right");
	$show_window_content.= spr_windowSetTpl($return_var, "row");
	$show_window_content.= Message($TblPathFunction." <b>Выбранная функция: <a href='".$path_lnk."&event=add_func&f=".$_GET["now_func"]."' id='id_add_func_add_edit'>".$_GET["now_func"]."</a></b>");
	$nyroModal1.= '$("#id_add_func_add_edit").nyroModal();';
	
	if(isset($_GET["event"])){
		if($_GET["event"] == "add_func"){
			
			if(isset($_POST["f_name"]) && $_POST["f_name"] != ""){
				
				$f_name	 = trim($_POST["f_name"]);
				$f_con	 = charset_x_win($_POST["f_content"]);
				
				if(!$flc->fRewrite($TblPathFunction."/".$f_name,$f_con)){
					$show_window_content.= Message("Ошибка сохранения: ".$_POST["f_name"]."");
				}
				else{
					$show_window_content.= Message("Функция сохранена: ".$_POST["f_name"]."");
					$show_window_content.= Message("<a href='".$path_lnk."&event=add_func&f=".$f_name."' id='id_add_func_a_edit'>Продолжить редактирование</a>");
					$nyroModal1.= '$("#id_add_func_a_edit").nyroModal();';
				}
				$show_window_content.= Message("<a href='".$path_lnk."' id='id_back_a_edit'>к списку</a>");

				$nyroModal1.= '$("#id_back_a_edit").nyroModal();';
			}
			else{
				$show_window_content.= "<h3>Функция: ".$_GET["f"]."</h3>";
				
				$f_name	 = "";
				$f_con	 = "";
				
				if($_GET["f"] == ""){
					$f_name	 = $TblSetting["table"]["name"]."_".trim($_GET["ret_field"]).".php";
					$NewFuncCont = "";
					$NewFuncCont.= "<"."?php\r\n";
					$NewFuncCont.= "	// ".$ut->utGetDate("Y-m-d")."\r\n";
					$NewFuncCont.= "	// Table: ".$TblSetting["table"]["name"]."\r\n";
					$NewFuncCont.= "	// Function: ".$f_name."\r\n";
					$NewFuncCont.= "\r\n";
					$NewFuncCont.= '';
					$NewFuncCont.= '	// $_POST[$TblSetting[$key]["name"]]] = "" - для функций до/после сохранения сохранения';
					$NewFuncCont.= "\r";
					$NewFuncCont.= '	// $IdChange = "" - id редактируемой записи после сохранения сохранения';
					$NewFuncCont.= "\r";
					$NewFuncCont.= "	// \$query[\$TblSetting['имя поля']['свойство поля']] - текущее поле таблицы в списке таблицы";
					$NewFuncCont.= "\r";
					$NewFuncCont.= "	// \$query[\$TblSetting[\$key]['name']] - текущее поле таблицы в списке таблицы";
					$NewFuncCont.= "\r";
					$NewFuncCont.= "	// \$qChange[\$TblSetting[\$key]['name']] - текущее поле таблицы в форме редактирования";
					$NewFuncCont.= "\r\n";
					$NewFuncCont.= "\r\n";
					$NewFuncCont.= "\r\n";
					if($_GET["ret_field"] == "table_SelectFunction"){
						$NewFuncCont.= '
	// если лимит записей не установлен или это форма для печати то выводим все записи
	if($TblSetting["table"]["limit"] == 0 || $PrintPage){
		$result = $sql->sql_query("SELECT a.* FROM `".$sql->prefix_db.$TblSetting["table"]["name"]."` AS a ".$tblWhere." ".$tblOrder."");
	}
	else{
		$row_count	 = $sql->sql_rows($sql->sql_query("SELECT a.* FROM `".$sql->prefix_db.$TblSetting["table"]["name"]."` AS a ".$tblWhere." ".$tblOrder.""));
		$start		 = PageGetCount($pg,$row_count,$TblSetting["table"]["limit"]);
		$arrPLS		 = PageListShow($pg, $row_count, $TblSetting["table"]["limit"]);
		$result		 = $sql->sql_query("SELECT a.* FROM `".$sql->prefix_db.$TblSetting["table"]["name"]."` AS a ".$tblWhere." ".$tblOrder." LIMIT ".$start.",".$TblSetting["table"]["limit"]);
	}
						';
					}
					
					$NewFuncCont.= "	\$ShowRow = \" function result\"; // резултат работы";
					$NewFuncCont.= "\r\n";
					$NewFuncCont.= "?".">"."\r\n";
					$f_con = $NewFuncCont;
				}
				else{
					$f_name = $_GET["f"];
					$f_con = $flc->fGetContent($TblPathFunction."/".$_GET['f']);
				}

				$show_window_content.= "<form method='post' action='".$path_lnk."&event=add_func&f=".$f_name."' id='form_edit_window' style='padding: 0; margin: 0;'>";
				
				$show_window_content.= "Название:<br><input type='text' name='f_name' id='f_name' style='width: 100%; ' value='".$f_name."' /><br>";
				$show_window_content.= "Код:<br><textarea name='f_content' style='width:100%; height:400px;'>".$f_con."</textarea><br>";
				
				$show_window_content.= "<input type='submit' name='SaveEditFuncWindow' id='SaveEditFuncWindow' value='Сохранить'>";
				$show_window_content.= "</form>";
				$nyroModal1.= '$("#form_edit_window").nyroModal();';				
			}

		}
	}
	else{

		$flc->fListFiles($TblPathFunction,"",true);
		$tFPath = $flc->fListFiles;
		
		$show_window_content.= "<table class='tab_list'>";
		$show_window_content.= "<thead>";
		$show_window_content.= "<tr>";
		$show_window_content.= "<th>Функция</th>";
		$show_window_content.= "<th width='50'>&nbsp;</th>";
		$show_window_content.= "</tr>";
		$show_window_content.= "</thead>";
		$show_window_content.= "<tbody>";
		if(is_array($tFPath)){
			$num = 0;
			foreach($tFPath as $keyFunc => $valFunc){
				$show_window_content.= "<tr>";
				$show_window_content.= "<td>";
				$show_window_content.= "<a href='javascript:void(0);' title='".$valFunc['file']."' onclick='jsAddField(\"".$_GET['ret_field']."\",\"".$valFunc['file']."\");$.nmTop().close();'>".$valFunc['file']."</a>";
				$show_window_content.= "</td>";
				
				$show_window_content.= "<td><a href='".$path_lnk."&event=add_func&f=".$valFunc['file']."' id='id_edit_".$num."'><img src='".$arrSetting['Path']['ico']."/edit.gif'/></a></td>";
				$nyroModal1.= '$("#id_edit_'.$num.'").nyroModal();';
				
				$show_window_content.= "</tr>";
				$num++;
			}
		}
		$show_window_content.= "</tbody>";
		$show_window_content.= "</table>";		
	}
	
	$nyroModal = '$("#id_refr").nyroModal();$("#id_add_func").nyroModal();$("#id_back").nyroModal();'.$nyroModal1;
	$show_window_content.= spr_WindowSetFoot($arrSetting, $nyroModal);
	
	echo "<div style=\"width: 800px; height: 600px;\">";
	echo $show_window_content;
	echo "</div>";
?>