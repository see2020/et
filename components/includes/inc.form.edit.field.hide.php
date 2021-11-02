<?php
	echo frmInput(array(
		"type"=>"hidden", 
		"name"=>$TblSetting[$key]['name'], 
		"value"=>$qChange[$TblSetting[$key]['name']], 
		"id"=>$TblSetting[$key]['name'], 
	));
?>