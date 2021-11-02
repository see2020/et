<?php
	echo frmInput(array(
		"type" => "hidden", 
		"name" => $TblSetting[$key]["name"]."_tp", 
		"value" => $_GET[$TblSetting[$key]["name"]."_tp"]??"", 
		"id" => $TblSetting[$key]["name"]."_tp", 
	));
	echo frmInput(array(
		"type" => "hidden", 
		"name" => $TblSetting[$key]["name"], 
		"value" => $_GET[$TblSetting[$key]["name"]]??"", 
		"id" => $TblSetting[$key]["name"], 
	));
?>