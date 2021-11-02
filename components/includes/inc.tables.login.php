<?php
	$UserType = "root";
	if($arrSetting['Access']['UsePassword']){
		if(!isset($_SESSION[D_NAME]['user']['Password']) || !isset($_SESSION[D_NAME]['user']['Login'])){
			echo Message("<strong>".$arrSetting["Main"]["Name"]."</strong>");
			echo Message("Необходимо выполнить вход");
			if(isset($_POST["go_tbl"])){

				if($_POST["tbl_login"] == "" || $_POST["tbl_pass"] == ""){
					$ut->utLog(__FILE__ . " Не верный пароль. Вход не выполнен. _POST: ".ParseArrForLog($_POST));
					echo Message("Пустые логин или пароль<br><a href='?tbl=".$TblSetting["table"]["name"]."'>Повторить</a>","error");
					exit;
				}
				if($qUser = usr_GetUser($sql,$_POST["tbl_login"],$_POST["tbl_pass"])){
					//$_SESSION[D_NAME]['user']					 = $qUser;
					$_SESSION[D_NAME]['user']['id']				 = $qUser["id"];
					$_SESSION[D_NAME]['user']['id_root']		 = $qUser["id_root"];
					$_SESSION[D_NAME]['user']['Login']			 = $qUser["login"];
					$_SESSION[D_NAME]['user']['Password']		 = $qUser["password"];

					$_SESSION[D_NAME]['user']['usrAccess']			 = $qUser["usrAccess"]; // уровень разрешений для пользователя 
					$_SESSION[D_NAME]['user']['usrtblAccess']		 = $qUser["usrtblAccess"]; // уровень разрешений для таблиц пользователя 
					$_SESSION[D_NAME]['user']['table_default']		 = $qUser["table_default"]; // таблица по умолчания для пользователя или группы
					
					$qUser_for_log			 = $qUser;
					
					setcookie('tbl_login', $qUser["login"],time()+60*60*24*84);
					
					$ut->utLog("Выполнен вход. ".ParseArrForLog($qUser_for_log));
					Redirect("?tbl=".$TblSetting["table"]["name"]);
				}
				else{
					unset($_COOKIE['tbl_login']);
					$ut->utLog(__FILE__ . " Не верный пароль. Вход не выполнен. _SESSION: ".ParseArrForLog($_POST));
					echo Message("Не верный логин или пароль<br><a href='?tbl=".$TblSetting["table"]["name"]."'>Повторить</a>","error");
					exit;
				}
			}
			else{
				echo "<div style=\"text-align: center;\">
				<form method=\"post\"  name=\"go_page\" id=\"go_page\" action=\"\">
				Login:<br><input type=\"text\" name=\"tbl_login\" id=\"tbl_login\" value=\"".((isset($_COOKIE['tbl_login']))?$_COOKIE['tbl_login']:"")."\"><br>
				Password:<br><input type=\"password\" name=\"tbl_pass\" id=\"tbl_pass\" value=\"\"><br>
				<input value=\"Ok\"  name=\"go_tbl\" type=\"submit\">
				</form>
				</div>";	
				exit;				
			}
		}
	}
	else{
		$_SESSION[D_NAME]['user']['UserType']	 = $UserType;
		Redirect("?tbl=".$TblSetting["table"]["name"]);exit;
	}
		
?>