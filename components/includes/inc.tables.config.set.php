<?php
	// ���� �����
	$arrTypeField = tblTypeField();
	
	// ������ �������� �����
	$arrFieldTpl = tblFieldTpl();
	
	// ������ �������� �������
	$arrTableTpl = tblTableTpl();

	// ������ �������� ������ ������
	$arrTableListTpl = tblTableListTpl();
	
	//��������� �����
	$arrConfigField = array();

	// 
	$TblSetting = array();
	if(file_exists($arrSetting['Path']['tbldata']."/".$TblName."/".$TblName.".php")){
		include($arrSetting['Path']['tbldata']."/".$TblName."/".$TblName.".php");
		$TblSetting = $arrConfig;
	}
	
	//$TblList = array();
	// if(file_exists($arrSetting['Path']['tbldata']."/tList.php")){
		// include($arrSetting['Path']['tbldata']."/tList.php");
		// $TblList = $arrConfig;
	// }
	
	$TblFieldPrimaryKey	 = $TblSetting['table']['PrimaryKey'];
	$IdForChange		 = (isset($_GET['id']))?$_GET['id']:0;
	$PageLink			 = "?tbl=".$TblName;

	include(GetIncFile($arrSetting,"inc.tables.config.set.settingall.php",$TblSetting["table"]["name"]));
	
?>