<?php
	$allSettings["arrSetting"]	 = $arrSetting;
	$allSettings["TblSetting"]	 = $TblSetting;
	$allSettings["TblPath"]		 = (isset($arrTblPath))?$arrTblPath:"";
	$allSettings["ut_class"]	 = $ut;
	
	// echo Message("1");
	// echo Message(ParseArrForLog($allSettings["TblPath"]));
	
	// не текстовые обработчики полей
	// для списка
	$allSettings["includes"]["list_fields"]	 = array("support","date","directory_id","selectarea","radiobutton","varbool","file","link","image","password","list_string","list_link");
	// для формы
	$allSettings["includes"]["form_fields"]	 = array("date","directory_name","directory_id","selectarea","radiobutton","number","textarea","varbool","file","image","hide","password","list_string","list_link");
	
	// уровень доступа
	//$allSettings["access"][$_SESSION[D_NAME]['user']['UserType']]
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