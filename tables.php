<?php
	session_start();
	
	$cmsPathRelative = ".";
	include($cmsPathRelative."/config.php");

	$sql->sql_connect();

	// ���� �� ������ ��� ������� ���������� ��� �� ���������
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
		$_SESSION[D_NAME]['user']['usrAccess']		 = "root"; // ������� ���������� ��� ������������ 
		$_SESSION[D_NAME]['user']['usrtblAccess']	 = array(); // ������� ���������� ��� ������ ������������ 
		
		//table_default
		if($result1 = $sql->sql_ShowTableFromBD()){
			foreach($result1 as $key => $t_name){
				$tName = str_replace($sql->prefix_db, "", $t_name);
				$_SESSION[D_NAME]['user']['usrtblAccess'][$tName]	 = "root"; // ������� ���������� ��� ������ ������������ 
			}		
		}
	}
		
	if($TblSetting["table"]["name"] == ""){
		$ut->utLog("�� ������ ��������� �������. ".__FILE__);
		echo Message("�� ������ ��������� �������<br><a href='./quit.php'>�����</a> | <a href='./tables.php'>�� �������</a>", "error");
		exit;
	}
	
	if($arrSetting['Access']['UsePassword']){
		//if(!usr_AccessTable($TblSetting["table"]["name"],$_SESSION[D_NAME]['user']['usrtblAccess'][$TblSetting["table"]["name"])){
		if(!usr_AccessTable($TblSetting["table"]["name"])){
			echo Message("������������ ���� �� ������ � �������. error 2<br><a href='./quit.php'>�����</a> | <a href='./tables.php'>�� �������</a>", "error");
			$ut->utLog("������������ ���� �� ������ � �������. ������ ������� �������. usr_AccessTable(".$TblSetting["table"]["name"]."), _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']).__FILE__);
			exit;
		}
	}
	
	/* $u_access = true;
	// ����������� ��������� ������ �� ������� � �������
	// ���� � ������������ � ������ ������� ���� ����������� ������ "new", "read", "edit", ...
	if(usr_AccessTable($TblSetting["table"]["name"],"edit")){$u_access = true;}
	//if(usr_Access("admin")){$u_access = true;}
	else{$u_access = false;} */
	
	include(GetIncFile($arrSetting,"inc.tables.serach.link.php", $TblSetting["table"]["name"]));
	
	// ������ �������
	$PrintPage = false;
	if(isset($_GET['print'])){ $PrintPage = true; }
	
	// ���� ����������
	$TblDefTplPath = $arrSetting['Path']['tpl']."/".$arrSetting['Table']['DefaultTpl'];
	if(isset($TblSetting['table']['tpl']) && $TblSetting['table']['tpl']!=""){$TblDefTplPath = $arrSetting['Path']['tpl']."/".$TblSetting['table']['tpl'];}
	
	if(file_exists($TblDefTplPath."/top.php")){include($TblDefTplPath."/top.php");}
	
	// ��������� ������� ����� ���������
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["BeforeLoading"];
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
		// ���������� ���������� ��������
		include(GetIncFile($arrSetting,"inc.tables.".$_GET["event"].".php", $TblSetting["table"]["name"]));
	}
		
	// ��������� ������� ����� �������� ������ �������
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["AfterLoading"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
		
	if(file_exists($TblDefTplPath."/bottom.php")){include($TblDefTplPath."/bottom.php");}
	$sql->sql_close();

?>