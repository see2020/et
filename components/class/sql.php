<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// 2013
// файл: sql.php
// класс: class_sql
// класс для работы с mysql
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
//defined('_BLDEXEC') or die('Restricted access');
class class_sql{


	var $sqlArrSetting = array();
	
	var $prefix_db; //префикс таблиц 
	var $conn_id; //идентификатор открытой базы 
	var $sql_err; //ошибка MySQL 
	var $sql_QueryString; //Содержит строку запроса(нужно для логов)	
	var $sql_insertLastId; // сюда помещаем последний новосозданный идентификатор записи 
	
    function __construct(&$arrSetting){
		
		// $arrSetting['MySQL']['login']		 = "denis";
		// $arrSetting['MySQL']['passwd']		 = "denis";
		// $arrSetting['MySQL']['database']	 = "";
		// $arrSetting['MySQL']['host']		 = "localhost";
		// $arrSetting['MySQL']['prefix_db']	 = "kd_";
		// $arrSetting['MySQL']['codepage']	 = "cp1251";
		// $arrSetting['Path']['log']						 = $_SERVER['DOCUMENT_ROOT']."/log";
		// $arrSetting['DateTime']['DateTimeCorrection']	 = 0;
		// $arrSetting["Log"]['sqlLogOnOff']				 = 1;
		// $arrSetting["Log"]['sqlLogMySQLErrorOnOff']		 = 1;
		// $arrSetting["Log"]['sqlLogMySQLWorkOnOffFull']	 = 1;
		// $arrSetting["Log"]['sqlDelete']	 = 1;
		// $arrSetting["Log"]['sqlUpdate']	 = 1;
		
		$this->sqlArrSetting = $arrSetting;
		$this->prefix_db	 = $arrSetting['MySQL']['prefix_db'];
		
    }

	// логи 
	function sqlSaveLog($f_cont = "", $f_type = ""){
		$f_name = $this->sqlArrSetting["Path"]['log']."/mysql_".$f_type."_".date("Y-m-d",(time()+$this->sqlArrSetting["DateTime"]['DateTimeCorrection'])).".log";
		$f_content = date("Y.m.d H:i:s",(time()+$this->sqlArrSetting["DateTime"]['DateTimeCorrection']))." --- ".str_replace(array("\r", "\n", "\t"), " ", $f_cont)."\r\n";
		// class_file::fAddLine($f_name,$f_content);
		$fp = fopen($f_name,"a+");
		flock($fp,LOCK_EX);
		fputs($fp,$f_content);
		fflush($fp);
		flock($fp,LOCK_UN);
		fclose($fp);
	}
	function sqlLog($f_cont = "",$f_type = "err"){
	
		$f_type = trim(strtolower($f_type));
		
		if($this->sqlArrSetting["Log"]['sqlLogOnOff'] == 1){
			if($f_type=="err" && $this->sqlArrSetting["Log"]['sqlLogMySQLErrorOnOff']==1){
				$this->sqlSaveLog($f_cont, $f_type);
			}
			elseif($f_type=="query" && $this->sqlArrSetting["Log"]['sqlLogMySQLWorkOnOffFull'] == 1){
				$this->sqlSaveLog($f_cont, $f_type);
			}
			else{
				$this->sqlSaveLog($f_cont, $f_type);
			}
		}
	}
	
	//-------------------------------------------------------------------------- 
	// Функция проверки существования и доступности другой функции PHP 
	//-------------------------------------------------------------------------- 
	// Параметры: $func - строка имени функции 
	// На выходе: true - функция есть и доступна для вызова, 
	//            false - функция недоступна по какой-либо причине 
	//-------------------------------------------------------------------------- 
	function sqlFuncEnabled($func) { 
		$func = strtolower(trim($func)); 
		if ($func == '') return false; 
		// Получить список функций, отключенных в php.ini 
		$disabled = explode(",",@ini_get("disable_functions")); 
		if (empty($disabled)) { 
			$disabled = array(); 
		} 
		else { 
			// Убрать пробелы и привести названия к нижнему регистру 
			$disabled = array_map('trim',array_map('strtolower',$disabled)); 
		} 
		// Проверить доступность функции разными способами 
		return (function_exists($func) && is_callable($func) && !in_array($func,$disabled)); 
	} 
	
