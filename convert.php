<?php
	header('Content-Type: text/html; charset=windows-1251');
	$pRel = ".";
	$PathClass = "/components/class";
	
	include($pRel.$PathClass.'/config.class.php');
	include($pRel.$PathClass.'/file.php');
	include($pRel.$PathClass.'/sql.php');
	//$cfg = new class_ini();
	/***************************************************************************************************/
	// конвертируем настройки
	
	$FileCfgIni	 = $pRel."/_data_files/config.ini";
	$FileCfgArr	 = $pRel."/_data_files/config.php";
	if(!file_exists($FileCfgArr)){
		$tmp_ini = new class_ini();
		$tmp_ini->fINIFileName = $FileCfgIni;
		$tmp_ini->fINIInitArray();
		$arrSetting_tmp = $tmp_ini->fINIArray;
		$config = new config($FileCfgArr);
		$config->init();	
		foreach ($arrSetting_tmp as $key => $val){
			$config->setSection($key,$val);
		}
		$config->upd();
		include($FileCfgArr);
		$arrSetting = $arrConfig;
		unset($arrSetting_tmp, $tmp_ini,$config,$arrConfig);
		
		echo "<p>".$FileCfgIni." >> ".$FileCfgArr."</p>";
	}
	else{
		include($FileCfgArr);
		$arrSetting = $arrConfig;
		unset($arrConfig);
		echo "<p>".$FileCfgArr." -  FILE IS EXISTS!</p>";
	}
	$arrSetting['Path']['log'] = $pRel.$arrSetting['Path']['log'];
	
	$FileCfgIni	 = $pRel."/_data_files/lang.ini";
	$FileCfgArr	 = $pRel."/_data_files/lang.php";
	if(!file_exists($FileCfgArr)){
		$tmp_ini = new class_ini();
		$tmp_ini->fINIFileName = $FileCfgIni;
		$tmp_ini->fINIInitArray();
		$arrSetting_tmp = $tmp_ini->fINIArray;
		$config = new config($FileCfgArr);
		$config->init();	
		foreach ($arrSetting_tmp as $key => $val){
			$config->setSection($key,$val);
		}
		$config->upd();
		include($FileCfgArr);
		unset($arrSetting_tmp, $tmp_ini,$config);
		echo "<p>".$FileCfgIni." >> ".$FileCfgArr."</p>";
	}
	else{
		echo "<p>".$FileCfgArr." -  FILE IS EXISTS!</p>";
	}
	
	$FileCfgIni	 = $pRel.$arrSetting['Path']['tbldata']."/tList.ini";
	$FileCfgArr	 = $pRel.$arrSetting['Path']['tbldata']."/tList.php";
	if(!file_exists($FileCfgArr)){
		$tmp_ini = new class_ini();
		$tmp_ini->fINIFileName = $FileCfgIni;
		$tmp_ini->fINIInitArray();
		$arrSetting_tmp = $tmp_ini->fINIArray;
		$config = new config($FileCfgArr);
		$config->init();	
		foreach ($arrSetting_tmp as $key => $val){
			$config->setSection($key,$val);
		}
		$config->upd();
		unset($arrSetting_tmp, $tmp_ini,$config);
		echo "<p>".$FileCfgIni." >> ".$FileCfgArr."</p>";
	}
	else{
		echo "<p>".$FileCfgArr." -  FILE IS EXISTS!</p>";
	}
	
	
	$sql = new class_sql($arrSetting);
	$sql->sql_connect();
	if($result1 = $sql->sql_ShowTableFromBD()){
		
		foreach($result1 as $key => $t_name){
			$tName = str_replace($sql->prefix_db, "", $t_name);
			
			$FileCfgIni	 = $pRel.$arrSetting['Path']['tbldata']."/".$tName."/".$tName.".ini";
			$FileCfgArr	 = $pRel.$arrSetting['Path']['tbldata']."/".$tName."/".$tName.".php";
			if(file_exists($FileCfgIni)){
				if(!file_exists($FileCfgArr)){
					$tmp_ini = new class_ini();
					$tmp_ini->fINIFileName = $FileCfgIni;
					$tmp_ini->fINIInitArray();
					$arrSetting_tmp = $tmp_ini->fINIArray;
					$config = new config($FileCfgArr);
					$config->init();	
					foreach ($arrSetting_tmp as $key => $val){
						$config->setSection($key,$val);
					}
					$config->upd();
					unset($arrSetting_tmp, $tmp_ini,$config);
					echo "<p>".$FileCfgIni." >> ".$FileCfgArr."</p>";
				}
				else{
					echo "<p>".$FileCfgArr." -  FILE IS EXISTS!</p>";
				}
			}
			else{
				echo "<p>".$FileCfgIni." -  <b>FILE IS NOT EXISTS!</b></p>";
			}
		}		
	}
	$sql->sql_close();
	
	
	/***************************************************************************************************/
	
	
?>
