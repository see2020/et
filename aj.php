<?php
	header('Content-Type: text/html; charset=windows-1251;');
	include("cfg.php");
	include(ET_PATH_RELATIVE . DS . "config.php");
	$sql->sql_connect();
	//aj.php?tbl=incident&id=6683&event=change&field=st&af=varbool
	//./aj.php?af=spr.autocomplete&tbl_spr=&field_type=
	
	$TblNameDefault	 = (!isset($arrSetting['Table']['DefaultTable']) || $arrSetting['Table']['DefaultTable'] == "")?"":$arrSetting['Table']['DefaultTable'];
	$TblName		 = (isset($_GET['tbl']))?trim($_GET['tbl']):$TblNameDefault;
	$PageLink		 = "";
	
	$TblSetting = array();
	include(GetIncFile($arrSetting,"inc.tables.config.set.php", ""));

	function ajCheckAlias($alias = ""){
		if($alias == ""){return($alias);}
		$alias = strip_tags($alias);
		$alias = preg_replace("/[^a-zA-Z0-9\_\.\s]/","",$alias);
		$alias = preg_replace("/ {2,}/", " ", $alias);
		$alias = trim($alias);
		$alias = strtr($alias,array(" "=>"",));
		return($alias);
	}
	include(GetIncFile($arrSetting,"inc.aj.".ajCheckAlias($_GET["af"]).".php", $TblSetting["table"]["name"]));
	$sql->sql_close();
?>