	// создание БД
	function sqlCreateDB(){
	
		if (is_function_enabled('mysql_create_db')){
			if($link = mysql_connect($this->sqlArrSetting["MySQL"]['host'],$this->sqlArrSetting["MySQL"]['login'],$this->sqlArrSetting["MySQL"]['passwd'])){
				if (!mysql_create_db($this->sqlArrSetting["MySQL"]['database'])){
					$this->sql_err = mysql_error();
					$this->sqlLog(__METHOD__ ." - Ошибка создания Базы данных: ".mysql_error(),"err");
					return(false);
				}
				else{
					$this->sqlLog(__METHOD__ ." - База данных успешно создана db name: ".$this->sqlArrSetting["MySQL"]['database'],"query");
				}
			}
			else{
				$this->sql_err = mysql_error();
				$this->sqlLog(__METHOD__ ." - Не возможно установить соединение с сервером MySQL: ".mysql_error(),"err");
				return(false);
			}
			@mysql_close($link);
			return(true);
		}
		else{
			$this->sql_err = mysql_error();
			$this->sqlLog(__METHOD__ ." - Не возможно создать базу данных. mysql_create_db() не найдена.","err");
			return(false);
		}

		
	}
	
	// названия методов и свойств оставлены  без изменений иначе править очень моного нужно :)
/*	function sql_connect(){
		if($this->conn_id = @mysql_connect($this->sqlArrSetting["MySQL"]['host'],$this->sqlArrSetting["MySQL"]['login'],$this->sqlArrSetting["MySQL"]['passwd'])){
			if(!mysql_select_db($this->sqlArrSetting["MySQL"]['database'])){
				$this->sql_err = mysql_error();
				$this->sqlLog(__METHOD__ ." - Не возможно подключить базу данных: ".mysql_error(),"err");
				return(false);
			}
			if(!mysql_query("set names ".$this->sqlArrSetting["MySQL"]['codepage']) ||
				!mysql_query("set character_set_client='".$this->sqlArrSetting["MySQL"]['codepage']."'") ||
				!mysql_query("set character_set_results='".$this->sqlArrSetting["MySQL"]['codepage']."'") || 
				!mysql_query("set collation_connection='".$this->sqlArrSetting["MySQL"]['codepage']."_general_ci'")){
				$this->sql_err = mysql_error();
				$this->sqlLog(__METHOD__ ." - Не возможно установить кодовую страницу: ".mysql_error(),"err");
				return(false);
			}
		}
		else{
			$this->sql_err = mysql_error();
			$this->sqlLog(__METHOD__ ." - Не возможно установить соединение с базой данных: ".mysql_error(),"err");
			return(false);
		}
		return(true);
	}
*/
	//-------------------------------------------------------------------------- 
	// Функция делает соединение с DB 
	//-------------------------------------------------------------------------- 
	// Параметры: 
	// На выходе: возвращает идентификатор соединения 
	//-------------------------------------------------------------------------- 
	function sql_connect(){
		if($this->conn_id = @mysqli_connect($this->sqlArrSetting["MySQL"]['host'], $this->sqlArrSetting["MySQL"]['login'], $this->sqlArrSetting["MySQL"]['passwd'], $this->sqlArrSetting["MySQL"]['database'])){
			if(!mysqli_query($this->conn_id, "set names ".$this->sqlArrSetting["MySQL"]['codepage']) ||
				!mysqli_query($this->conn_id, "set character_set_client='".$this->sqlArrSetting["MySQL"]['codepage']."'") ||
				!mysqli_query($this->conn_id, "set character_set_results='".$this->sqlArrSetting["MySQL"]['codepage']."'") || 
				!mysqli_query($this->conn_id, "set collation_connection='".$this->sqlArrSetting["MySQL"]['codepage']."_general_ci'")){
				$this->sql_err = @mysqli_error($this->conn_id);
				$this->sqlLog(__METHOD__ ." - Не возможно установить кодовую страницу: ".$this->sql_err,"err");
				return(FALSE);
			}
		}
		else{
			$this->sql_err = @mysqli_error($this->conn_id);
			$this->sqlLog(__METHOD__ ." - Не возможно установить соединение с базой данных: ".$this->sql_err,"err");
			return(FALSE);
		}
		return(TRUE);
	}
	

/*	function sql_close(){
		unset($this->sql_QueryString);
		if($this->conn_id){
			if(!mysql_close($this->conn_id)){
				$this->sql_err = mysql_error();
				$this->sqlLog(__METHOD__ ." - Не возможно закрыть соединение с базой данных: ".mysql_error(),"err");
				return(false);
			}
		}
		return(true);
			
	}
*/
	//-------------------------------------------------------------------------- 
	// Функция закрывает соединение с DB 
	//-------------------------------------------------------------------------- 
	// Параметры: 
	// На выходе: 
	//-------------------------------------------------------------------------- 
	function sql_close(){
		unset($this->sql_QueryString);
		if($this->conn_id){
			if(!mysqli_close($this->conn_id)){
				$this->sql_err = @mysqli_error($this->conn_id);
				$this->sqlLog(__METHOD__ ." - Не возможно закрыть соединение с базой данных: ".$this->sql_err,"err");
				return(FALSE);
			}
		}
		return(TRUE);
	}
	
/*	function sql_query($query){

		//$_SERVER['REQUEST_URI']
		$this->sql_QueryString = $query;
		
		if(!$this->conn_id){
			$this->sql_err = mysql_error();
			$this->sqlLog(__METHOD__ ." - Не возможно выполнить запрос - QUERY: ".$this->sql_QueryString." - ".$this->sql_err,"err"); //
			return(false);
		}
		
		if($result = @mysql_query($query)){
			$this->sqlLog(__METHOD__ ." - ".$this->sql_QueryString,"query");
			return($result);
		}
		else{
			$this->sql_err = mysql_error();
			$this->sqlLog(__METHOD__ ." - Ошибка выполнения запроса - QUERY: ".$this->sql_QueryString." - ".$this->sql_err,"err");
			return(false);
		}
	}
*/
	//-------------------------------------------------------------------------- 
	// Функция выполняет зарос и возвращает результат выполнения
	//-------------------------------------------------------------------------- 
	// Параметры: query - строка запроса 
	// На выходе: result - результат выполения запроса
	//-------------------------------------------------------------------------- 
	function sql_query($query){
		$this->sql_QueryString = $query;
		
		if(!$this->conn_id){
			$this->sql_err = @mysqli_error($this->conn_id);
			$this->sqlLog(__METHOD__ ." - Не возможно выполнить запрос - QUERY: ".$this->sql_QueryString.". ERROR: ".$this->sql_err,"err"); //
			return(FALSE);
		}
		
		if($result = @mysqli_query($this->conn_id, $query)){
			$this->sqlLog(__METHOD__ ." - ".$this->sql_QueryString,"query");
			return($result);
		}
		else{
			if($this->sql_err = @mysqli_error($this->conn_id)){
				$this->sqlLog(__METHOD__ ." - Ошибка выполнения запроса - QUERY: ".$this->sql_QueryString.". ERROR: ".$this->sql_err,"err");	
			}
			return(FALSE);
		}
	}
	
/*	function sql_rows($query){
		if($result = @mysql_num_rows($query)){
			return($result);
		}
		else{
			$this->sql_err = mysql_error();
			$this->sqlLog(__METHOD__ ." - QUERY: ".$this->sql_QueryString." - ".$this->sql_err,"err");
			return(false);
		}
	}
*/
	//-------------------------------------------------------------------------- 
	// Функция возвращает количество строк 
	// из результата выполения функции sql_query()
	//-------------------------------------------------------------------------- 
	// Параметры: query - результат выполения функции sql_query()
	// На выходе: result - количество записей в результате запрса или false
	//-------------------------------------------------------------------------- 
	function sql_rows($query){
		if($result = @mysqli_num_rows($query)){
			return($result);
		}
		else{
			if($this->sql_err = @mysqli_error($this->conn_id)){
				$this->sqlLog(__METHOD__ ." - QUERY: ".$this->sql_QueryString." - ".$this->sql_err,"err");
			}
			return(false);
		}
	}
	
