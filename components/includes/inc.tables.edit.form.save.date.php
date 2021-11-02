<?php
	$arr[$TblSetting[$key]["name"]] = ($_POST[$TblSetting[$key]["name"]] == "")?0:strtotime($_POST[$TblSetting[$key]["name"]]);
?>