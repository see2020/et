<?php
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

	if(!is_dir($arrSetting['Path']['log'])){if(!mkdir($arrSetting['Path']['log'], 0777)){echo "<p>Ошибка создания папки ".$arrSetting['Path']['log']."</p>";exit;}}
	
	// создаем дополнительные папки
	foreach($arrSetting['Path'] as $key => $val){if(!is_dir($val)){if(!mkdir($val, 0777)){echo "<p>Ошибка создания папки ".$val."</p>";exit;}}}

	$arrClassInclude = array("sql.php","ut.php","url.php","text.php","mail.php","sqlparser.php","func.php","func_spr.php","func_user.php","config.class.php","file.php");

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
	$url = new class_url();
	$txt = new class_txt();
	$eml = new class_mail($arrSetting);
	$flc = new class_file();
	$sql_parser = new SQLParser;
	
	$arrSetting["Other"]["tablesysmenu"] = "tblmenu";
	
	if(file_exists($arrSetting['Path']['tpl'] . DS . $arrSetting['Table']['DefaultTpl'] . DS . "func.php")){
		include_once($arrSetting['Path']['tpl'] . DS . $arrSetting['Table']['DefaultTpl'] . DS . "func.php");
	}else{
		echo "Includes error: ".$arrSetting['Table']['DefaultTpl'] . DS . "func.php";
		exit;
	}	

	define("D_NAME",$arrSetting['MySQL']['database'],true);

?>