	//-------------------------------------------------------------------------- 
	// Возвращает ассоциативный массив с названиями индексов, соответсвующими названиям колонок или FALSE если рядов больше нет
	//-------------------------------------------------------------------------- 
	// Параметры: $query - ряд результата запроса 
	// На выходе: $result(array) - ассоциативный массив ряда результата запроса с названиями индексов
	//            false - если возникала ошибка при выполенеии или достигнут конец рядов результата запроса (если вызов в цыкле) 
	//-------------------------------------------------------------------------- 
	function sql_array($query){
		if($result = @mysqli_fetch_assoc($query)){
			return($result);
		}
		else{
			if($this->sql_err = @mysqli_error($this->conn_id)){
				$this->sqlLog(__METHOD__  ." - Не возможно обработать результат. QUERY: ".$this->sql_QueryString." ERROR:".$this->sql_err,"err");
			}
			return(FALSE);
		}
	}
	//-------------------------------------------------------------------------- 
	// Возвращает не ассоциативный массив с номерами индексов ($arr[0],$arr[1],$arr[...],...)
	//-------------------------------------------------------------------------- 
	// Параметры: $query - ряд результата запроса 
	// На выходе: $result(array) - не ассоциативный массив ряда результата запроса с номерами индексов
	//            false - если возникала ошибка при выполенеии или достигнут конец рядов результата запроса (если вызов в цыкле) 
	//-------------------------------------------------------------------------- 
	function sql_f_row($query){
		if($result = @mysqli_fetch_row($query)){
			return($result);
		}
		else{
			if($this->sql_err = @mysqli_error($this->conn_id)){
				$this->sqlLog(__METHOD__  ." - Не возможно обработать результат. QUERY: ".$this->sql_QueryString." ERROR:".$this->sql_err,"err");
			}
			return(FALSE);
		}
	}
	
