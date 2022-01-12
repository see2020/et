<?php
	// типы полей
	//$arrTypeField = tblTypeField();
	
	// шаблон настроек полей
	//$arrFieldTpl = tblFieldTpl();
	
	// шаблон настроек таблицы
	//$arrTableTpl = tblTableTpl();

	// шаблон настроек списка таблиц
	//$arrTableListTpl = tblTableListTpl();
	
	//настройки полей
	//$arrConfigField = array();

	$TblSetting = array();
	if(file_exists($arrSetting['Path']['tbldata']."/".$TblName."/".$TblName.".php")){
		include($arrSetting['Path']['tbldata']."/".$TblName."/".$TblName.".php");
		$TblSetting = $arrConfig;
	}

	$TblFieldPrimaryKey	 = $TblSetting['table']['PrimaryKey'];
	//$IdForChange		 = (isset($_GET['id']))?$_GET['id']:0;
	$PageLink			 = "?tbl=".$TblName;

	//include(GetIncFile($arrSetting,"inc.tables.config.set.settingall.php",$TblSetting["table"]["name"]));

	$allSettings["arrSetting"]	 = $arrSetting;
	$allSettings["TblSetting"]	 = $TblSetting;
	$allSettings["TblPath"]		 = (isset($arrTblPath))?$arrTblPath:"";
	$allSettings["ut_class"]	 = $ut;

	// не текстовые обработчики полей
	// для списка
	$allSettings["includes"]["list_fields"]	 = array("support","date","directory_id","selectarea","radiobutton","varbool","file","link","image","password","list_string","list_link");
	// для формы
	$allSettings["includes"]["form_fields"]	 = array("date","datetime","directory_name","directory_id","selectarea","radiobutton","number","textarea","varbool","file","image","hide","password","list_string","list_link");

	// уровень доступа
	$allSettings["access"]["read"]	 = "read";
	$allSettings["access"]["new"]	 = "read,new";
	$allSettings["access"]["edit"]	 = "read,new,edit";
	$allSettings["access"]["admin"]	 = "read,new,edit,admin";
	$allSettings["access"]["root"]	 = "read,new,edit,admin,root";
	//$allSettings["access"][""] = "";

	if(isset($_SESSION[D_NAME]['user'])){
		$usrAccess = '';
		if($_SESSION[D_NAME]['user']['usrAccess'] != ''){
			$usrAccess = $_SESSION[D_NAME]['user']['usrAccess'];
		}
		$_SESSION[D_NAME]['user']['Access'] = $allSettings["access"][$usrAccess];

		foreach($_SESSION[D_NAME]['user']['usrtblAccess'] as $key=>$val){
			$_SESSION[D_NAME]['user']['AccessTable'][$key] = $allSettings["access"][$val];
		}
	}
	
?>