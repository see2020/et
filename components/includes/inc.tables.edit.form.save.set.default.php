<?php

	// ���������� �� �������� ����� ���������� �� ���������
	// ������ ��� ����� � ����� hide � ����������� ��� ��������������
	if($TblSetting[$key]["editable"] == 0 && $TblSetting[$key]["type"] != "support"){
		if($TblSetting[$key]["default"] != ""){
			$arr[$TblSetting[$key]["name"]] = $TblSetting[$key]["default"];							
		}
	}

?>