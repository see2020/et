<?php
/**
 * func.php - дополнительные функции общего назначения
 *
 */

	function Redirect($param,$timeout = 0){
		$param = trim($param);
 		echo "<script language='Javascript'><!--
		function reload() {location = \"".$param."\"}; setTimeout('reload()', ".$timeout.");
		//--></script>"; 
	}

	//считаем странички
	// page - текущая стр.(получаем по гет)
	// num - общее количесво строк
	// count_on_page - количетсво строк на страницу
	function PageGetCount($page, $num, $count_on_page){
		$count_on_page = (int)$count_on_page;
		$start = 0;
		if($count_on_page > 0 ){
			if(!isset($page) && intval($page) == 0) {$page = 1;}
			//количество страниц = количество строчек в базе : кол-во элементов на странице
			$count_pages = intval($num / $count_on_page); 
			// проверяем, нет ли остатка от деления
			$ostatok	 = $num % $count_on_page; 
			//если остаток есть, то прибавляем к числу страниц единичку
			if($ostatok > 0){ $count_pages++;} 
			// вычисляем строчку с какой начинаем выводить из бд
			$start		 = $count_on_page * $page - $count_on_page; 
		}
		return($start);
	}
	
	//выводим стнавигацию по страницам
	// page - текущая стр.(получаем по гет)
	// num - общее количесво строк
	// count_on_page - количетсво строк на страницу
	function PageListShow($page, $num, $count_on_page){
		$arrPLS = array();
		//сколько ссылок на страницы делать
		$diapazon = 6;
		//начальны значения
		$pBack	 = "1";
		$pNext	 = "1";
		if(!isset($page) && intval($page) == 0){$page = 1;}
		//количество страниц = количество строчек в базе : кол-во элементов на странице
		$count_pages = intval($num / $count_on_page); 
		// проверяем, нет ли остатка от деления
		$ostatok	 = $num % $count_on_page; 
		//если остаток есть, то прибавляем к числу страниц единичку
		if($ostatok > 0) {$count_pages++;} 
		if($page > 1){$pBack = ($page - 1);}
		$page_from	 = $page - $diapazon; 
		if($page_from < 1) {$page_from = 1;}
		$page_to	 = $page + $diapazon; 
		if($page_to > $count_pages) {$page_to = $count_pages;}
		if($page < $count_pages){$pNext = ($page + 1);}
		$arrPLS["row_count"] = $num;
		$arrPLS["first"]	 = "1";
		$arrPLS["back"] 	 = $pBack;
		$arrPLS["now"]		 = $page;
		$arrPLS["next"]		 = $pNext;
		$arrPLS["end"]		 = $count_pages;
		
		return($arrPLS);
	}

	//страничка с кнопками да/нет(buttonYes / buttonNo)
	function WindowYesNo($text = "",$ActionString = "", $arrSettingForm = array()){
		$ShowForm = "";
		$ShowForm.= '<div style="width: 100%;text-align: center;border: 1px solid #DDD;margin:5px 0px 5px 0px;padding: 5px;background: #FFF6BF; color: #514721; border-color: #FFD324;">';
		$ShowForm.= "<form id='".((isset($arrSettingForm['fName']))?$arrSettingForm['fName']:"FormYesNo")."' name='".((isset($arrSettingForm['fName']))?$arrSettingForm['fName']:"FormYesNo")."' method='post' action='{%WindowYesNoActionString%}'>";
		$ShowForm.= "<p>{%WindowYesNoText%}</p>";
		$ShowForm.= "<input type='submit' name='".((isset($arrSettingForm['bNameYes']))?$arrSettingForm['bNameYes']:"buttonYes")."' id='".((isset($arrSettingForm['bNameYes']))?$arrSettingForm['bNameYes']:"buttonYes")."' value='Yes' style='width:40pt;'>";
		$ShowForm.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$ShowForm.= "<input type='submit' name='".((isset($arrSettingForm['bNameNo']))?$arrSettingForm['bNameNo']:"buttonNo")."' id='".((isset($arrSettingForm['bNameNo']))?$arrSettingForm['bNameNo']:"buttonNo")."' value='No' style='width:40pt;'>";
		$ShowForm.= "</form>";
		$ShowForm.= "</div>";
		return(str_replace(array("{%WindowYesNoActionString%}","{%WindowYesNoText%}"), array($ActionString,$text), $ShowForm));
	}

	//просто сообщение большими буквами!
	function Message($mess, $Type = ""){
		if(strtolower($Type) == "alert"){
			return('<div style="width: 98%;text-align: left;border: 1px solid #DDD;margin:5px 0px 5px 0px;padding: 5px;background: #E6EFC2; color: #264409; border-color: #C6D880;">'.$mess.'</div>');
		}
		if(strtolower($Type) == "error"){
			return('<div style="width: 98%;text-align: left;border: 1px solid #DDD;margin:5px 0px 5px 0px;padding: 5px;background: #FBE3E4; color: #8A1F11; border-color: #FBC2C4;">'.$mess.'</div>');
		}
		return('<div style="width: 98%;text-align: left;border: 1px solid #DDD;margin:5px 0px 5px 0px;padding: 5px;background: #FFF6BF; color: #514721; border-color: #FFD324;">'.$mess.'</div>');
	}

	// показываем массив в удобном виде
	function ParseArrForLog($arrLog){
		
		if(is_array($arrLog)){
			$arrLogTemp = "";
			foreach($arrLog as $key=>$val){
				if(!is_array($val)){
					$val = str_replace("<", "&lt;", $val);
					$val = str_replace(">", "&gt;", $val);
					$arrLogTemp.= "[".$key."]=>'".$val."', ";
				}
				else{
					$arrLogTemp.= "[".$key."]=>(".ParseArrForLog($val)."), ";
				}
			}
			return($arrLogTemp);
		}
		else{
			return("array is null!");
		}
	}
	
	// получение данных из другой таблицы
	function GetDataOtherTable($sql,$ut,$primarytable,$primarykey,$primaryvalue,$SearchVal){
		$result = $sql->sql_query("SELECT `".$primaryvalue."` FROM `".$sql->prefix_db.$primarytable."` WHERE `".$primarykey."`='".$SearchVal."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			return($query[$primaryvalue]);
		}
		else{
			$ut->utLog(__FILE__." не могу получить запись");
			return("<span style='color:red;'>Error!</span>");
		}
	}
	
	//получение списка полей подключенной таблицы
	function GetSettingByOtherTable(&$cfg,&$ut,$arrSetting = "",$TblName = ""){
		if($TblName==""){return(false);}
		if(!is_array($arrSetting)){return(false);}
		$TblData = $arrSetting['Path']['tbldata']."/".$TblName;
		
		//получаем настройки из ini-файла
		$cfg->fINIFileName = $TblData."/".$TblName.".ini";
		
		$TblSetting = array();
		
		if($cfg->fINIInitArray()){
			$TblSetting = $cfg->fINIArray;
		}
		
		// выставляем порядок вывода столбцов таблицы
		asort($TblSetting["sortfield"]);
		return($TblSetting);
	}
	
	// для форм, доступные поля
	function GetFieldForTpl($TblSetting){
		$formContent = "";
		$formContent.= "<!--\r\n";
		$formContent.= "<br>\r\n";
		$formContent.= "ДОСТУПНЫЕ ПОЛЯ :<br>\r\n";
		$FieldList		 = "";
		$FieldFormList	 = "";
		reset($TblSetting["sortfieldform"]);
		foreach ($TblSetting["sortfieldform"] as $key=>$val){
			if($TblSetting[$key]["type"] != "support"){
				$FieldList.= $TblSetting[$key]["name"].", ";
				$FieldFormList.= strip_tags(($TblSetting[$key]["description"]!="")?$TblSetting[$key]["description"]:$TblSetting[$key]["name"])."<br>\r\n";
				$FieldFormList.= "<input type='text' name='".$TblSetting[$key]["name"]."' id='".$TblSetting[$key]["name"]."' style='width: 150px;' value='<?php echo \$qChange['".$TblSetting[$key]['name']."']; ?>' /><br>\r\n";
			}
		}
		$formContent.= substr($FieldList,0,-2)."\r\n";
		$formContent.= "<br>\r\n";
		$formContent.= $FieldFormList."\r\n";
		$formContent.= "<br>\r\n";
		$formContent.= "-->\r\n";
		return($formContent);
	}
	
	
	// определение подключаемого файла
	function GetIncFile($arrSetting,$f_name = "", $t_name = ""){
		$f_path_def	 = $arrSetting['Path']['inc']."/".$f_name;
		$f_path		 = $arrSetting['Path']['tbldata']."/".$f_name;
		if($t_name != ""){
			$f_path_def	 = $arrSetting['Path']['inc']."/".$f_name;
			$f_path		 = $arrSetting['Path']['tbldata']."/".$t_name."/tForm/".$f_name;
		}
		if(file_exists($f_path)){return($f_path);}
		if(file_exists($f_path_def)){return($f_path_def);}
		else{echo Message("INCLUDE: ".$f_path_def." - error","error");return("");}	
	}
	
	// типы полей
	function tblTypeField(){
		$arrTypeField					 = array();
		$arrTypeField["text"]			 = "text";
		$arrTypeField["textarea"]		 = "textarea";
		$arrTypeField["selectarea"]		 = "selectarea";
		$arrTypeField["radiobutton"]	 = "radiobutton";
		$arrTypeField["number"]			 = "number";
		$arrTypeField["date"]			 = "date";
		$arrTypeField["varbool"]		 = "varbool";
		$arrTypeField["support"]		 = "support";
		$arrTypeField["link"]			 = "link";
		$arrTypeField["directory_name"]	 = "directory_name";
		$arrTypeField["directory_id"]	 = "directory_id";
		$arrTypeField["file"]			 = "file";
		$arrTypeField["image"]			 = "image";
		$arrTypeField["hide"]			 = "hide";
		$arrTypeField["password"]		 = "password";
		// список строк
		$arrTypeField["list_string"]	 = "list_string";
		// список ссылок
		$arrTypeField["list_link"]		 = "list_link";
		return($arrTypeField);
	}
	// шаблон настроек полей
	function tblFieldTpl(){
		$arrFieldTpl					 = array();
		$arrFieldTpl['name']			 = ""; 
		$arrFieldTpl['description']		 = "";
		$arrFieldTpl['column_descr']	 = "";
		$arrFieldTpl['type']			 = "text"; // от типа зависит формат редатирования данных
		$arrFieldTpl['type_data']		 = ""; // данные для полей с типом selectarea и radio
		$arrFieldTpl['dateformat']		 = "Y-m-d H:i:s";
		$arrFieldTpl['default']			 = ""; // значение по умолчанию, необходимо при создании новой записи или вывода в список
		$arrFieldTpl['maxlength']		 = ""; // ограничениее на количество символов для текстовых полей ("maxlength"=""), 0 или "" не ограничено
		
		$arrFieldTpl['directory_table']		 = ""; // подключаемая таблица
		$arrFieldTpl['multiselect']			 = "0"; // Множественный выбор для полей типа directory_name
		$arrFieldTpl['multiselect_sep']		 = ","; // Разделитель выбранного для полей типа directory_name
		
		$arrFieldTpl['visible']			 = "1";
		$arrFieldTpl['editable']		 = "1";
		$arrFieldTpl['forprint']		 = "1";
		$arrFieldTpl['for_search']		 = "1";
		$arrFieldTpl['readonly']		 = "0";
		$arrFieldTpl['use_order']		 = "0"; // включить сортировку по колонке
		$arrFieldTpl['required']		 = "0"; // поле обязательно к заполнению
		
		$arrFieldTpl['width']			 = "";
		
		$arrFieldTpl['image']			 = "";
		$arrFieldTpl['image_other']		 = "";
		
		$arrFieldTpl['link']			 = "";
		//$arrFieldTpl['link_edit']		 = "0";
		$arrFieldTpl['link_image']		 = "1";
		$arrFieldTpl['link_newwindow']	 = "0";
		
		$arrFieldTpl['func']			 = "";
		//$arrFieldTpl['theme_head']		 = "";
		$arrFieldTpl['theme']			 = "";
		
		$arrFieldTpl["FormFieldWidth"]		 = "500"; // ширина полей на форме
		$arrFieldTpl["FormFieldHeight"]		 = "100"; // высота полей на форме
		$arrFieldTpl["FormColumn"]			 = "0"; // колонка размещения поля на форме редактирования
		
		
		// в списке, для вывода в нескольких полей в одной колонке
		$arrFieldTpl["AttachField1"] = "";
		$arrFieldTpl["AttachField2"] = "";
		$arrFieldTpl["AttachField3"] = "";
		$arrFieldTpl["AttachField4"] = "";
		$arrFieldTpl["AttachField5"] = "";
		$arrFieldTpl["AttachSeparator"]		 = "; "; // Разделитель объединенных полей
		$arrFieldTpl["AttachShowColumnName"] = "0"; // показывать название колонки в списке
		
		
		return($arrFieldTpl);
	}
	// шаблон настроек таблицы
	function tblTableTpl(){
		$arrTableTpl						 = array();
		$arrTableTpl["name"]				 = "";
		$arrTableTpl["PrimaryKey"]			 = "";
		$arrTableTpl["description"]			 = "";
		$arrTableTpl["order"]				 = "";
		$arrTableTpl["limit"]				 = "";
		$arrTableTpl["StatusField"]			 = "";
		$arrTableTpl["AllRows"]				 = "1";
		$arrTableTpl["WhereType"]			 = "or"; // and/or
		$arrTableTpl["TotalSumm"]			 = "0";
		//$arrTableTpl["func"]				 = "";
		
		$arrTableTpl["BeforeLoading"]	 = ""; // функция до загрузки таблицы, используется в списке и в редактировании
		$arrTableTpl["AfterLoading"]	 = ""; // функция полсе загрузки таблицы, используется в списке и в редактировании

		$arrTableTpl["BeforeLoadingTable"]	 = ""; // функция до загрузки списка таблицы
		$arrTableTpl["AfterLoadingTable"]	 = ""; // функция полсе загрузки списка таблицы
		
		$arrTableTpl["BeforeLoadEditForm"]	 = ""; // функция до загрузки формы редактирования записи
		$arrTableTpl["AfterLoadEditForm"]	 = ""; // функция после загрузки формы редактирования записи
		
		$arrTableTpl["BeforeSaveRow"]		 = ""; // функция до сохранения формы редактирования записи
		$arrTableTpl["AfterSaveRow"]		 = ""; // функция после сохранения формы редактирования записи
		$arrTableTpl["BeforeDelRow"]		 = ""; // функция до удаления записи
		$arrTableTpl["AfterDelRow"]			 = ""; // функция после удаления записи
		$arrTableTpl["BeforeChangeRow"]		 = ""; // функция до внесения изменения в поле статуса указанного в настройках StatusField
		$arrTableTpl["AfterChangeRow"]		 = ""; // функция после внесения изменения в поле статуса указанного в настройках StatusField
		
		$arrTableTpl["SelectFunction"]		 = ""; // запрос для списка записей
		
		$arrTableTpl["ReadonlyOffForCopyRow"] = ""; // разрешить редактирование полей для чтения при копориовании записи
		
		$arrTableTpl["FormFieldWidth"]		 = "500"; // ширина полей на форме
		$arrTableTpl["FormFieldHeight"]		 = "100"; // высота полей на форме
		
		$arrTableTpl["FormButtonShowSave"]		 = "1"; // показать кнопку формы "Сохранить"
		$arrTableTpl["FormButtonShowCancel"]	 = "1"; // показать кнопку формы "Отмена"
		$arrTableTpl["FormButtonShowPrint"]		 = "1"; // показать кнопку формы "Печать"
		$arrTableTpl["FormButtonShowCopy"]		 = "1"; // показать кнопку формы "Сделать копию"
	
		$arrTableTpl["NameTabFileList"]		 = "Загрузка файлов";
		$arrTableTpl["UseTableFileList"]	 = "0";
		$arrTableTpl["NameTabTableList"]	 = "Список";
		$arrTableTpl["UseTableList"]		 = "0";
		
		$arrTableTpl["NameTabTableUser"]	 = "Список таблиц пользователя";
		$arrTableTpl["UseTableUser"]		 = "0";
		
		$arrTableTpl["ico"]					 = "";
		$arrTableTpl["tpl"]					 = "";
		
		$arrTableTpl["is_directory"]		 = "0";
		$arrTableTpl["directory_root"]		 = ""; // родительское поле
		$arrTableTpl["directory_name"]		 = ""; // имя в списке
		$arrTableTpl["directory_name2"]		 = ""; // имя в списке 2, разделитель "; "
		$arrTableTpl["directory_name3"]		 = ""; // имя в списке 3, разделитель "; "
		$arrTableTpl["directory_name4"]		 = ""; // имя в списке 4, разделитель "; "
		$arrTableTpl["directory_name5"]		 = ""; // имя в списке 5, разделитель "; "
		$arrTableTpl["directory_name_edit"]	 = ""; // имя для полей редатирования в других таблицах
		$arrTableTpl["directory_name_edit2"] = ""; // имя для полей редатирования в других таблицах 2, разделитель "; "
		$arrTableTpl["directory_name_edit3"] = ""; // имя для полей редатирования в других таблицах 3, разделитель "; "
		$arrTableTpl["directory_name_edit4"] = ""; // имя для полей редатирования в других таблицах 4, разделитель "; "
		$arrTableTpl["directory_name_edit5"] = ""; // имя для полей редатирования в других таблицах 5, разделитель "; "

		//$arrTableTpl["directory_name_show"]	 = ""; // имя в списке для связи по идентификатору
		$arrTableTpl["directory_type"]		 = ""; // это категория
		
		$arrTableTpl["directory_NoAddInWindow"]	 = "0"; // это категория
		$arrTableTpl["directory_NoSelectCat"]	 = "0"; // запретить выбор категорий в окне выбора
		$arrTableTpl["directory_UseFullPath"]	 = "0"; // показывать полный путь к выбранному элементу
		
		$arrTableTpl["FileStore"]				 = ""; // папка для загрузки файлов
		return($arrTableTpl);
	}
	// шаблон настроек списка таблиц
	function tblTableListTpl(){
		$arrTableListTpl				 = array();
		$arrTableListTpl['name']			 = "";
		$arrTableListTpl['description']		 = "";
		$arrTableListTpl['visible']			 = "0";
		$arrTableListTpl['separator']		 = "0";
		$arrTableListTpl['ico']				 = "";
		$arrTableListTpl['lnk']				 = "";
		$arrTableListTpl['lnk_newwindow']	 = "0";
		$arrTableListTpl['position']		 = "0";
		$arrTableListTpl['main_menu']		 = "";
		return($arrTableListTpl);
	}
	
	// получение массива с данными для поляе типа select и radio
	function GetArrTypeData($type_data = ""){
		//значение_0=имя_0;значение_N=имя_N;
		$return_arr = array();
		if($type_data ==""){return($return_arr);}
		$arr = explode(";", $type_data);
		reset($arr);
		foreach($arr as $key=>$val){
			if(!empty($val)){
				@list($var_val,$var_name) = explode("=", $val);
				$return_arr[$var_val] = $var_name;
			}
		}
		return($return_arr);
	
	}
	
	function SetAttributes($attributes = ""){
		if(is_array($attributes)){
			$attr = "";
			if(count($attributes) > 0){
				foreach($attributes as $key => $val){
					$attr.= " ".$key."=\"".$val."\" ";
				}
			}
			return($attr);
		}else{
			return($attributes);
		}
	}
	
	// формирование ссылок
	function fLnk($anchor = "", $url = "", $attributes = ""){return("<a ".(($url!="")?"href=\"".$url."\"":"")." ".SetAttributes($attributes).">".$anchor."</a>");}

	// меню по таблицам
	function mUpMenuArr($TblList){
		
		$tListArr = array();
		$tListArrRet = array();
		foreach($TblList as $key => $val){
			if($val['visible']==1){

				$tbl_lnk = ($val['lnk']=="")?"?tbl=".$val['name']."":$val['lnk'];	
				//для того что бы вернуться на туже страницу списка из меню после редактирования
				if($val['name'] == $_GET['tbl']){
					if($_GET['event'] == "edit" && isset($_GET['pagenum'])){	
						$tbl_lnk = ($val['lnk']=="")?"?tbl=".$val['name']."&pagenum=".$_GET['pagenum']:$val['lnk'];
					}
				}
				
				$arrKey = 10000 + (array_key_exists($val['position'], $tListArr))?$val['position'] + 1:$val['position'];
				
				$tmpArr = array(
					$arrKey => array("link" => "","img" => (($val['separator']==0)?"":"sep.gif"),"title" => "",),
					$arrKey + 1 => array(
						"link" => $tbl_lnk,
						"img" => (($val['ico']!="")?$val['ico']:""),
						"title" => (($val['description']=="")?$val['name']:$val['description']),
						"attributes" => (($val['lnk_newwindow']=="1")?array("target" => "_blank"):""),
					),
				);
				// если есть таблицы в списке разрешенных пользователю
				if(is_array($_SESSION[D_NAME]['user']['UserTables'])){
					// добавляем в выод только те которые разрешены
					if(in_array($val['name'],$_SESSION[D_NAME]['user']['UserTables'])){
						$tListArr+= $tmpArr;
					}
				}
				// добавляем в список все таблицы
				else{
					$tListArr+= $tmpArr;
				}
				unset($tmpArr);

				
			}
		}

		reset($tListArr);
		ksort($tListArr);
		foreach ($tListArr as $key=>$val){
			$tListArrRet[$key] = $val;
		}
		return($tListArrRet);
	}

	function mUpMenuArrTbl(&$sql,$id_root = 0){
		
		$id_root = (int)$id_root;
		$tblSysMenu = "tblmenu";
		
		//$_SESSION[D_NAME]['user']['UserType']
	
		// read=read;new=new;edit=edit;admin=admin;root=root
		// read >> new >> edit >> admin >> root
		// read	 - чтение
		// new	 - чтение, создание
		// edit	 - чтение, создание, редактрование
		// admin - чтение, создание, редактрование, удаление, настройки таблицы
		// root	 - чтение, создание, редактрование, удаление, настройки таблицы, прочие системные настройки
	
		$arr_access["root"]	 = array("root","admin","edit","new","read");
		$arr_access["admin"] = array("admin","edit","new","read");
		$arr_access["edit"]	 = array("edit","new","read");
		$arr_access["new"]	 = array("new","read");
		$arr_access["read"]	 = array("read");
		
		
		$rSysMenu = $sql->sql_query("SELECT * FROM `".$sql->prefix_db.$tblSysMenu."` where st='1' AND id_root='".$id_root."' ORDER BY position asc");
		if($sql->sql_rows($rSysMenu)){
			$tListArr = array();
			$tListArrRet = array();
			$numrow = 0;
			while($query = $sql->sql_array($rSysMenu)){
				$numrow++;
				//для того что бы вернуться на туже страницу списка из меню после редактирования
				if(isset($_GET['tbl']) && $query['tbl_name'] == $_GET['tbl']){
					if(isset($_GET['event']) && $_GET['event'] == "edit" && isset($_GET['pagenum'])){	
						$tbl_lnk = ($query["lnk"] == "")?"?tbl=".$query['tbl_name']."&pagenum=".$_GET['pagenum']:$query["lnk"];
					}
				}
				
				$query['position'] = (int)$query['position'];
				
				if($query['position'] == 0){
					$arrKey = "10".$numrow.$query['position'];	
				}
				else{
					$arrKey = "10".$query['position'];	
				}
				
				if($query["is_separator"] == 1){
					$tmpArr = array(
						$arrKey => array("link" => "","img" => "","title" => "","submenu" => array(),),
						$arrKey + 1 => array("link" => "","img" => "sep.gif","title" => "","submenu" => array(),),
					);
				}
				else{
					$tmpArr = array(
						$arrKey => array("link" => "","img" => "","title" => "","submenu" => array(),),
						$arrKey + 1 => array(
							"link"		 => (($query["lnk"]=="")?"?tbl=".$query['tbl_name']."":$query["lnk"]),
							"img"		 => (($query["tbl_ico"] != "")?$query["tbl_ico"]:""),
							"title"		 => (($query["title"] == "")?$query["tbl_name"]:$query["title"]),
							"attributes" => (($query["lnk_blank"] == 1)?array("target" => "_blank"):""),
							"submenu"	 => (($query["show_submenu"] != 0)?mUpMenuArrTbl($sql,$query["id"]):array()),
						),
					);
				}
				
				if($query["u_access"] == ""){$query["u_access"] = "root";}

				// проверяем основное разрешение пользователя для строки меню
				if(usr_Access($query['u_access'])){
					$tListArr+= $tmpArr;
				}
				unset($tmpArr);
			}
			
			reset($tListArr);
			ksort($tListArr);
			foreach($tListArr as $key => $val){
				$tListArrRet[$key] = $val;
			}
			return($tListArrRet);
		}
		else{
			return(array());
		}
	}
	
	// получаем настройки таблицы
	function tblGetConfig($TblName,$arrSetting){
		$TblCfgFile = $arrSetting["Path"]["tbldata"]."/".$TblName."/".$TblName.".php";
		if(file_exists($TblCfgFile)){
			include($TblCfgFile);
			return($arrConfig);
		}
		else{return(array());}
	}
	
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Поиск
	function TypeSearch(){
		$arr_srch[0]	 = "Не использовать";
		$arr_srch[10]	 = "Равно"; // =
		$arr_srch[11]	 = "Не равно"; // <>
		$arr_srch[12]	 = "Содержит"; // LIKE
		$arr_srch[13]	 = "В списке"; // IN()
		$arr_srch[14]	 = "Больше или равно"; // >=
		$arr_srch[15]	 = "Меньше или равно"; // <=
		$arr_srch[16]	 = "Больше"; // >
		$arr_srch[17]	 = "Меньше"; // <
		//$arr_srch[50]	 = "Равно пустое значение"; // =""
		return($arr_srch);
	}
// Поиск конец
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Формы

	function frmInput($attributes = ""){
		if($attributes == ""){return(false);}
		return("<input ".SetAttributes($attributes).">");
	}
	function frmTextarea($field_name = "", $field_value = "", $attributes = array(),$ro = false){
		if($field_name == ""){return(false);}
		
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_field = "";
		$return_field.= "<div style=\"".$stl_ro."\">";
		if(!$ro){
			$return_field.= "<textarea name=\"".$field_name."\" ".SetAttributes($attributes).">".$field_value."</textarea>";
		}
		else{
			$return_field.= "<textarea name=\"".$field_name."\" readonly=\"readonly\" ".SetAttributes($attributes).">".$field_value."</textarea>";
		}
		$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_field.= "</div>";		
		return($return_field);
	}
	
	function frmSelect($field_name = "", $data_arr = array(), $data_selected = "", $attributes = array(),$ro = false){
		if($field_name == ""){return(false);}
		if(!is_array($data_arr)){return(false);}
		
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;color:#8c8c8c;";}
		$attributes["style"] = $attributes["style"].$stl_ro;
		
		$return_field = "";
		$return_field.= "<div>";
		if(!$ro){
			$return_field.= "<select name='".$field_name."' ".SetAttributes($attributes).">"; 
		}
		else{
			$return_field.= "<select name='".$field_name."' ".SetAttributes($attributes)." onchange=\"this.selectedIndex='".$data_selected."'\">"; 
		}
		foreach($data_arr as $key=>$val){
			$return_field.= "<option value='".$key."' ".(($data_selected == $key)?"selected":"").">".$val."</option>";
		}
		$return_field.= "</select>";
		$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_field.= "</div>";
		return($return_field);
	}

	function frmRadio($field_name = "", $data_arr = array(), $data_selected = "", $attributes = array()){
		if($field_name == ""){return(false);}
		if(!is_array($data_arr)){return(false);}
		
		$frm = ""; 
		foreach($data_arr as $key => $val){
			$attributes = array("type" => "radio", "name" => $field_name, "value" => $key,) + $attributes;
			$frm.= "<input ".SetAttributes($attributes)." ".(($data_selected == $key)?"checked":"")."> ".$val." ";
		}
		return($frm);
	}

	
	function frmInputText($field_name = "", $field_value = "", $attributes = array(),$width_field = "350",$ro = false){
		if($field_name == ""){return(false);}
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_field = "";
		$return_field.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		if(!$ro){
			$return_field.= frmInput(array("type"=>"text", "name"=>$field_name, "style"=>"width: ".((int)$width_field - 29)."px; ", "value"=>$field_value, ) + $attributes);
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"X", "OnClick"=>"document.getElementById('".$field_name."').value = '';", "title"=>"Сбросить" ));
		}
		else{
			$return_field.= frmInput(array("type"=>"text", "name"=>$field_name,"readonly"=>"readonly", "style"=>"width: ".((int)$width_field - 29)."px;", "value"=>$field_value, ) + $attributes);
		}
		$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_field.= "</div>";
		
		return($return_field);
	}
	function frmInputPassword($field_name = "", $field_value = "", $attributes = array(),$width_field = "350",$ro = false){
		if($field_name == ""){return(false);}
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_field = "";
		$return_field.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		if(!$ro){
			$return_field.= frmInput(array("type"=>"password", "name"=>$field_name, "style"=>"width: ".((int)$width_field - 60)."px; ", "value"=>$field_value, ) + $attributes);
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"X", "OnClick"=>"document.getElementById('".$field_name."').value = '';", "title"=>"Сбросить"));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"&bull;", "OnClick"=>"alert(document.getElementById('".$field_name."').value);", "href"=>"javascript:void(0);", "title"=>"Показать пароль"));
		}
		else{
			$return_field.= frmInput(array("type"=>"password", "name"=>$field_name,"readonly"=>"readonly", "style"=>"width: ".((int)$width_field - 29)."px;", "value"=>$field_value, ) + $attributes);
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"&bull;", "OnClick"=>"alert('".$field_value."');", "href"=>"javascript:void(0);", ));
		}
		$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_field.= "</div>";
		
		return($return_field);
	}

	function frmInputCheckbox($field_name = "", $field_value = bool, $attributes = array(),$ro = false){
		if($field_name == ""){return(false);}
		if(!$ro){
			$attr = array("type" => "checkbox", "name" => $field_name, "value" => "1",);
			if($field_value){$attr+= array("checked" => "checked");}
			$attributes = $attr + $attributes;
			return(frmInput(SetAttributes($attributes)));
		}
		else{
			$return_var = "<span style=\"border: 1px solid #cccccc;\">";
			$return_var.= ($field_value)?"Да":"Нет";
			$return_var.= "</span>";
			return($return_var);
		}
	}
	
	function frmInputDateAdd($Setting, $field_name = "", $field_value = "", $attributes = array(),$width_field = "350",$ro = false){
		if($field_name == ""){return(false);}
		
		// $Setting["arrSetting"]	 = $arrSetting;
		// $Setting["TblSetting"]	 = $TblSetting;
		// $Setting["ut_class"]		 = $ut;
		
		$tmp_dt = $Setting["ut_class"]->utGetDate($Setting["TblSetting"][$field_name]['dateformat'],$field_value);
		if(is_numeric($field_value)){
			if($field_value == 0){
				$tmp_dt = "";
			}
		}

		if(!$ro){
			$attr = array("type" => "text", "name" => $field_name, "value" => $tmp_dt,"style"=>"width: ".((int)$width_field - 22)."px; ",);
		}
		else{
			$attr = array("type" => "text", "name" => $field_name, "value" => $tmp_dt,"style"=>"width: ".((int)$width_field - 22)."px; ","readonly"=>"readonly",);
		}
		
		$attributes = $attr + $attributes;
		//d:\Server\domains\edit_table\public_html\edit_table\components\ico\
		
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		
		$return_var = "";
		$return_var.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		$return_var.= frmInput(SetAttributes($attributes));
		
		if(!$ro){
			$return_var.= "<input type='image' src='".$Setting["arrSetting"]["Path"]["ico"]."/calendar.gif' style='margin-bottom:-5px;' />";
			$dt_format = strtr($Setting["TblSetting"][$field_name]['dateformat'],array(
							"Y"=>"%Y", "m"=>"%m", "d"=>"%d", 
							"H"=>"%H", "h"=>"%h", "i"=>"%M", "s"=>"%S",
							)
					);
			//ifFormat: \"%Y-%m-%d %H:%M:00\", 
			$return_var.= "<script type=\"text/javascript\">
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
			//$return_var.= $tmp_var;
		}
		
		$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_var.= "</div>";
		
		return($return_var);
	}

	
	// поле с выбором
	function frmInputAddField($field_name = "", $field_data = "", $AddButtonName = "", $WindowDataFile = "", $addJsFunc = 'jsAddField',$width_field = "350", $ro = false){
		
		if($AddButtonName == ""){$AddButtonName = "AddButtonName";}
		$return_var = "";
		$arr_ro = array();
		if($ro){$arr_ro = array("readonly"=>"readonly",);}
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_var.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		$return_var.= frmInput(array("type"=>"text", "name"=>$field_name, "id"=>$field_name, "style"=>"width: ".((int)$width_field - 60)."px; float:left;", "value"=>$field_data, ) + $arr_ro);
		if(!$ro){
			$return_var.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"X", "OnClick"=>"".$addJsFunc."('".$field_name."','');", ));
			$return_var.= frmInput(array("type"=>"button", "name"=>$AddButtonName, "id"=>$AddButtonName, "style"=>"width: 28px; float:right;", "value"=>"...", "title"=>$field_name, "class"=>"btn_add", "href"=>"".$WindowDataFile."", ));
		}
		$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_var.= "</div>";
		if(!$ro){
			$return_var.= '<script type="text/javascript">$(function() {$("#'.$AddButtonName.'").nyroModal();});</script>';
		}
		return($return_var);
	}
	
	//для выбора файла
	function frmInputFile($field_name = "", $field_value = "", $attributes = array(),$width_field = "350",$ro = false){
		if($field_name == ""){return(false);}
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_field = "";
		$return_field.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		
		if(!$ro){
			if($field_value == ""){
				$return_field.= frmInput(array("type"=>"file", "name"=>$field_name, "style"=>"width: ".((int)$width_field - 30)."px; ", "value"=>"",) + $attributes);
			}
			else{
				$return_field.= frmInput(array("type"=>"hidden", "name"=>$field_name, "style"=>"", "value"=>$field_value,) + $attributes);
				$arr_f_name = explode("/", $field_value);
				$return_field.= "<span id='fl_".$field_name."' style='padding-left: 3px;'><a href='".$field_value."' target='_blank'>".$arr_f_name[count($arr_f_name)-1]."</a></span>";
				$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"X", "OnClick"=>"
				$('#".$field_name."').val('');
				$('#fl_".$field_name."').html('');
				", "title"=>"Сбросить"));
			}
		}
		else{
			$return_field.= frmInput(array("type"=>"hidden", "name"=>$field_name, "style"=>"", "value"=>$field_value,) + $attributes);
			$arr_f_name = explode("/", $field_value);
			$return_field.= "<span style='padding-left: 3px;'><a href='".$field_value."' target='_blank'>".$arr_f_name[count($arr_f_name)-1]."</a></span>";
		}

		$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_field.= "</div>";
		
		return($return_field);
	}
	
	//для выбора изображения
	function frmInputImage($field_name = "", $field_value = "", $attributes = array(),$width_field = "350",$height_field = "100",$ro = false){
		if($field_name == ""){return(false);}
		$stl_ro = "";if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_field = "";
		$return_field.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		
		if(!$ro){
			if($field_value == ""){
				$return_field.= frmInput(array("type"=>"file", "name"=>$field_name, "style"=>"width: ".((int)$width_field - 30)."px; ", "value"=>"",) + $attributes);
			}
			else{
				$return_field.= frmInput(array("type"=>"hidden", "name"=>$field_name, "style"=>"", "value"=>$field_value,) + $attributes);
				$arr_f_name = explode("/", $field_value);
				$return_field.= "<span id='fl_".$field_name."' style='padding-left: 3px;'><a href='".$field_value."' target='_blank'>".$arr_f_name[count($arr_f_name)-1]."</a></span>";
				$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; float:right;", "value"=>"X", "OnClick"=>"
				$('#".$field_name."').val('');
				$('#fl_".$field_name."').html('');
				$('#img_".$field_name."').html('');
				", "title"=>"Сбросить"));
			}
		}
		else{
			$return_field.= frmInput(array("type"=>"hidden", "name"=>$field_name, "style"=>"", "value"=>$field_value,) + $attributes);
			$arr_f_name = explode("/", $field_value);
			$return_field.= "<span style='padding-left: 3px;'><a href='".$field_value."' target='_blank'>".$arr_f_name[count($arr_f_name)-1]."</a></span>";
		}

		if($field_value != ""){
			if($height_field == 0){
				$return_field.= "<div id='img_".$field_name."' style='width: 99%;padding: 0; margin: 0;'>";
				$return_field.= "<a href='".$field_value."' target='_blank'>";
				$return_field.= "<img src='".$field_value."' style='width:50px;padding: 0; margin: 0;' border='0' title='".$field_value."'>";
				$return_field.= "</a>";
				$return_field.= "</div>";
			}
			else{
				$return_field.= "<div id='img_".$field_name."' style='height: ".$height_field."px;width: 99%;overflow-y: scroll;margin: 0;padding: 1;'>";
				$return_field.= "<a href='".$field_value."' target='_blank'>";
				$return_field.= "<img src='".$field_value."' style='width:100%;padding: 0; margin: 0;' border='0' title='".$field_value."'>";
				$return_field.= "</a>";				
				$return_field.= "</div>";
			}
		}
		
		$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_field.= "</div>";
		
		return($return_field);
	}
	
	
	// поле ввода простого строкового списка
	function frmInputListString($field_name = "", $field_value = "", $width_field, $height_field, $ro = false){
		if($field_name == ""){return(false);}
		$sep_lsstr = "|||";
		$stl_ro = "";
		if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_field = "";
		$return_field.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
		if(!$ro){
			$return_field.= frmInput(array("type"=>"text", "name"=>$field_name."_lsstr", "id"=>$field_name."_lsstr", "style"=>"width: ".((int)$width_field - 86)."px; ", "value"=>"", ));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"add", "OnClick"=>"
			var ".$field_name."_vl = $('#".$field_name."').val();
			var ".$field_name."_ls = $('#".$field_name."_lsstr_area').html();
			".$field_name."_ls = ".$field_name."_ls + '<span>' + $('#".$field_name."_lsstr').val() + '</span><br>';
			$('#".$field_name."_lsstr_area').html(".$field_name."_ls);
			if(".$field_name."_vl == ''){
				".$field_name."_vl = $('#".$field_name."_lsstr').val();
			}
			else{
				".$field_name."_vl = ".$field_name."_vl + '".$sep_lsstr."' + $('#".$field_name."_lsstr').val();
			}
			$('#".$field_name."').val(".$field_name."_vl);
			$('#".$field_name."_lsstr').val('');
			", "title"=>"Добавить запись", ));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"X", "OnClick"=>"$('#".$field_name."_lsstr').val('');", "title"=>"Сбросить строку" ));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"x-all", "OnClick"=>"
				$('#".$field_name."').val('');
				$('#".$field_name."_lsstr_area').html('');
			", "title"=>"Очистить весь список"));
		}
		else{
			//$return_field.= frmInput(array("type"=>"text", "name"=>$field_name,"readonly"=>"readonly", "style"=>"width: ".((int)$width_field - 29)."px;", "value"=>$field_value, ) + $attributes);
		}
		$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
		$return_field.= "</div>";
		$return_field.= frmInput(array("type"=>"hidden", "name"=>$field_name, "id"=>$field_name, "value"=>$field_value, ));
		$return_field.= "<div class=\"sel_field ".$field_name."_lsstr_area\" id=\"".$field_name."_lsstr_area\" style='".$stl_ro."height: ".$height_field."px;width: ".$width_field."px;overflow-y: scroll;margin: 0;padding: 0;'>";
		if($field_value != ""){
			$arrListStr = explode($sep_lsstr, $field_value);
			$cntListStr = 1;
			if(count($arrListStr) > 0){
				foreach($arrListStr as $valListStr){
					$row_id = $field_name."_ls_id_".$cntListStr;
					$return_field.= "<span id='".$row_id."'>";
					$return_field.= "<div style='width: 91%; overflow: hidden; float: left; text-align: left; margin: 3px 0 3px 0; padding: 2px 0 2px 0; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; -khtml-border-radius: 4px;	 color: #444444; background: #ffffff; border: 1px solid #bde5f7;'>";
					$return_field.= $cntListStr.". ";
					$return_field.= $valListStr;
					$return_field.= "</div>";
					if(!$ro){
						$return_field.= "<div style='width: 7%; float: left; text-align: center; margin: 3px 0 3px 0; padding: 2px 0 2px 0; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; -khtml-border-radius: 4px;color: #930000; background: #ffffff; border: 1px solid #ff7171; '>";
						$return_field.= " <a href=\"javascript:void(0);\" title=\"Удалить запись\" OnClick=\"
							if(confirm('Строка № ".$cntListStr." удалится окончательно, после сохранения!\\rПродолжить?')){
								var vl_main = $('#".$field_name."').val();
								vl_main = vl_main.replace('".$sep_lsstr.$valListStr."', '');
								vl_main = vl_main.replace('".$valListStr.$sep_lsstr."', '');
								vl_main = vl_main.replace('".$valListStr."', '');
								vl_main = vl_main.replace('".$sep_lsstr.$sep_lsstr."', '".$sep_lsstr."');
								$('#".$field_name."').val(vl_main);
								$('#".$row_id."').remove();						
							}
							else{
								return(false);
							}
							
						\" title='Удалить строку'>(X)</a>";
						$return_field.= "</div>";
					}
					$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
					$return_field.= "</span>";
					$cntListStr++;
				}
			}
		}
		$return_field.= "</div>";
		
		return($return_field);
	}
	
	// поле ввода простого строкового ссылок
	function frmInputListLink($field_name = "", $field_value = "", $width_field, $height_field, $ro = false){
		if($field_name == ""){return(false);}
		$sep_lslnk	 = "|||";
		$sep_lslnk1	 = "::";

		$stl_ro = "";
		if($ro){$stl_ro = "border: 1px solid #cccccc;";}
		$return_field = "";
		
		if(!$ro){
		
			$return_field.= "<span class=''>Название ссылки:</span><br>";
			$return_field.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
			$return_field.= frmInput(array("type"=>"text", "name"=>$field_name."_lsname", "id"=>$field_name."_lsname", "style"=>"width: ".((int)$width_field - 30)."px; ", "value"=>"", ));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"X", "OnClick"=>"$('#".$field_name."_lsname').val('');", "title"=>"Сбросить имя ссылки" ));
			$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
			$return_field.= "</div>";	
			
			$return_field.= "<span class=''>Ссылка:</span><br>";
			$return_field.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;".$stl_ro."\">";
			$return_field.= frmInput(array("type"=>"text", "name"=>$field_name."_lslnk", "id"=>$field_name."_lslnk", "style"=>"width: ".((int)$width_field - 86)."px; ", "value"=>"", ));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"add", "OnClick"=>"
			var ".$field_name."_vl = $('#".$field_name."').val();
			var ".$field_name."_ls = $('#".$field_name."_lslnk_area').html();
			
			var row_str = '';
			var row_link = '';
			if($('#".$field_name."_lsname').val() == ''){
				row_str = $('#".$field_name."_lslnk').val() + '".$sep_lslnk1."' + $('#".$field_name."_lslnk').val();
				row_link = '<a href=' + $('#".$field_name."_lslnk').val() + ' target=_blank>' + $('#".$field_name."_lslnk').val() + '</a>';
			}
			else{
				row_str = $('#".$field_name."_lslnk').val() + '".$sep_lslnk1."' + $('#".$field_name."_lsname').val();
				row_link = '<a href=' + $('#".$field_name."_lslnk').val() + ' target=_blank>' + $('#".$field_name."_lsname').val() + '</a>';
			}
			
			if($('#".$field_name."_lslnk').val() == ''){
				alert('Необходимо задать ссылку!');
				return(false);
			}
			
			".$field_name."_ls = ".$field_name."_ls + '<span>' + row_link + '</span><br>';
			
			$('#".$field_name."_lslnk_area').html(".$field_name."_ls);
			
			if(".$field_name."_vl == ''){
				".$field_name."_vl = row_str;
			}
			else{
				".$field_name."_vl = ".$field_name."_vl + '".$sep_lslnk."' + row_str;
			}
			
			$('#".$field_name."').val(".$field_name."_vl);
			$('#".$field_name."_lslnk').val('');
			$('#".$field_name."_lsname').val('');
			", "title"=>"Добавить запись", ));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"X", "OnClick"=>"$('#".$field_name."_lslnk').val('');", "title"=>"Сбросить строку" ));
			$return_field.= frmInput(array("type"=>"button", "style"=>"width: 28px; ", "value"=>"x-all", "OnClick"=>"
				$('#".$field_name."').val('');
				$('#".$field_name."_lslnk_area').html('');
			", "title"=>"Очистить весь список"));
			$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
			$return_field.= "</div>";	
		}
		else{
			//$return_field.= frmInput(array("type"=>"text", "name"=>$field_name,"readonly"=>"readonly", "style"=>"width: ".((int)$width_field - 29)."px;", "value"=>$field_value, ) + $attributes);
		}

		$return_field.= frmInput(array("type"=>"hidden", "name"=>$field_name, "id"=>$field_name, "value"=>$field_value, ));
		$return_field.= "<div class=\"sel_field ".$field_name."_lslnk_area\" id=\"".$field_name."_lslnk_area\" style='".$stl_ro."height: ".$height_field."px;width: ".$width_field."px;overflow-y: scroll;margin: 0;padding: 0;'>";
		if($field_value != ""){
			$arrListLink = explode($sep_lslnk, $field_value);
			$arrListLink1 = array();
			$cntListLink = 1;
			if(count($arrListLink) > 0){
				foreach($arrListLink as $valListLink){
					$row_id = $field_name."_ls_link_id_".$cntListLink;
					
					$arrListLink1 = explode($sep_lslnk1, $valListLink);
					$return_field.= "<span id='".$row_id."'>";


					$return_field.= "<div style='height: 20px;width: 91%; overflow: hidden; float: left; text-align: left; margin: 3px 0 3px 0; padding: 2px 0 2px 0; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; -khtml-border-radius: 4px;color: #444444; background: #ffffff; border: 1px solid #bde5f7;'>";
					$return_field.= $cntListLink.". ";

					$return_field.= "<a href='".$arrListLink1[0]."' title='".$arrListLink1[0]."' target='_blank'>".$arrListLink1[1]."</a>";

					$return_field.= "</div>";
					if(!$ro){
						$return_field.= "<div style='height: 20px;width: 7%; float: left; text-align: center; margin: 3px 0 3px 0; padding: 2px 0 2px 0; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; -khtml-border-radius: 4px;color: #930000; background: #ffffff; border: 1px solid #ff7171; '>";
						$return_field.= " <a href=\"javascript:void(0);\" title=\"Удалить запись\" OnClick=\"
							if(confirm('Строка № ".$cntListLink." удалится окончательно, после сохранения!\\rПродолжить?')){
								var vl_main = $('#".$field_name."').val();
								vl_main = vl_main.replace('".$sep_lslnk.$valListLink."', '');
								vl_main = vl_main.replace('".$valListLink.$sep_lslnk."', '');
								vl_main = vl_main.replace('".$valListLink."', '');
								vl_main = vl_main.replace('".$sep_lslnk.$sep_lslnk."', '".$sep_lslnk."');
								$('#".$field_name."').val(vl_main);
								$('#".$row_id."').remove();							
							}
							else{
								return(false);
							}

						\" title='Удалить строку'>(X)</a>";
						$return_field.= "</div>";
					}
					$return_field.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
					$return_field.= "</span>";
					$cntListLink++;
				}
			}
		}
		$return_field.= "</div>";
		
		return($return_field);
	}
	
	
	// название поля на форме
	function frmGetName($TblSetting,$key){
		return(strip_tags(($TblSetting[$key]['description']!='')?$TblSetting[$key]['description']:$TblSetting[$key]['name']));
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// TPL

	function GetTpl($type_tpl = "", $arrTplData = array(), $TblDefTplPath = ""){
		if($type_tpl == ""){return(false);}
		if($TblDefTplPath == ""){return(false);}
		$return_var	 = "";
		$type_tpl	 = trim($type_tpl);
		
		if(file_exists($TblDefTplPath."/".$type_tpl.".php")){include($TblDefTplPath."/".$type_tpl.".php");return($return_var);}else{return(false);}

		return($return_var);
	}

	function SetTplVar($arrData, $tpl = ""){
		if($tpl == "" || !is_array($arrData)){return(false);}
		
		foreach($arrData as $key => $val){
			$tpl = str_replace ("{%".$key."%}", $val, $tpl);
		}
		return($tpl);
	}
	
	function SetTplLnk($tmpArrSetField, $q_data, $tmpLnk = ""){
		
		if($tmpLnk == "" || !is_array($tmpArrSetField) || !is_array($q_data)){return("#");}
		
		foreach($tmpArrSetField as $key_lnk => $val_lnk){
			if(isset($q_data[$key_lnk])){
				$tmpLnk =  str_replace("{%".$key_lnk."%}", $q_data[$key_lnk], $tmpLnk);
			}
		}
		return($tmpLnk);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// получение данных из другой таблицы
	function GetRowDataByPrimaryKey(&$sql,&$ut,$TblSetting,$primarykey_data = 0){
		
		$result = $sql->sql_query("SELECT * FROM `".$sql->prefix_db.$TblSetting["table"]['name']."` WHERE `".$TblSetting["table"]['PrimaryKey']."`='".$primarykey_data."'");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			return($query);
		}
		else{
			$ut->utLog(__FILE__ ." не могу получить запись");
			return(false);
		}
	}


?>