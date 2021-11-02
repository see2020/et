<?php
	// обработка выхода
	session_start();
	$cmsPathRelative = ".";
	include($cmsPathRelative."/config.php");
	//if(isset($_SESSION[D_NAME])){
		unset($_SESSION[D_NAME]['user']);
	//}
	Redirect("tables.php?tbl=".$arrSetting['Table']['DefaultTable']);
	
?>