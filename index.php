<?php
//	$cmsPathRelative = ".";
	include("cfg.php");
	include(ET_PATH_RELATIVE . DS ."config.php");
	Redirect("tables.php?tbl=".$arrSetting['Table']['DefaultTable']);
