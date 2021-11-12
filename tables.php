<?php
/**
 * tables.php - основной файл вывода данных из таблиц
 * здесь происходит подключение настроек полей дл€ форм и списка
 */
	session_start();

	include("cfg.php");
	include(ET_PATH_RELATIVE . DS . "config.php");

	$sql->sql_connect();

	// если не задано им€ таблицы используем им€ по умолчанию
	$TblNameDefault	 = (!isset($arrSetting['Table']['DefaultTable']) || $arrSetting['Table']['DefaultTable'] == "")?"":$arrSetting['Table']['DefaultTable'];
	if(isset($_SESSION[D_NAME]['user']['table_default']) && $_SESSION[D_NAME]['user']['table_default'] != ""){$TblNameDefault = $_SESSION[D_NAME]['user']['table_default'];}
	$TblName		 = (isset($_GET['tbl']))?trim($_GET['tbl']):$TblNameDefault;
	$PageLink		 = "";
	
	$TblSetting = array();
	include(GetIncFile($arrSetting,"inc.tables.config.set.php", ""));
	
	if($arrSetting['Access']['UsePassword']){include(GetIncFile($arrSetting,"inc.tables.login.php", ""));}
	else{
		unset($_SESSION[D_NAME]['user']['usrTablesList']);
		
		$_SESSION[D_NAME]['user']['id']				 = 0;
		$_SESSION[D_NAME]['user']['id_root']		 = 0;
		$_SESSION[D_NAME]['user']['Login']			 = "";
		$_SESSION[D_NAME]['user']['Password']		 = "";
		$_SESSION[D_NAME]['user']['usrAccess']		 = "root"; // уровень разрешений дл€ пользовател€ 
		$_SESSION[D_NAME]['user']['usrtblAccess']	 = array(); // уровень разрешений дл€ таблиц пользовател€ 
		
		//table_default
		if($result1 = $sql->sql_ShowTableFromBD()){
			foreach($result1 as $key => $t_name){
				$tName = str_replace($sql->prefix_db, "", $t_name);
				$_SESSION[D_NAME]['user']['usrtblAccess'][$tName]	 = "root"; // уровень разрешений дл€ таблиц пользовател€ 
			}		
		}
	}
		
	if($TblSetting["table"]["name"] == ""){
		$ut->utLog("Ќе задана стартова€ таблица. ".__FILE__);
		echo Message("Ќе задана стартова€ таблица<br>
			скорее всего необходимо выполнить автонастройку<br>
			<a href='./quit.php'>¬ыход</a> | <a href='./tables.php'>Ќа главную</a>", "error");
		exit;
	}
	
	if($arrSetting['Access']['UsePassword']){
		if(!usr_AccessTable($TblSetting["table"]["name"])){
			echo Message("Ќедостаточно прав на доступ к разделу. error 2<br><a href='./quit.php'>¬ыход</a> | <a href='./tables.php'>Ќа главную</a>", "error");
			$ut->utLog("Ќедостаточно прав на доступ к разделу. низкий уровень доступа. usr_AccessTable(".$TblSetting["table"]["name"]."), _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']).__FILE__);
			exit;
		}
	}
	
	include(GetIncFile($arrSetting,"inc.tables.serach.link.php", $TblSetting["table"]["name"]));
	
	// печать таблицы
	$PrintPage = false;
	if(isset($_GET['print'])){ $PrintPage = true; }
	
	// тема оформлени€
	$TblDefTplPath = $arrSetting['Path']['tpl'] . DS . $arrSetting['Table']['DefaultTpl'];
	if(isset($TblSetting['table']['tpl']) && $TblSetting['table']['tpl']!=""){$TblDefTplPath = $arrSetting['Path']['tpl'] . DS . $TblSetting['table']['tpl'];}
	
	if(file_exists($TblDefTplPath . DS . "top.php")){include($TblDefTplPath . DS . "top.php");}
	
	// выполн€ем функцию перед загрузкой
	$func_file = $arrSetting["Path"]["tbldata"] . DS . $TblSetting["table"]["name"] . DS . "tFunction" . DS . $TblSetting["table"]["BeforeLoading"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
	
	if(!isset($_GET['event'])){
		if(!isset($_GET["print"])){
			include(GetIncFile($arrSetting,"inc.tables.list.php", $TblSetting["table"]["name"]));
		}
		else{
			include(GetIncFile($arrSetting,"inc.tables.list.print.php", $TblSetting["table"]["name"]));
		}
	}
	else{
		// подключаем внутренние страницы
		include(GetIncFile($arrSetting,"inc.tables.".$_GET["event"].".php", $TblSetting["table"]["name"]));
	}
		
	// выполн€ем функцию после загрузки списка таблицы
	$func_file = $arrSetting["Path"]["tbldata"] . DS . $TblSetting["table"]["name"] . DS . "tFunction" . DS . $TblSetting["table"]["AfterLoading"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
		
	if(file_exists($TblDefTplPath . DS . "bottom.php")){include($TblDefTplPath . DS . "bottom.php");}
	$sql->sql_close();

?>