	//function sql_count($field,$table,$conditions = ""){$cond =($conditions ? " WHERE ".$conditions : "");$result = @mysql_query("SELECT Count".$field." FROM ".$table.$cond);if(!$result){$this->sql_err=mysql_error();return(false);}else{$rows = mysql_result($result, 0);return($rows);}}
	
	//работа с запросами к таблицам
	//function sql_select(){}
	// $TableName - название таблицы без префикса
	// $ListField - список полей в которые будет производится вставка через ","
	// $ListValue - список значений для вставки в том же порядке что и поля('qwe1','qwe2','...')
	// возвращает true, если запрос выполне успешно и false если произошла ошибка. в переменную $sql_err будет занесено значение ошибки MySQL
	function sql_insert($TableName,$ListField,$ListValue){
	
		$this->sql_insertLastId = 0;
		$query = "insert into `".$this->prefix_db.$TableName."` (".$ListField.") values(".$ListValue.")";
		
		if($result = $this->sql_query($query)){
			$this->sql_insertLastId = mysqli_insert_id($this->conn_id);
			$this->sqlLog(__METHOD__ ." - QUERY:".$query." - ". $_SERVER['REQUEST_URI'],"insert");
			return(true);
		}
		else{
			$this->sql_err = mysqli_error($this->conn_id);
			//$this->sqlLog(__METHOD__ ." - sql_insert - ". $_SERVER['REQUEST_URI']." - QUERY: ".$query." - ".$this->sql_err,"err");
			return(false);
		}
	}
	
