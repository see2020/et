<?php
	// обработка формы редактирования
	// редактирование записи
	if($_GET['event']=="edit"){
		if(isset($_POST['NoSave'])){Redirect("?tbl=".$TblSetting["table"]["name"]."&pagenum=".$pg."",0);}
		if(isset($_POST['Save'])){
			
			// доступность проверяем только по доступу к таблице
			// если у пользователя к данной таблице есть определнный доступ "new", "read", "edit", ...
			if(usr_AccessTable($TblSetting["table"]["name"],"new")){$u_access = true;}
			else{
				echo Message("Недостаточно прав на изменение этого раздела te3", "error");
				$u_access = false;
			}
			if($u_access){
				
				// сохранения формы редактирования 
				include(GetIncFile($arrSetting,"inc.tables.edit.form.save.php", $TblSetting["table"]["name"]));
			}
		}
		else{
			// форма редактирования 
			include(GetIncFile($arrSetting,"inc.tables.edit.form.php", $TblSetting["table"]["name"]));
		}
	}
?>