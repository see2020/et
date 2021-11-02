<?php
	header('Content-Type: text/html; charset=windows-1251');
	//session_name();
	//session_start();

	$ver = "1.16";
	
	if(!isset($cmsPathRelative)){echo "Relative path error!"; exit;}
	if($cmsPathRelative == ""){echo "Relative path is null!"; exit;}
	
	$PathClass	 = $cmsPathRelative."/components/class";
	$PathData	 = $cmsPathRelative."/_data_files";

	include($PathData."/config.php");
	$arrSetting = $arrConfig;
	
	include($PathData."/lang.php");
	$arrLang = $arrConfig;
	
	$arrSetting["Version"]["ver"]	 = $ver;
	foreach($arrSetting['Path'] as $key => $val){$arrSetting['Path'][$key] = $cmsPathRelative.$val;}
	$arrSetting["Path"]["class"]	 = $PathClass;

	if(!is_dir($arrSetting['Path']['log'])){if(!mkdir($arrSetting['Path']['log'], 0777)){echo "<p>Ошибка создания папки ".$arrSetting['Path']['log']."</p>";exit;}}
	
	// создаем дополнительные папки
	foreach($arrSetting['Path'] as $key => $val){if(!is_dir($val)){if(!mkdir($val, 0777)){echo "<p>Ошибка создания папки ".$val."</p>";exit;}}}

	$arrClassInclude = array("sql.php","ut.php","url.php","text.php","mail.php","sqlparser.php","func.php","func_spr.php","func_user.php","config.class.php","file.php");

	foreach($arrClassInclude as $FNameClass) {
		if(file_exists($PathClass."/".$FNameClass)){
			include_once($PathClass."/".$FNameClass);
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
	
	if(file_exists($arrSetting['Path']['tpl']."/".$arrSetting['Table']['DefaultTpl']."/func.php")){
		include_once($arrSetting['Path']['tpl']."/".$arrSetting['Table']['DefaultTpl']."/func.php");
	}else{
		echo "Includes error: ".$arrSetting['Table']['DefaultTpl']."/func.php";
		exit;
	}	

	define("D_NAME",$arrSetting['MySQL']['database'],true);

?>