	// $TableName - название таблицы без префикса
	// $FieldAndValue - правильная строка определение параметров name='".$name."', templ='".$templ."', qwe='123', ...='...'
	// $WhereValue - правильное условие MySQL запроса qwe1='123', qwe2='456', ...='...' 
	// возвращает true, если запрос выполне успешно и false если произошла ошибка. в переменную $sql_err будет занесено значение ошибки MySQL
	function sql_update($TableName,$FieldAndValue,$WhereValue=''){
		
		// зваписываем в лог удаляемые записи таблицы
		if($this->sqlArrSetting["Log"]['sqlUpdate'] == 1){
			$this->sql_bakupdata($TableName,$WhereValue,"update");
		}
		
		$wv = "";
		if($WhereValue != ''){$wv = " where ".$WhereValue;}
		$query	 = "update `".$this->prefix_db.$TableName."` set ".$FieldAndValue.$wv;
		$result	 = $this->sql_query($query);
		if(!$result){
			$this->sql_err = mysqli_error($this->conn_id);
			//$this->sqlLog(__METHOD__ ." - ". $_SERVER['REQUEST_URI']." - QUERY: ".$query." - ".$this->sql_err,"err");
			return(false);
		}
		else{
			$this->sqlLog(__METHOD__ ." - ". $_SERVER['REQUEST_URI']." - QUERY: ".$query,"update");
			return(true);
		}
	}
	
	// $TableName - название таблицы без префикса
	// $WhereValue - правильное условие MySQL запроса qwe1='123' and qwe2='456' and ...='...' 
	// возвращает true, если запрос выполне успешно и false если произошла ошибка. в переменную $sql_err будет занесено значение ошибки MySQL
	function sql_delete($TableName,$WhereValue){

		// зваписываем в лог удаляемые записи таблицы
		if($this->sqlArrSetting["Log"]['sqlDelete'] == 1){
			$this->sql_bakupdata($TableName,$WhereValue,"delete");
		}
	
		$query = "delete from `".$this->prefix_db.$TableName."` where ".$WhereValue;
		$result = $this->sql_query($query);
		if(!$result){
			$this->sql_err = mysqli_error($this->conn_id);
			//$this->sqlLog(__METHOD__ ." - sql_update - ". $_SERVER['REQUEST_URI']." - QUERY: ".$query." - ".$this->sql_err,"err");
			return(false);
		}
		else{
			$this->sqlLog(__METHOD__ ." - ". $_SERVER['REQUEST_URI']." - ".$query,"delete");
			return(true);
		}
	}

	
	// сохранение данных перед изменением
	function sql_bakupdata($TableName,$WhereValue = "",$logName = ""){
		if($WhereValue != ''){$WhereValue = " WHERE ".$WhereValue;}

		$resultdlt = $this->sql_query("SELECT * FROM `".$this->prefix_db.$TableName."` ".$WhereValue."");
		if($this->sql_rows($resultdlt)){
			while($querydlt = $this->sql_array($resultdlt)){
				$str_dlt = "";
				foreach ($querydlt as $val){
					$str_dlt.= "'".$val."',";
				}
				unset($val);
				$str_dlt = substr($str_dlt,0,-1);
				
				$fld_dlt = "";
				if($fieldArr = $this->sql_GetFieldFromTable($this->prefix_db.$TableName)){
					foreach ($fieldArr as $val){
						$fld_dlt.= "`".$val."`,";
					}
					$fld_dlt = substr($fld_dlt,0,-1);
				}
				$this->sqlLog(__METHOD__ ." - INSERT INTO `".$this->prefix_db.$TableName."`(".$fld_dlt.") VALUES(".$str_dlt.")",$logName);
			}
		}

	}

	
	// проверяем существоание таблицы
	// с версии 2.5.9
	// $table - название проверяемой таблицы без префикса
	// возврат true если есть, false если нет
	function sql_table_exist($table){
		return(($this->sql_rows($this->sql_query("SHOW TABLES LIKE '".$this->prefix_db.trim($table)."'")))? TRUE : FALSE);
	}
	
	// получаем список таблиц БД
	function sql_ShowTableFromBD(){
		$result1 = $this->sql_query("SHOW TABLES FROM `".$this->sqlArrSetting["MySQL"]['database']."`");
		if($this->sql_err){
			$this->sqlLog(__METHOD__ ." - SHOW TABLES FROM `".$this->sqlArrSetting["MySQL"]['database']."` - ".$this->sql_err." - ". $_SERVER['REQUEST_URI']);
			return(FALSE);
		}
		else{
			$arrTable = array();
			while ($table = $this->sql_f_row($result1)){
				$arrTable[] = $table[0];
			}
			return($arrTable);
		}
	}
	
