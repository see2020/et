<?php
	// ��������� ����� ��������������
	// �������������� ������
	if($_GET['event']=="edit"){
		if(isset($_POST['NoSave'])){Redirect("?tbl=".$TblSetting["table"]["name"]."&pagenum=".$pg."",0);}
		if(isset($_POST['Save'])){
			
			// ����������� ��������� ������ �� ������� � �������
			// ���� � ������������ � ������ ������� ���� ����������� ������ "new", "read", "edit", ...
			if(usr_AccessTable($TblSetting["table"]["name"],"new")){$u_access = true;}
			else{
				echo Message("������������ ���� �� ��������� ����� ������� te3", "error");
				$u_access = false;
			}
			if($u_access){
				
				// ���������� ����� �������������� 
				include(GetIncFile($arrSetting,"inc.tables.edit.form.save.php", $TblSetting["table"]["name"]));
			}
		}
		else{
			// ����� �������������� 
			include(GetIncFile($arrSetting,"inc.tables.edit.form.php", $TblSetting["table"]["name"]));
		}
	}
?>