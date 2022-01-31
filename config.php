<?php
/**
 * config.php - все настройки начинаются здесь
 */
	header('Content-Type: text/html; charset=windows-1251');
	//session_name();
	//session_start();

	$ver = "1.17";

	if(!defined('ET_PATH_RELATIVE')){
		echo "Relative path error!";
		exit;
	}

	$PathClass	 = ET_PATH_RELATIVE . DS ."components" . DS . "class";
	$PathData	 = ET_PATH_RELATIVE . DS ."_data_files";

	$arrConfig = [];

	function newDir($dirName = null){
		if(empty($dirName)){
			return false;
		}
		if(!is_dir($dirName)){
			if(!mkdir($dirName, 0777)){
				return false;
			}
		}
		return true;
	}

	include($PathData . DS . "config.php");
	$arrSetting = $arrConfig;
	
	include($PathData . DS . "lang.php");
	$arrLang = $arrConfig;
	
	$arrSetting["Version"]["ver"]	 = $ver;
	foreach($arrSetting['Path'] as $key => $val){
//		$arrSetting['Path'][$key] = ET_PATH_RELATIVE . $val;
		$arrSetting['Path'][$key] = ET_PATH_RELATIVE . str_replace('/', DS, $val);
	}
	$arrSetting["Path"]["class"]	 = $PathClass;

	if(!newDir($arrSetting['Path']['log'])){
		echo "<p>Ошибка создания папки ".$arrSetting['Path']['log']."</p>";
	}

	// создаем дополнительные папки
	foreach($arrSetting['Path'] as $key => $val){
		if(!newDir($val)){
			echo "<p>Ошибка создания папки ".$val."</p>";
		}
	}

	$arrClassInclude = array("sql.php","ut.php","url.php","text.php"
//	,"sqlparser.php"
	,"func.php","func_spr.php","func_user.php","config.class.php","file.php");

	foreach($arrClassInclude as $FNameClass) {
		if(file_exists($PathClass . DS .$FNameClass)){
			include_once($PathClass . DS .$FNameClass);
		}
		else{
			echo "Includes error: ".$FNameClass;
			exit;
		}		
	}
	$sql = new class_sql($arrSetting);
	$ut	 = new class_ut($arrSetting);
//	$url = new class_url();
//	$txt = new class_txt();
	$flc = new class_file();
//	$sql_parser = new SQLParser;
	
	$arrSetting["Other"]["tablesysmenu"] = "tblmenu";
	
	if(file_exists($arrSetting['Path']['tpl'] . DS . $arrSetting['Table']['DefaultTpl'] . DS . "func.php")){
		include_once($arrSetting['Path']['tpl'] . DS . $arrSetting['Table']['DefaultTpl'] . DS . "func.php");
	}else{
		echo "Includes error: ".$arrSetting['Table']['DefaultTpl'] . DS . "func.php";
		exit;
	}	

	define("D_NAME",$arrSetting['MySQL']['database']);

?>
