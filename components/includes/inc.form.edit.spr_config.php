<?php
	if($TblSetting["table"]['is_directory'] == "1"){
		// если эта форма для редактирования справочника 
		$arrTableSpr["table_name"]			 = $TblSetting["table"]['name'];
		$arrTableSpr["order_by"]			 = $TblSetting["table"]["directory_type"]." desc".(($TblSetting["table"]['order']!="")?", ".$TblSetting["table"]['order']:"");
		$arrTableSpr["PrimaryKey"]			 = $TblSetting["table"]['PrimaryKey'];
		$arrTableSpr["id"]					 = $TblSetting["table"]['PrimaryKey']; // идентификатор записи
		$arrTableSpr["id_root"]				 = $TblSetting["table"]['directory_root']; // поле с значениями родительской записи
		$arrTableSpr["field_name"]			 = $TblSetting["table"]['directory_name']; // название поля для вывода в списках
		$arrTableSpr["field_status"]		 = $TblSetting["table"]['StatusField']; // поле статуса 
		$arrTableSpr["field_type_row"]		 = $TblSetting["table"]['directory_type']; // тип записи категория или нет 0/1
		//$arrTableSpr["input_return_name"]	 = "test_field_add"; // в какое поле возвращать значение выбрнной записи
		$arrTableSpr["uplevel_link"]		 = "";
	}
?>