	// получаем список полей таблицы
	// имя таблицы указывать с префиксом ($table)
	function sql_GetFieldFromTable($table){
		$arrfld = array();
		$resulField = $this->sql_query("SHOW COLUMNS FROM `".$table."`");
		if($this->sql_err){
			$this->sqlLog(__METHOD__ ." - SHOW COLUMNS FROM `".$table."` - ".$this->sql_err." - ". $_SERVER['REQUEST_URI']);
			return(FALSE);
		}
		if($this->sql_rows($resulField)){
			while($qField = $this->sql_array($resulField)){
				$arrfld[] = $qField['Field'];
			}
		}
		return($arrfld);
	}
	
	// разбираем массив для записи в базу данных
	// возвращаем массив из двух строк для дальнейшего использования в методе sql_insert и sql_update
	function sql_ExpandArr($arr){
		if(is_array($arr)){
			$RetArr = array();
			$RetArr['ListField']	 = "";
			$RetArr['ListValue']	 = "";
			$RetArr['FieldAndValue'] = "";
			$ListField		 = "";
			$ListValue		 = "";
			$FieldAndValue	 = "";
			foreach($arr as $lf => $lv){
				$ListField.= ",`".$lf."`";
				$ListValue.= ",'".$lv."'";
				$FieldAndValue.= ",`".$lf."`='".$lv."'";
			}
			$RetArr['ListField']	 = substr($ListField,1);
			$RetArr['ListValue']	 = substr($ListValue,1);
			$RetArr['FieldAndValue'] = substr($FieldAndValue,1);
			return($RetArr);
		}else{return(false);}
	}
	
	// Получаем PRIMARY_KEY таблицы
	// вход $table - имя таблицы с префиксом
	// выход имя колонки PRIMARY_KEY
	// при ошибке false
	function sql_GetPrimaryKey($table){
		
		if($rPId = $this->sql_query("SHOW KEYS FROM `".$table."` WHERE `Key_name` = 'PRIMARY'")){
			$qPId		 = $this->sql_array($rPId);
			return($qPId['Column_name']);
		}
		else{
			$this->sql_err = @mysqli_error($this->conn_id);
			return(FALSE);
		}
	}


	//сохраняет или обновляет данные
	// $arrData = array("field name"=>"field value",)
	//$arrWhere = array(0=>array("field"=>"","value"=>"","action"=>"=","type"=>"and/or"))
	function sql_InsAndUpd(&$arrData, $table = "", $arrWhere = ""){
		if(!is_array($arrData)){return(false);}
		if($table == ""){return(false);}
		$table = trim($table);
		$ArrFV = $this->sql_ExpandArr($arrData);
		// добавляем новую запись
		if(!is_array($arrWhere)){
			if(!$this->sql_insert($table,$ArrFV['ListField'],$ArrFV['ListValue'])){return (false);}
			else{return (true);}
		}
		//сохраняем измененную запись
		else{
			$WhereVal = "";
			reset($arrWhere);
			while(list($key,$val) = each($arrWhere)){
				$WhereVal.= ((isset($val["type"]) && $val["type"] != "")?$val["type"]." ":"")."`".$val["field"]."`".$val["action"]."'".$val["value"]."'";
			}
			if(!$this->sql_update($table,$ArrFV['FieldAndValue'],$WhereVal)){return (false);}
			else{return (true);}
		}
		return(false);
	}
	
	// безопасный контент
    function sql_safesql($source = "") {
        if ( $this->conn_id ){
			return (mysql_real_escape_string( $source, $this->conn_id ));
		}
        else{
			return (mysql_escape_string( $source ));
		}
    }
	// получение безопасного контента
	function sql_get_safesql($source = ""){
		return(stripslashes($source));
	}
}//class_sql


	
?>
