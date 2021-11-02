<?php
	if($_POST[$TblSetting[$key]["name"]] == "" && $TblSetting[$key]["default"] != ""){
		$_POST[$TblSetting[$key]["name"]] = $TblSetting[$key]["default"];
	}
	$arr[$TblSetting[$key]["name"]] = $_POST[$TblSetting[$key]["name"]];
?>