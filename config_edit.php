<?php
session_start();
$cmsPathRelative = ".";
include($cmsPathRelative."/config.php");

if($arrSetting['Access']['UsePassword']){
	if(usr_Access("admin")){$u_access = true;}
	else{
		echo Message("Недостаточно прав на изменение этого раздела", "error");
		$u_access = false;
	}
}
else{
	$u_access = true;
}

if($u_access){
	$this_url = "config_edit.php";
	$sql->sql_connect();
	
	$TblList = array();
	$TblDefTplPath = $arrSetting['Path']['tpl']."/".$arrSetting['Table']['DefaultTpl'];
	$TblSetting["table"]['name'] = "";
	$TblSetting["table"]['ico'] = ""; //$arrSetting['Path']['ico']."/album.gif";
	$TblSetting["table"]['description'] = "Редактирование";
	if(file_exists($TblDefTplPath."/config.top.php")){include($TblDefTplPath."/config.top.php");}
	
	$tmp_style = "style='font-size:14px; text-decoration: none;'";
	
	function TestAlias($str = ""){
		if($str == ""){return($str);}
		$str = strip_tags($str);
		$str = preg_replace("/[^a-zA-Z0-9\_\/\.\s]/","",$str);
		$str = preg_replace("/ {2,}/", " ", $str);
		$str = trim($str);
		$str = strtr($str,array(" "=>"-",));
		return($str);
	}
	
	function GetConfig($arrSetting,$cfg_str){
		$arrConfig["table"]["description"] = "";
		$config_file = $arrSetting["Path"]["data"]."/".$cfg_str.".php";
		if(file_exists($config_file)){include($config_file);}	
		return($arrConfig);
	}
	
	$tmpTableDataPath = str_replace($cmsPathRelative."/_data_files/","",$arrSetting['Path']['tbldata']);
	
	if(!isset($_GET['cfg'])){

		if(!isset($_GET['action'])){
			echo "<div style='float: left;width: 48%;margin: 0 0.9% 0 0.9%;padding: 0;'>";
			if($result1 = $sql->sql_ShowTableFromBD()){
				foreach($result1 as $key => $t_name){
					$tName = str_replace($sql->prefix_db, "", $t_name);
					
					$arr_tmp_cfg = GetConfig($arrSetting,$tmpTableDataPath."/".$tName."/".$tName);
		
					echo Message("<p>
						<span ".$tmp_style.">Настройка таблицы: </span>
						<a href='".$this_url."?cfg=".$tmpTableDataPath."/".$tName."/".$tName."'><strong ".$tmp_style.">[".$tName."]</strong></a>
						".(($arr_tmp_cfg["table"]["description"] != "")?"<br><span class='sektion_description'>".$arr_tmp_cfg["table"]["description"]."</span>":"")."
					</p>");
				}		
			}
			echo "</div>";
			
			echo "<div style='float: left;width: 48%;margin: 0 0.9% 0 0.9%;padding: 0;'>";
			if(usr_Access("root")){
				
				echo Message("<p><a href='".$this_url."?cfg=config' ".$tmp_style.">Основные настройки</a></p>");
				//echo Message("<p><a href='".$this_url."?cfg=".substr($arrSetting['Path']['tbldata'],1)."/tList' ".$tmp_style.">Настройка списка таблиц</a></p>");
				//echo Message("<p><a href='".$this_url."?cfg=".$tmpTableDataPath."/tList' ".$tmp_style.">Настройка списка таблиц</a></p>");
				echo Message("<p><a href='".$this_url."?cfg=lang' ".$tmp_style.">Настройка названий полей</a></p>");

				
				echo Message("<p><a href='javascript:void(0);'  ".$tmp_style." onclick=\"
				if(confirm('Будет выполнено обновление настроек таблиц.\\r\\nБудут созданы копии настроек.\\r\\nЕсли будут найдены новые таблицы, для них будут созданы настройки и добавлен пункт меню\\r\\n\\r\\nПродолжить?')){
					location = '".$this_url."?action=configcorrection'
				}\">Автонастройка</a></p>");

				// echo Message("<p><a href='".$this_url."' ".$tmp_style.">Настройка меню</a></p>");
				// echo Message("<p><a href='".$this_url."' ".$tmp_style.">Пользователи</a></p>");
				echo "</div>";
				
				echo "<div style='float: left;width: 48%;margin: 0 0.9% 0 0.9%;padding: 0;'>";
				echo Message("<p><a href='javascript:void(0);' ".$tmp_style." onclick=\"
				if(confirm('Будет выполнено обновление настроек таблиц.\\r\\nБудут созданы копии настроек.\\r\\nБудут перенумерованы настройки отвечающие за порядок вывода полей\\r\\n\\r\\nПродолжить?')){
					location = '".$this_url."?action=sortcorrection'
				}\">Автонастройка порядковых полей таблиц в настройках</a></p>");
			}
			echo "</div>";
			echo "<div style='clear:both;'></div>";
		}
		else{
			if($_GET['action']=="configcorrection"){
			
				function GetConfigArray($file_config){}
				
				//$arrConfig['Path']['tbldata'] = '/_data_files/tbldata';
				///_data_files/tbldata/maintbl/maintbl.php
				// $arrConfig['Path']['data'] = '/_data_files';
				// $arrConfig['Path']['tbldata'] = $arrConfig['Path']['data'].'/tbldata';
				
				//./_data_files/tbldata/tst_maintbl/tst_maintbl.php
				// \_data_files\tbldata\maintbl\maintbl.php
				
				include($arrSetting["Path"]["data"]."/config.php");

				// шаблон настроек таблицы
				$arrTableTpl = tblTableTpl();	
				
				// шаблон настроек полей
				$arrFieldTpl = tblFieldTpl();
			
				// получаем список всех таблиц
				if($arrListTable = $sql->sql_ShowTableFromBD()){
					
					// делаем копии файлов настроек, если их нет то создаем
					foreach($arrListTable AS $key => $t_name){
						$tName = str_replace($sql->prefix_db, "", $t_name);
						$config_file_name = $tName.".php";
						$config_file_path = $arrSetting['Path']['tbldata']."/".$tName;
						
						// если неастроек нет, делаем первичное заполнение
						if(!is_dir($config_file_path)){
							@mkdir($config_file_path, 0777);
							$cfg_new = new config($config_file_path."/".$config_file_name);
							$cfg_new->init();
							unset($cfg_new);
							
							// создаем папки для хранения данных настроек
							$arrTblPath = array("form"=>"tForm","function"=>"tFunction","theme"=>"tThemeField",);
							foreach($arrTblPath as $keyPath => $valPath){
								$atp = $config_file_path."/".$valPath;
								if(!is_dir($atp)){@mkdir($atp, 0777);}
							}
							//unset($arrTblPath);
							// если таблца новая, добавляем эту таблицу в "меню"
							$ArrNewTable["id_root"]			 = "0";
							$ArrNewTable["tbl_name"]		 = $tName;
							$ArrNewTable["title"]			 = "";
							$ArrNewTable["description"]		 = "";
							$ArrNewTable["is_separator"]	 = "0";
							$ArrNewTable["tbl_ico"]			 = "";
							$ArrNewTable["lnk"]				 = "";
							$ArrNewTable["lnk_blank"]		 = "0";
							//$ArrNewTable["position"]		 = "max(position)+10";
							$ArrNewTable["position"]		 = "0";
							$resultNewTable = $sql->sql_query("SELECT max(position) AS max_position FROM ".$sql->prefix_db."tblmenu");
							if($sql->sql_rows($resultNewTable)){
								$queryNewTable = $sql->sql_array($resultNewTable);
								$ArrNewTable["position"] = $queryNewTable["max_position"] + 10;
							}
							$ArrNewTable["type_row"]		 = "0";
							$ArrNewTable["u_access"]		 = "read";
							$ArrNewTable["show_submenu"]	 = "0";
							$ArrNewTable["st"]				 = "1";
							$ArrFVNewTable = $sql->sql_ExpandArr($ArrNewTable);
							$sql->sql_insert("tblmenu",$ArrFVNewTable['ListField'],$ArrFVNewTable['ListValue']);
							// $IdNewTable = $sql->sql_insertLastId;
							
							echo Message("<p ".$tmp_style."><strong ".$tmp_style.">Новый файл настроек:</strong> ".$config_file_path."/".$config_file_name."<p>");
						}
						else{
							//копия иземееняемого файла с настройками
							$FNameArr = $flc->fFileName($config_file_path."/",$config_file_name);
							if(!copy($config_file_path."/".$config_file_name, $config_file_path."/".$FNameArr['name'])){
								echo Message("<p ".$tmp_style.">Ошибка копирования файла: ".$config_file_path."/".$config_file_name." >> ".$config_file_path."/".$FNameArr['name']."<p>","error");
								$ut->utLog(__FILE__ . " - ошибка копирования файла: ".$config_file_path."/".$config_file_name." >> ".$config_file_path."/".$FNameArr['name']."<p>");
							}
							else{
								echo Message("<p ".$tmp_style.">Выполнено копирование файла настроек: ".$config_file_path."/".$config_file_name." >> ".$config_file_path."/".$FNameArr['name']."<p>");
							}	
						}

					}

					// перенумерование таблцы "tblmenu"
					// $ReNumStart	 = 20;
					// $ReNum		 = $ReNumStart;
					// $resultReNum = $sql->sql_query("SELECT * FROM ".$sql->prefix_db."tblmenu ORDER BY position ASC");
					// if($sql->sql_rows($resultReNum)){
						// while($queryRenum = $sql->sql_array($resultReNum)){
							// $sql->sql_update("tblmenu","position='".$ReNum."'","`id`='".$queryRenum["id"]."'");
							// $ReNum = $ReNum + $ReNumStart;
						// }
					// }
					
					// проверка ключей настроек если что-то отсутсвует, добавляем
					foreach($arrListTable AS $key => $t_name){
						$tName		 = str_replace($sql->prefix_db, "", $t_name);
						$config_file = $arrSetting['Path']['tbldata']."/".$tName."/".$tName.".php";
						
						$cfg_edit = new config($config_file);
						$cfg_edit->init();
						// поверяем общие настройки
						$TblPrimaryKey	 = $sql->sql_GetPrimaryKey($t_name);
						$tmpSection		 = "table";
						foreach($arrTableTpl AS $TblDefKey => $TblDefVal){
							if(!$cfg_edit->ElementExist($tmpSection, $TblDefKey)){
								$cfg_edit->set($tmpSection, $TblDefKey, $TblDefVal);
								if($TblDefKey == "name"){
									$cfg_edit->set($tmpSection, $TblDefKey, $tName);
								}
								if($TblDefKey == "PrimaryKey"){
									$cfg_edit->set($tmpSection, $TblDefKey, $TblPrimaryKey);
								}
								if($TblDefKey == "limit"){
									$cfg_edit->set($tmpSection, $TblDefKey, $arrSetting["List"]['RowCountToPage']);
								}
								
								echo Message("<p ".$tmp_style."><strong>".$tName." -</strong> добавлен новый ключ: [".$tmpSection."][".$TblDefKey."]=".$TblDefVal."</p>","");
							}
						}
						unset($tmpSection);
						
						// настройки сортировочных полей
						if(!$arrFields = $sql->sql_GetFieldFromTable($t_name)){
							echo Message("<p ".$tmp_style.">Ошибка ошибка получения полей таблицы: ".$t_name."</p>","error");
						}
						$tmpSection		 = "sortfield"; // порядок вывода полей в списке
						$tmpSection2	 = "sortfieldform"; // порядок вывода полей в форме для редактирования
						$tmpSection3	 = "sortfieldsearch"; // порядок вывода полей в форме для поиска
						$srt_num		 = 30;
						if(!$cfg_edit->ElementExist($tmpSection, "f_edit")){
							$cfg_edit->set($tmpSection, "f_edit", $srt_num - 20);
						}
						if(!$cfg_edit->ElementExist($tmpSection, "f_copy")){
							$cfg_edit->set($tmpSection, "f_copy", $srt_num - 10);
						}
						foreach($arrFields AS $FieldName){
							if(!$cfg_edit->ElementExist($tmpSection, $FieldName)){
								$cfg_edit->set($tmpSection, $FieldName, $srt_num);
							}
							if(!$cfg_edit->ElementExist($tmpSection2, $FieldName)){
								$cfg_edit->set($tmpSection2, $FieldName, $srt_num);
							}
							if(!$cfg_edit->ElementExist($tmpSection3, $FieldName)){
								$cfg_edit->set($tmpSection3, $FieldName, $srt_num);
							}
							$srt_num = $srt_num + 10;
						}
						if(!$cfg_edit->ElementExist($tmpSection, "f_del")){
							$cfg_edit->set($tmpSection, "f_del", $srt_num + 20);
						}
						unset($tmpSection, $tmpSection2, $tmpSection3);
						
						// дополнительное поле для кнопки редактирования
						$tmpSection = "f_edit";
						foreach($arrFieldTpl AS $FieldKey => $FieldVal){
							if(!$cfg_edit->ElementExist($tmpSection, $FieldKey)){
								$cfg_edit->set($tmpSection, $FieldKey, $FieldVal);
								if($FieldKey == "name"){$cfg_edit->set($tmpSection, $FieldKey, $tmpSection);}
								if($FieldKey == "visible"){$cfg_edit->set($tmpSection, $FieldKey, "1");}
								if($FieldKey == "editable"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "for_search"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "forprint"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "description"){$cfg_edit->set($tmpSection, $FieldKey, "edit");}
								if($FieldKey == "width"){$cfg_edit->set($tmpSection, $FieldKey, "25");}
								if($FieldKey == "type"){$cfg_edit->set($tmpSection, $FieldKey, "support");}
								if($FieldKey == "image"){$cfg_edit->set($tmpSection, $FieldKey, "edit.gif");}
								if($FieldKey == "link_edit"){$cfg_edit->set($tmpSection, $FieldKey, "1");}
								if($FieldKey == "link_image"){$cfg_edit->set($tmpSection, $FieldKey, "1");}
								if($FieldKey == "link_newwindow"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								echo Message("<p ".$tmp_style."><strong>".$tName." -</strong> добавлен новый ключ: [".$tmpSection."][".$FieldKey."]=".$cfg_edit->get($tmpSection, $FieldKey)."</p>","");
							}
						}
						unset($tmpSection);
						
						// дополнительное поле для кнопки копии
						$tmpSection = "f_copy";
						foreach($arrFieldTpl AS $FieldKey => $FieldVal){
							if(!$cfg_edit->ElementExist($tmpSection, $FieldKey)){
								$cfg_edit->set($tmpSection, $FieldKey, $FieldVal);
								if($FieldKey == "name"){$cfg_edit->set($tmpSection, $FieldKey, $tmpSection);}
								if($FieldKey == "visible"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "editable"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "for_search"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "forprint"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "description"){$cfg_edit->set($tmpSection, $FieldKey, "copy");}
								if($FieldKey == "width"){$cfg_edit->set($tmpSection, $FieldKey, "25");}
								if($FieldKey == "type"){$cfg_edit->set($tmpSection, $FieldKey, "support");}
								if($FieldKey == "image"){$cfg_edit->set($tmpSection, $FieldKey, "edit_copy.gif");}
								if($FieldKey == "link_edit"){$cfg_edit->set($tmpSection, $FieldKey, "1");}
								if($FieldKey == "link_image"){$cfg_edit->set($tmpSection, $FieldKey, "1");}
								if($FieldKey == "link_newwindow"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								echo Message("<p ".$tmp_style."><strong>".$tName." -</strong> добавлен новый ключ: [".$tmpSection."][".$FieldKey."]=".$cfg_edit->get($tmpSection, $FieldKey)."</p>","");
							}
						}
						unset($tmpSection);
			
						//настройки для каждого поля таблицы
						foreach($arrFields AS $FieldName){
							$tmpSection = $FieldName;
							foreach($arrFieldTpl AS $FieldKey => $FieldVal){
								if(!$cfg_edit->ElementExist($tmpSection, $FieldKey)){
									$cfg_edit->set($tmpSection, $FieldKey, $FieldVal);
									if($FieldKey == "name"){$cfg_edit->set($tmpSection, $FieldKey, $tmpSection);}
									echo Message("<p ".$tmp_style."><strong>".$tName." -</strong> добавлен новый ключ: [".$tmpSection."][".$FieldKey."]=".$cfg_edit->get($tmpSection, $FieldKey)."</p>","");
								}
							}
							unset($tmpSection);
						}
						
						// дополнительное поле для кнопки удаления
						$tmpSection = "f_del";
						foreach($arrFieldTpl AS $FieldKey => $FieldVal){
							if(!$cfg_edit->ElementExist($tmpSection, $FieldKey)){
								$cfg_edit->set($tmpSection, $FieldKey, $FieldVal);
								if($FieldKey == "name"){$cfg_edit->set($tmpSection, $FieldKey, $tmpSection);}
								if($FieldKey == "visible"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "editable"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "for_search"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "forprint"){$cfg_edit->set($tmpSection, $FieldKey, "0");}
								if($FieldKey == "description"){$cfg_edit->set($tmpSection, $FieldKey, "del");}
								if($FieldKey == "width"){$cfg_edit->set($tmpSection, $FieldKey, "25");}
								if($FieldKey == "type"){$cfg_edit->set($tmpSection, $FieldKey, "support");}
								if($FieldKey == "image"){$cfg_edit->set($tmpSection, $FieldKey, "list-delete.gif");}
								if($FieldKey == "link_edit"){$cfg_edit->set($tmpSection, $FieldKey, "1");}
								if($FieldKey == "link_image"){$cfg_edit->set($tmpSection, $FieldKey, "1");}
								if($FieldKey == "link_newwindow"){$cfg_edit->set($tmpSection, $FieldKey, "0");}

								echo Message("<p ".$tmp_style."><strong>".$tName." -</strong> добавлен новый ключ: [".$tmpSection."][".$FieldKey."]=".$cfg_edit->get($tmpSection, $FieldKey)."</p>","");
							}
						}
						unset($tmpSection);

						for($i = 4; $i <= $arrSetting['Other']['TabCount']; $i++){
							$tmpSection		 = "FormEditTab_".$i;
							$tmpKey			 = "TabUse";
							if(!$cfg_edit->ElementExist($tmpSection, $tmpKey)){$cfg_edit->set($tmpSection, $tmpKey, 0);}
							$tmpKey			 = "TabName";
							if(!$cfg_edit->ElementExist($tmpSection, $tmpKey)){$cfg_edit->set($tmpSection, $tmpKey, "");}
							$tmpKey			 = "TabFunction";
							if(!$cfg_edit->ElementExist($tmpSection, $tmpKey)){$cfg_edit->set($tmpSection, $tmpKey, "");}
							unset($tmpSection, $tmpKey);
						}
						unset($tmpSection);
						$cfg_edit->upd();
						unset($cfg_edit);
					}
				
				
				
				}
			}
			
			if($_GET['action']=="sortcorrection"){
				
				include($arrSetting["Path"]["data"]."/config.php");

				// получаем список всех таблиц
				if($arrListTable = $sql->sql_ShowTableFromBD()){
					
					// делаем копии файлов настроек, если их нет то создаем
					foreach($arrListTable AS $key => $t_name){
						$tName = str_replace($sql->prefix_db, "", $t_name);
						$config_file_name = $tName.".php";
						$config_file_path = $arrSetting['Path']['tbldata']."/".$tName;
						
						if(is_dir($config_file_path)){
							//копия иземееняемого файла с настройками
							$FNameArr = $flc->fFileName($config_file_path."/",$config_file_name);
							if(!copy($config_file_path."/".$config_file_name, $config_file_path."/".$FNameArr['name'])){
								echo Message("<p ".$tmp_style.">Ошибка копирования файла: ".$config_file_path."/".$config_file_name." >> ".$config_file_path."/".$FNameArr['name']."<p>","error");
								$ut->utLog(__FILE__ . " - ошибка копирования файла: ".$config_file_path."/".$config_file_name." >> ".$config_file_path."/".$FNameArr['name']."<p>");
							}
							else{
								echo Message("<p ".$tmp_style.">Выполнено копирование файла настроек: ".$config_file_path."/".$config_file_name." >> ".$config_file_path."/".$FNameArr['name']."<p>");
							}	
						}
					}

					// перенумерование таблцы "tblmenu"
					$ReNumStart	 = 20;
					$ReNum		 = $ReNumStart;
					$resultReNum = $sql->sql_query("SELECT * FROM ".$sql->prefix_db."tblmenu ORDER BY position ASC");
					if($sql->sql_rows($resultReNum)){
						while($queryRenum = $sql->sql_array($resultReNum)){
							$sql->sql_update("tblmenu","position='".$ReNum."'","`id`='".$queryRenum["id"]."'");
							$ReNum = $ReNum + $ReNumStart;
						}
					}
					
					// проверка ключей настроек если что-то отсутсвует, добавляем
					foreach($arrListTable AS $key => $t_name){
						$tName		 = str_replace($sql->prefix_db, "", $t_name);
						$config_file = $arrSetting['Path']['tbldata']."/".$tName."/".$tName.".php";
						
						$cfg_edit = new config($config_file);
						$cfg_edit->init();
						
						$StartNum = 50;
						
						// порядок вывода полей в списке
						$tmpSection		 = "sortfield"; 
						$NewNum			 = $StartNum;
						$tmpSectionArr	 = $cfg_edit->getSection($tmpSection);
						$tmpSectionArr1	 = array();
						asort($tmpSectionArr);
						foreach($tmpSectionArr AS $key=>$val){
							$tmpSectionArr1[$key] = $NewNum;
							$NewNum = $NewNum + $StartNum;
						}
						$cfg_edit->setSection($tmpSection, $tmpSectionArr1);
						unset($tmpSection, $tmpSectionArr, $tmpSectionArr1);
						
						// порядок вывода полей в форме для редактирования
						$tmpSection		 = "sortfieldform"; 
						$NewNum			 = $StartNum;
						$tmpSectionArr	 = $cfg_edit->getSection($tmpSection);
						$tmpSectionArr1	 = array();
						asort($tmpSectionArr);
						foreach($tmpSectionArr AS $key=>$val){
							$tmpSectionArr1[$key] = $NewNum;
							$NewNum = $NewNum + $StartNum;
						}
						$cfg_edit->setSection($tmpSection, $tmpSectionArr1);
						unset($tmpSection, $tmpSectionArr, $tmpSectionArr1);
						
						// порядок вывода полей в форме для поиска
						$tmpSection	 = "sortfieldsearch";
						$NewNum			 = $StartNum;
						$tmpSectionArr	 = $cfg_edit->getSection($tmpSection);
						$tmpSectionArr1	 = array();
						asort($tmpSectionArr);
						foreach($tmpSectionArr AS $key=>$val){
							$tmpSectionArr1[$key] = $NewNum;
							$NewNum = $NewNum + $StartNum;
						}
						$cfg_edit->setSection($tmpSection, $tmpSectionArr1);
						unset($tmpSection, $tmpSectionArr, $tmpSectionArr1);

						$cfg_edit->upd();
					}
				}
			}
		}
	}
	else{
		
		include($arrSetting["Path"]["data"]."/lang.php");
		$arrConfigLang = $arrConfig;
		unset($arrConfig);
		
		$cfg_str = TestAlias($_GET["cfg"]);
		$config_file = $arrSetting["Path"]["data"]."/".$cfg_str.".php";
		//echo Message("<p><a href='".$this_url."' ".$tmp_style.">Вернуться к выбору настроек</a> | <strong ".$tmp_style."> Редактирование: [ ".$cfg_str." ]</strong> ".$config_file."</p>");
		echo Message("<p><strong ".$tmp_style."> Редактирование: [ ".$cfg_str." ]</strong> ".$config_file."</p>");
		
		if(!file_exists($config_file)){
			echo Message("Не задан файл настроек!","error");
		}
		else{
			include($config_file);
			
			$listConfig = "";
			$section_key = "";
			if(isset($_GET["sk"])){$section_key = TestAlias($_GET["sk"]);}
			
			$listConfig.= "<table width='100%' border='0' cellspacing='0' cellpadding='7'>";
			$listConfig.= "<tr>";
			
				$listConfig.= "<td width='150' align='right' valign='top'>";
				// список разделов настроек таблицы
				foreach($arrConfig as $key => $val){
					//$listConfig.= (isset($arrLang["LangConfigTable"][$key]) && $arrLang["LangConfigTable"][$key] != "")?$arrLang["LangConfigTable"][$key]:$key;
					
					if($key == "sortfield"){$val["description"] = "Порядок полей в списке";}
					if($key == "sortfieldform"){$val["description"] = "Порядок полей в форме редактирования";}
					if($key == "sortfieldsearch"){$val["description"] = "Порядок полей в форме поиска";}
					
					if($section_key == $key){
						$listConfig.= "<div class='sektion_key_open'>";
						$listConfig.= "[ <a href='".$this_url."?cfg=".$cfg_str."&sk=".$key."' >".$key."</a> ]: ";
						if(isset($val["description"]) && $val["description"] != ""){
							$listConfig.= "<br><span class='sektion_description'>".$val["description"]."</span>";
						}
						$listConfig.= "</div>";
					}
					else{
						$listConfig.= "<div class='sektion_key'>";
						$listConfig.= "<a href='".$this_url."?cfg=".$cfg_str."&sk=".$key."' >".$key."</a>:";
						if(isset($val["description"]) && $val["description"] != ""){
							$listConfig.= "<br><span class='sektion_description'>".$val["description"]."</span>";
						}
						$listConfig.= "</div>";
					}
				}
			
				$listConfig.= "</td>";
				
				$listConfig.= "<td align='left' valign='top'>";
				// для выбора списка полей редактируемой таблицы
				$arr_list_tbl_field_sk_table = array("StatusField", "PrimaryKey", "directory_root", "directory_type"
				, "directory_name", "directory_name2", "directory_name3", "directory_name4", "directory_name5"
				, "directory_name_edit", "directory_name_edit2", "directory_name_edit3", "directory_name_edit4", "directory_name_edit5"
				, "AttachField1", "AttachField2", "AttachField3", "AttachField4", "AttachField5"
				);

				$arr_section_key_noname = array("sortfield", "sortfieldform", "sortfieldsearch");

				$arr_checkbox_field_sk_table = array("TabUse","UseTableUser","AllRows","TotalSumm","UseTableList","is_directory",
				"directory_NoAddInWindow", "directory_NoSelectCat","directory_UseFullPath", "FormButtonShowSave",
				"FormButtonShowCancel","FormButtonShowPrint","FormButtonShowCopy","UseTableFileList","ReadonlyOffForCopyRow" ); 

				$arr_checkbox_field_sk_field = array("TabUse","visible","editable","forprint","for_search","readonly",
				"link_image","link_newwindow","use_order","required","TabUse"
				,"AttachShowColumnName","multiselect"
				); 


				$arr_chekbox_field_other = array(
				"link_newwindow","UsePassword", "ShowEmailFrom", "smtp_on", "dmpDropTableIfNotExists", "dmpCreateTableIfNotExists", "dmpStructureCopy", "dmpDataCopy" , "sqlLogOnOff", "sqlLogMySQLErrorOnOff", "sqlLogMySQLWorkOnOffFull", "sqlDelete", "sqlUpdate", "emlLogOnOff" , "visible", "separator", "lnk_newwindow",
				"AutoReloadEditForm",);

				$SelectedTable = (isset($arrConfig["table"]))?$arrConfig["table"]["name"]:"";

				// получаем список полей текущей таблицы
				if(!$arrFields = $sql->sql_GetFieldFromTable($sql->prefix_db.$SelectedTable)){
					$ut->utLog(__FILE__ . " - Ошибка ошибка получения полей таблицы ".$sql->prefix_db.$TblSetting["table"]["name"]);
				}
				// получаем список тем оформления
				$flc->fListFolders($arrSetting['Path']['tpl'],$arrSetting['Path']['tpl'],true);
				$arrTplPath = $flc->fListFolders;
							
				// типы полей
				$arrTypeField = tblTypeField();
				
				// шаблон настроек полей
				$arrFieldTpl = tblFieldTpl();
							
				// для настройки дополнительных вкладок
				$ArrFormEditTab = array();
				for($i = 4; $i <= $arrSetting['Other']['TabCount']; $i++){
					$ArrFormEditTab[] = "FormEditTab_".$i;
				}
							
				// получаем список таблиц и их полей в массив
				$arrListTable = array();
				if($result1 = $sql->sql_ShowTableFromBD()){
					$arr_count = 0;
					foreach($result1 AS $key => $t_name){
						$tName = str_replace($sql->prefix_db, "", $t_name);
						$arrListTable[$arr_count]["name"]	 = $tName;
						$arrListTable[$arr_count]["field"]	 = $sql->sql_GetFieldFromTable($sql->prefix_db.$tName);
						$arr_count++;
					}		
				}

				// настройки с функциями _sk_table _sk_field
				$arr_function_field_sk_table = array("BeforeLoading","AfterLoading","SelectFunction","BeforeLoadingTable","AfterLoadingTable","BeforeLoadEditForm","AfterLoadEditForm","BeforeSaveRow","AfterSaveRow","BeforeDelRow","AfterDelRow","BeforeChangeRow","AfterChangeRow");
				$arr_function_field_sk_field = array("func","TabFunction");
				
				if($section_key != ""){
					if(is_array($arrConfig[$section_key])){
					
						if(isset($_POST['SaveConfig'])){
						
							$cfg = new config($config_file);
							$cfg->init();
							
							if($cfg_str == "config"){
								foreach($arrConfig[$section_key] as $key1 => $cfg_value){
									$cfg_name = $section_key."_".$key1;
									
									if(in_array($key1,$arr_chekbox_field_other)){
										$cfg->set($section_key, $key1, ((isset($_POST[$cfg_name]))?'1':'0'));
									}
									else{
										$cfg->set($section_key, $key1, strtr($_POST[$cfg_name],array("'"=>'"')));
									}		
								}	
							}
							elseif($cfg_str == "lang"){
								foreach($arrConfig[$section_key] as $key1 => $cfg_value){
									$cfg_name = $section_key."_".$key1;
									$cfg->set($section_key, $key1, strtr($_POST[$cfg_name],array("'"=>'"')));
								}	
							}
							else{
							
								if($section_key == "table"){
									foreach($arrConfig[$section_key] as $key1 => $cfg_value){
										$cfg_name = $section_key."_".$key1;
											
										if(in_array($key1,$arr_checkbox_field_sk_table)){
											$cfg->set($section_key, $key1, ((isset($_POST[$cfg_name]))?'1':'0'));
										}
										else{
											$cfg->set($section_key, $key1, strtr($_POST[$cfg_name],array("'"=>'"')));
										}

										// АВТОЗАПОЛНЕНИЕ НАСТРОЕК
										// если было выбрано поле статуса, то преписываем настройки
										if($key1 == "StatusField" && $arrConfig[$_POST[$cfg_name]]["description"] == ""){
											$cfg->set($_POST[$cfg_name], "description", "Ст.");
											$cfg->set($_POST[$cfg_name], "type", "varbool");
											$cfg->set($_POST[$cfg_name], "default", "1");
											$cfg->set($_POST[$cfg_name], "width", "25");
											$cfg->set($_POST[$cfg_name], "image", "checked.gif");
											$cfg->set($_POST[$cfg_name], "image_other", "unchecked.gif");
										}
										// задано поле для раздела
										if($key1 == "directory_type" && $arrConfig[$_POST[$cfg_name]]["description"] == ""){
											$cfg->set($_POST[$cfg_name], "description", "Это раздел");
											$cfg->set($_POST[$cfg_name], "type", "varbool");
											$cfg->set($_POST[$cfg_name], "default", "0");
											$cfg->set($_POST[$cfg_name], "width", "25");
											$cfg->set($_POST[$cfg_name], "image", "folder.gif");
											$cfg->set($_POST[$cfg_name], "image_other", "group-checked.gif");
											$cfg->set($_POST[$cfg_name], "for_search", "0");
											$cfg->set($_POST[$cfg_name], "link", "javascript:vpid(0);");
											$cfg->set("sortfield",$_POST[$cfg_name], "25");
										}
					
										// задано поле для радительских связей
										if($key1 == "directory_root" && $arrConfig[$_POST[$cfg_name]]["description"] == ""){
											$cfg->set($_POST[$cfg_name], "description", "Раздел");
											$cfg->set($_POST[$cfg_name], "visible", "0");
											$cfg->set($_POST[$cfg_name], "forprint", "0");
											$cfg->set($_POST[$cfg_name], "for_search", "0");
										}
										// задано поле PrimaryKey
										if($key1 == "PrimaryKey" && $arrConfig[$_POST[$cfg_name]]["description"] == ""){
											$cfg->set($_POST[$cfg_name], "description", "ID");
											$cfg->set($_POST[$cfg_name], "visible", "0");
											$cfg->set($_POST[$cfg_name], "editable", "0");
											$cfg->set($_POST[$cfg_name], "forprint", "0");
											$cfg->set($_POST[$cfg_name], "for_search", "0");
											$cfg->set($_POST[$cfg_name], "use_order", "0");
											$cfg->set($_POST[$cfg_name], "readonly", "1");
										}

									}
								}
								elseif(in_array($section_key,$ArrFormEditTab)){
									foreach($arrConfig[$section_key] as $key1 => $cfg_value){
										$cfg_name = $section_key."_".$key1;
											
										if(in_array($key1,$arr_checkbox_field_sk_table)){
											$cfg->set($section_key, $key1, ((isset($_POST[$cfg_name]))?'1':'0'));
										}
										else{
											$cfg->set($section_key, $key1, strtr($_POST[$cfg_name],array("'"=>'"')));
										}
									}
								}
								elseif($section_key != "sortfield" && $section_key != "sortfieldform" && $section_key != "sortfieldsearch"){
									
									
									//foreach($arrConfig[$section_key] as $key1 => $cfg_value){
									foreach($arrFieldTpl as $key1 => $cfg_value){
										$cfg_name = $section_key."_".$key1;

										if(in_array($key1,$arr_checkbox_field_sk_field)){
											$cfg->set($section_key, $key1, ((isset($_POST[$cfg_name]))?'1':'0'));
										}
										else{
											$cfg->set($section_key, $key1, strtr($_POST[$cfg_name],array("'"=>'"')));
										}

									}
								}
								else{
									foreach($arrConfig[$section_key] as $key1 => $cfg_value){
										$cfg_name = $section_key."_".$key1;
										$cfg->set($section_key, $key1, strtr($_POST[$cfg_name],array("'"=>'"')));
									}									
								}	
							

							}
							
							$cfg->upd();
							Redirect($this_url."?cfg=".$cfg_str."&sk=".$section_key,0);
						}
					
						$listConfig.= "<form method='post' action='".$this_url."?cfg=".$cfg_str."&sk=".$section_key."'>";
						$listConfig.= "<p><input class='input-button' type='submit' name='SaveConfig' id='SaveConfig' value='Сохранить' title=''></p>";
						$listConfig.= "<table width='100%' border='0' cellspacing='1' cellpadding='7'>";
						
						
						if($cfg_str == "config"){
							foreach($arrConfig[$section_key] as $key1 => $cfg_value){
								$cfg_name = $section_key."_".$key1;
								$listConfig.= "<tr>";
								$listConfig.= "<td width='300' align='right' valign='middle'><span>";
								$listConfig.= (isset($arrConfigLang["LangCfg".$section_key][$key1]) && $arrConfigLang["LangCfg".$section_key][$key1] != "")?$arrConfigLang["LangCfg".$section_key][$key1]:$key1;
								$listConfig.= ":</span></td>";
								$listConfig.= "<td align='left' valign='middle'>";
								if(in_array($key1,$arr_chekbox_field_other)){
									$listConfig.= "<input name='".$cfg_name."' id='".$cfg_name."' type='checkbox' value='0' ".(($cfg_value == '1')?"checked='checked'":"").">";
								}
								elseif($key1 == "DefaultTpl"){
									$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
									$listConfig.= "<option value=''></option>";
									if(is_array($arrTplPath)){
										foreach($arrTplPath as $keyFunc => $valFunc){
											$listConfig.= "<option value='".$valFunc['file']."' ".(($valFunc['file']==$cfg_value)?"selected='selected'":"").">".$valFunc['file']."</option>";
										}
									}
									$listConfig.= "</select>";
								}
								else{
									$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."'>";
								}
								$listConfig.= "</td>";
								$listConfig.= "</tr>";
							}
						}
						elseif($cfg_str == "lang"){
							foreach($arrConfig[$section_key] as $key1 => $cfg_value){
								$cfg_name = $section_key."_".$key1;
								$listConfig.= "<tr>";
								$listConfig.= "<td width='300' align='right' valign='middle'><span>";
								$listConfig.= $key1;
								$listConfig.= ":</span></td>";
								$listConfig.= "<td align='left' valign='middle'>";
								$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."'>";
								$listConfig.= "</td>";
								$listConfig.= "</tr>";
							}
						}
						else{
						
							if($section_key == "table"){
								foreach($arrConfig[$section_key] as $key1 => $cfg_value){
									$cfg_name = $section_key."_".$key1;
									
									$listConfig.= "<tr bgcolor='#ededed'>";
									$listConfig.= "<td width='300' align='right' valign='middle'><span>";
									$listConfig.= (isset($arrConfigLang["LangConfigTable"][$key1]) && $arrConfigLang["LangConfigTable"][$key1] != "")?$arrConfigLang["LangConfigTable"][$key1]:$key1;
									$listConfig.= ":</span><br><span style='color: #b0b0b0; font-size:11px;' title='Обращение к настройкам'>\$TblSetting[\"".$section_key."\"][\"".$key1."\"]</span></td>";
									$listConfig.= "<td align='left' valign='middle'>";
									
									if(in_array($key1,$arr_list_tbl_field_sk_table)){
										$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
										$listConfig.= "<option value=''></option>";
										foreach($arrFields as $key_f => $val_f){
											$listConfig.= "<option value='".$val_f."' ".(($val_f==$cfg_value)?"selected='selected'":"").">".$val_f."</option>";
										}
										$listConfig.= "</select>";
									}
									elseif(in_array($key1,$arr_checkbox_field_sk_table)){
										$listConfig.= "<input name='".$cfg_name."' id='".$cfg_name."' type='checkbox' value='0' ".(($cfg_value == '1')?"checked='checked'":"").">";
									}
									elseif($key1 == "tpl"){
										$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
										$listConfig.= "<option value=''></option>";
										if(is_array($arrTplPath)){
											foreach($arrTplPath as $keyFunc => $valFunc){
												$listConfig.= "<option value='".$valFunc['file']."' ".(($valFunc['file']==$cfg_value)?"selected='selected'":"").">".$valFunc['file']."</option>";
											}
										}
										$listConfig.= "</select>";
									}	
									elseif($key1 == "ico"){
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."' readonly='readonly'/>";
										$listConfig.= " <a href='./aj.php?af=cfg.window.ico&ret_field=".$cfg_name."' id='cfg_add_ico_".$cfg_name."' title='".$key1." - Выбрать иконку'>Выбрать иконку</a>";
										$listConfig.= " <a href='javascript:void(0);' onclick='jsAddField(\"".$cfg_name."\",\"\");'>сбросить</a>";
										$listConfig.= '<script type="text/javascript">$(function() {$("#cfg_add_ico_'.$cfg_name.'").nyroModal();});</script>';
									}
									elseif($key1 == "WhereType"){
										$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
										$listConfig.= "<option value='and' ".(($cfg_value == "and")?"selected='selected'":"").">and</option>";
										$listConfig.= "<option value='or' ".(($cfg_value == "or")?"selected='selected'":"").">or</option>";
										$listConfig.= "</select>";
									}
									elseif(in_array($key1,$arr_function_field_sk_table)){
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."' readonly='readonly'/>";
										$listConfig.= " <a href='./aj.php?af=cfg.window.function&tbl=".$SelectedTable."&ret_field=".$cfg_name."&now_func=".$cfg_value."' id='cfg_add_func_".$cfg_name."' title='".$key1." - Выбрать функцию - ".$SelectedTable."'>Выбрать функцию</a>";
										$listConfig.= " <a href='javascript:void(0);' onclick='jsAddField(\"".$cfg_name."\",\"\");'>сбросить</a>";
										$listConfig.= '<script type="text/javascript">$(function() {$("#cfg_add_func_'.$cfg_name.'").nyroModal();});</script>';
									}
									else{
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."'>";
									}

									$listConfig.= "</td>";
									$listConfig.= "</tr>";	
								}
							}
							elseif(in_array($section_key,$ArrFormEditTab)){
							
								// $arrConfig['FormEditTab_4']['TabUse'] = '0';
								// $arrConfig['FormEditTab_4']['TabName'] = '';
								// $arrConfig['FormEditTab_4']['TabFunction'] = '';
							
								foreach($arrConfig[$section_key] as $key1 => $cfg_value){
									$cfg_name = $section_key."_".$key1;
									$cfg_value = $arrConfig[$section_key][$key1];
									
									$listConfig.= "<tr bgcolor='#ededed'>";
									$listConfig.= "<td width='300' align='right' valign='middle'><span>";
									$listConfig.= (isset($arrConfigLang["LangConfigField"][$key1]) && $arrConfigLang["LangConfigField"][$key1] != "")?$arrConfigLang["LangConfigField"][$key1]:$key1;
									$listConfig.= ":</span><br><span style='color: #b0b0b0; font-size:11px;' title='Обращение к настройкам'>\$TblSetting[\"".$section_key."\"][\"".$key1."\"]</span></td>";
									$listConfig.= "<td align='left' valign='middle'>";
									
									if(in_array($key1,$arr_checkbox_field_sk_field)){
										$listConfig.= "<input name='".$cfg_name."' id='".$cfg_name."' type='checkbox' value='0' ".(($cfg_value == '1')?"checked='checked'":"").">";
									}
									elseif(in_array($key1,$arr_function_field_sk_field)){
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."' readonly='readonly'/>";
										$listConfig.= " <a href='./aj.php?af=cfg.window.function&tbl=".$SelectedTable."&ret_field=".$cfg_name."&now_func=".$cfg_value."' id='cfg_add_func_".$cfg_name."' title='".$key1." - Выбрать функцию - ".$SelectedTable."'>Выбрать функцию</a>";
										$listConfig.= " <a href='javascript:void(0);' onclick='jsAddField(\"".$cfg_name."\",\"\");'>сбросить</a>";
										$listConfig.= '<script type="text/javascript">$(function() {$("#cfg_add_func_'.$cfg_name.'").nyroModal();});</script>';
									}
									else{
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."'>";	
									}
									
									
									
									$listConfig.= "</td>";
									$listConfig.= "</tr>";	
								}
								
							}							
							elseif($section_key != "sortfield" && $section_key != "sortfieldform" && $section_key != "sortfieldsearch"){
								
								//$arrFieldTpl
								
								foreach($arrFieldTpl as $key1 => $cfg_value){
									$cfg_name = $section_key."_".$key1;
									$cfg_value = $arrConfig[$section_key][$key1];
									
									$listConfig.= "<tr bgcolor='#ededed'>";
									$listConfig.= "<td width='300' align='right' valign='middle'><span>";
									$listConfig.= (isset($arrConfigLang["LangConfigField"][$key1]) && $arrConfigLang["LangConfigField"][$key1] != "")?$arrConfigLang["LangConfigField"][$key1]:$key1;
									$listConfig.= ":</span><br><span style='color: #b0b0b0; font-size:11px;' title='Обращение к настройкам'>\$TblSetting[\"".$section_key."\"][\"".$key1."\"]</span></td>";
									$listConfig.= "<td align='left' valign='middle'>";
									
									
									if($key1 == "type"){
										$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
										foreach($arrTypeField as $keyATF => $valATF){
											$listConfig.= "<option value='".$valATF."' ".(($valATF == $cfg_value)?"selected='selected'":"").">".$valATF."</option>";
										}
										$listConfig.= "</select>";
									}
									elseif($key1 == "FormColumn"){
										$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
										$arrFormColumn = array(0 => "Колонка 1 - центр", 1 => "Колонка 2 - центр", 2 => "Колонка 3 - центр",
										3 => "Колонка 1 - верх", 4 => "Колонка 2 - верх", 5 => "Колонка 3 - верх",
										6 => "Колонка 1 - низ", 7 => "Колонка 2 - низ", 8 => "Колонка 3 - низ");
										foreach($arrFormColumn as $keyATF => $valATF){
											$listConfig.= "<option value='".$keyATF."' ".(($keyATF == $cfg_value)?"selected='selected'":"").">".$valATF."</option>";
										}
										$listConfig.= "</select>";
									}
									elseif($key1 == "directory_table"){
										$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
										$listConfig.= "<option value='' ".(($cfg_value=="")?"selected='selected'":"")."></option>";
										foreach($arrListTable AS $keytbl => $valtbl){
											$listConfig.= "<option value='".$valtbl["name"]."' ".(($valtbl["name"]==$cfg_value)?"selected='selected'":"").">".$valtbl["name"]."</option>";
										}
										$listConfig.= "</select>";
									}
									elseif(in_array($key1,$arr_checkbox_field_sk_field)){
										$listConfig.= "<input name='".$cfg_name."' id='".$cfg_name."' type='checkbox' value='0' ".(($cfg_value == '1')?"checked='checked'":"").">";
									}
									elseif(in_array($key1,$arr_function_field_sk_field)){
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."' readonly='readonly'/>";
										$listConfig.= " <a href='./aj.php?af=cfg.window.function&tbl=".$SelectedTable."&ret_field=".$cfg_name."&now_func=".$cfg_value."' id='cfg_add_func_".$cfg_name."' title='".$key1." - Выбрать функцию - ".$SelectedTable."'>Выбрать функцию</a>";
										$listConfig.= " <a href='javascript:void(0);' onclick='jsAddField(\"".$cfg_name."\",\"\");'>сбросить</a>";
										$listConfig.= '<script type="text/javascript">$(function() {$("#cfg_add_func_'.$cfg_name.'").nyroModal();});</script>';
									}
									elseif($key1 == "image_other" || $key1 == "image"){
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."' readonly='readonly'>";
										$listConfig.= " <a href='./aj.php?af=cfg.window.ico&ret_field=".$cfg_name."' id='cfg_add_ico_".$cfg_name."' title='".$key1." - Выбрать иконку'>Выбрать иконку</a>";
										$listConfig.= " <a href='javascript:void(0);' onclick='jsAddField(\"".$cfg_name."\",\"\");'>сбросить</a>";
										$listConfig.= '<script type="text/javascript">$(function() {$("#cfg_add_ico_'.$cfg_name.'").nyroModal();});</script>';
									}
									
									elseif(in_array($key1,$arr_list_tbl_field_sk_table)){
										$listConfig.= "<select class='input-select' name='".$cfg_name."' id='".$cfg_name."'>";
										$listConfig.= "<option value=''></option>";
										foreach($arrFields as $key_f => $val_f){
											$listConfig.= "<option value='".$val_f."' ".(($val_f==$cfg_value)?"selected='selected'":"").">".$val_f."</option>";
										}
										$listConfig.= "</select>";
									}
									
									else{
										$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."'>";
									}
								
									$listConfig.= "</td>";
									$listConfig.= "</tr>";										
								}
								
								// foreach($arrConfig[$section_key] as $key1 => $cfg_value){
									// $cfg_name = $section_key."_".$key1;
								// }
							}

							else{
								foreach($arrConfig[$section_key] as $key1 => $cfg_value){
									
									$cfg_name = $section_key."_".$key1;
									
									$listConfig.= "<tr bgcolor='#ededed'>";
									$listConfig.= "<td width='300' align='right' valign='middle'><span>";
									$listConfig.= $key1;
									$listConfig.= ":</span><br><span style='color: #b0b0b0; font-size:11px;' title='Обращение к настройкам'>\$TblSetting[\"".$section_key."\"][\"".$key1."\"]</span></td>";
									$listConfig.= "<td align='left' valign='middle'>";
									$listConfig.= "<input class='input-text' type='text' name='".$cfg_name."' id='".$cfg_name."' value='".$cfg_value."'>";
									$listConfig.= "</td>";
									$listConfig.= "</tr>";	
								}
							}
						}
						
						$listConfig.= "</table>";
						$listConfig.= "<p><input class='input-button' type='submit' name='SaveConfig' id='SaveConfig' value='Сохранить' title=''></p>";
						$listConfig.= "</form>";
					}
					else{
						$listConfig.= Message("Необходимо выбрать <strong>другой</strong> раздел для редактирования");
						//$listConfig.= Message("Создать новый раздел настроек");
					}
				}
				else{
					$listConfig.= Message("Необходимо выбрать раздел для редактирования");
				}
				$listConfig.= "</td>";
			$listConfig.= "</tr>";
			$listConfig.= "</table>";
			echo $listConfig;			
		}
		

	}
	if(file_exists($TblDefTplPath."/config.bottom.php")){include($TblDefTplPath."/config.bottom.php");}
	$sql->sql_close();
}
?>