<?php
	$cmsPathRelative = ".";
	include($cmsPathRelative."/config.php");
	Redirect("tables.php?tbl=".$arrSetting['Table']['DefaultTable']);	
	
?>