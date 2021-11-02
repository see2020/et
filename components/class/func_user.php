<?php
	
	function usr_GetUserTables($sql,$id_user = 0){
		$ret_arr = array();
		if($id_user == 0){return($ret_arr);}
		$rTbl = $sql->sql_query("select * from `".$sql->prefix_db."users_tbl` where id_user = '".$id_user."' and st='1'");
		if($sql->sql_rows($rTbl)){
			while($qTbl = $sql->sql_array($rTbl)){
				$ret_arr[] = $qTbl["table_name"];	
			}
			return($ret_arr);
		}
		else{
			return($ret_arr);
		}
	}
	
	function usr_GetUserTablesAndAccess($sql,$id_user = 0){
		$ret_arr = array();
		if($id_user == 0){return($ret_arr);}
		
		$rTbl = $sql->sql_query("select * from `".$sql->prefix_db."users_tbl` where id_user = '".$id_user."' and st='1'");
		if($sql->sql_rows($rTbl)){
			while($qTbl = $sql->sql_array($rTbl)){
				$ret_arr[$qTbl["table_name"]] = trim($qTbl["user_type"]);	
			}
			return($ret_arr);
		}
		else{
			return($ret_arr);
		}
	}
	
	function usr_GetUser($sql,$login,$passw){
		$rUser = $sql->sql_query("SELECT * FROM `".$sql->prefix_db."users` WHERE login = '".trim($login)."' AND password='".trim($passw)."' AND st='1'");
		if($sql->sql_rows($rUser)){
			$qUser = $sql->sql_array($rUser);
			$qUser["user_type"]	 = trim($qUser["user_type"]);
			$ret_arr			 = $qUser;
			
			// если в группе, получаем разрешение на всю группу
			if($qUser["id_root"] > 0){
				$rUser1 = $sql->sql_query("SELECT * FROM `".$sql->prefix_db."users` WHERE id = '".$qUser["id_root"]."'");
				if($sql->sql_rows($rUser1)){
					$qUser1 = $sql->sql_array($rUser1);
					// получаем разрешение группы
					$ret_arr["usrAccess"]		 = $qUser1["user_type"];
					$ret_arr["table_default"]	 = $qUser1["table_default"]; // таблица по умолчанию для группы
				}
			}
			// если таблица у пользователя установлена индивидуально
			if($qUser["user_type"] != ""){
				$ret_arr["table_default"] = $qUser["table_default"];
			}
			// если разрешение у пользователя установлено индивидуально
			if($qUser["user_type"] != ""){
				$ret_arr["usrAccess"] = $qUser["user_type"];
			}
			$ret_arr["usrtblAccess"] = array();
			// получаем список таблиц группы пользователя, с разрешениями
			if($qUser["id_root"] != 0){
				$ret_arr["usrtblAccess"] = usr_GetUserTablesAndAccess($sql,$qUser["id_root"]);// получаем таблицы c разрешениями для группы
			}
			// получаем список таблиц пользователя, с разрешениями
			// и проверяем, если в ["usrtblAccess"] уже есть такая таблица переписываем разрешение на заданное у пользователя
			// если нет, то добавляем
			$tmpUserTableAccess = usr_GetUserTablesAndAccess($sql,$qUser["id"]);// получаем таблицы c разрешениями для пользователя
			foreach($tmpUserTableAccess as $key => $val){
				$ret_arr["usrtblAccess"][$key] = $val;
			}
			return($ret_arr);
		}
		else{
			return(false);
		}		
	}
	
	// проверяем разрешиния пользователя 
	function usr_Access($access_level = ""){
		if(!isset($_SESSION[D_NAME]['user'])){return(FALSE);}
		if(empty($_SESSION[D_NAME]['user']['Access'])){return(FALSE);}
		if($access_level == ""){return(FALSE);}		
		$access_level = trim($access_level);
		
		if(in_array($access_level,explode(",", $_SESSION[D_NAME]['user']['Access']))){return(TRUE);}else{return(FALSE);}
	}
	// проверяем разрешения пользователя на таблицу
	function usr_AccessTable($table_name = "", $access_level = ""){
		if(!isset($_SESSION[D_NAME]['user'])){return(FALSE);}
		if($table_name == ""){return(FALSE);}	
		$table_name = trim($table_name);
		if(!isset($_SESSION[D_NAME]['user']['usrtblAccess'][$table_name])){return(FALSE);}
		if($_SESSION[D_NAME]['user']['usrtblAccess'][$table_name] == ""){return(FALSE);}
		if(!isset($_SESSION[D_NAME]['user']['AccessTable'][$table_name])){return(FALSE);}
		if($_SESSION[D_NAME]['user']['AccessTable'][$table_name] == ""){return(FALSE);}
		
		$access_level = trim($access_level);
		if($access_level == ""){
			$access_level	 = $_SESSION[D_NAME]['user']['usrtblAccess'][$table_name];
		}
		if(in_array($access_level,explode(",", $_SESSION[D_NAME]['user']['AccessTable'][$table_name]))){return(TRUE);}else{return(FALSE);}
	}
	
?>