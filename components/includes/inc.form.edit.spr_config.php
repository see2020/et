<?php
	if($TblSetting["table"]['is_directory'] == "1"){
		// ���� ��� ����� ��� �������������� ����������� 
		$arrTableSpr["table_name"]			 = $TblSetting["table"]['name'];
		$arrTableSpr["order_by"]			 = $TblSetting["table"]["directory_type"]." desc".(($TblSetting["table"]['order']!="")?", ".$TblSetting["table"]['order']:"");
		$arrTableSpr["PrimaryKey"]			 = $TblSetting["table"]['PrimaryKey'];
		$arrTableSpr["id"]					 = $TblSetting["table"]['PrimaryKey']; // ������������� ������
		$arrTableSpr["id_root"]				 = $TblSetting["table"]['directory_root']; // ���� � ���������� ������������ ������
		$arrTableSpr["field_name"]			 = $TblSetting["table"]['directory_name']; // �������� ���� ��� ������ � �������
		$arrTableSpr["field_status"]		 = $TblSetting["table"]['StatusField']; // ���� ������� 
		$arrTableSpr["field_type_row"]		 = $TblSetting["table"]['directory_type']; // ��� ������ ��������� ��� ��� 0/1
		//$arrTableSpr["input_return_name"]	 = "test_field_add"; // � ����� ���� ���������� �������� �������� ������
		$arrTableSpr["uplevel_link"]		 = "";
	}
?>