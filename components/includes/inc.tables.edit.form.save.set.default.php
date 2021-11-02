<?php

	// заполнение не активных полей значениями по умолчанию
	// только для полей с типом hide и отключенных для редактирования
	if($TblSetting[$key]["editable"] == 0 && $TblSetting[$key]["type"] != "support"){
		if($TblSetting[$key]["default"] != ""){
			$arr[$TblSetting[$key]["name"]] = $TblSetting[$key]["default"];							
		}
	}

?>