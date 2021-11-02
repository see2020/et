<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// 2013
// файл: ut.php
// класс: class_ut
// автор: Иванцов Денис Владимирович 
// вспомогательный класс
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
//defined('_BLDEXEC') or die('Restricted access');
class class_ut{

	var $uArrSetting = array();

	function __construct(&$arrSetting){
		// $arrSetting['MySQL']['login']		 = "denis";
		// $arrSetting['MySQL']['passwd']		 = "denis";
		// $arrSetting['MySQL']['database']	 = "kd";
		// $arrSetting['MySQL']['host']		 = "localhost";
		// $arrSetting['MySQL']['prefix_db']	 = "kd_";
		// $arrSetting['MySQL']['codepage']	 = "cp1251";
		// $arrSetting['Path']['log']						 = $_SERVER['DOCUMENT_ROOT']."/temp/log";
		// $arrSetting['Path']['temp']						 = $_SERVER['DOCUMENT_ROOT']."/temp";
		// $arrSetting['DateTime']['DateTimeCorrection']	 = 0;
		// $arrSetting['DateTime']['DateTimeFormat']		 = "Y-m-d H:i:s";
		// $arrSetting["Log"]['sqlLogOnOff']				 = 1;
		// $arrSetting["Log"]['sqlLogMySQLErrorOnOff']		 = 1;
		// $arrSetting["Log"]['sqlLogMySQLWorkOnOffFull']	 = 1;
		$this->utArrSetting = $arrSetting;
	}
	//Получаем IP посетителя : адрес содержится в переменной окружения $REMOTE_ADDR.
	function utGetUserIP(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){$ip = $_SERVER['HTTP_CLIENT_IP'];}
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];}
		else{$ip = $_SERVER['REMOTE_ADDR'];}
		return $ip;
	}
	
	//для ведения логов
	function utLog($cont = "",$l_location = ""){
		$f_log		 = $this->utArrSetting['Path']['log']."/".$this->utGetDate("Y-m-d").".log";
		//$f_content	 = $this->utGetDate("Y.m.d H:i:s")." --- ".$this->utGetUserIP()." --- ".str_replace(array("\r", "\n", "\t"), " ", $cont)."\r\n";
		$f_content	 = $this->utGetDate("Y.m.d H:i:s").
			" -- ".
			$this->utGetUserIP().
			" -- ".
			str_replace(array("\r", "\n", "\t"), " ", $cont).
			" -- ".
			"REQUEST_URI: ".$_SERVER['REQUEST_URI'].
			"\r\n";
		if($l_location != ""){
			$f_content	 = $this->utGetDate("Y.m.d H:i:s").
			" -- ".
			$l_location.
			" -- ".
			$this->utGetUserIP().
			" -- ".
			str_replace(array("\r", "\n", "\t"), " ", $cont).
			" -- ".
			"REQUEST_URI: ".$_SERVER['REQUEST_URI'].
			"\r\n";
		}
		
		
		$fp = fopen($f_log,"a+");
		flock($fp,LOCK_EX);
		fputs($fp,$f_content);
		fflush($fp);
		flock($fp,LOCK_UN);
		fclose($fp);
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ДАТА И ВРЕМЯ 
	// функция возвращает дату и время в заданом формате 
	// примерно тоже самое, что и функция php date()

	// Поправка даты и времени в секунах +/-
	// время в секундах с учетом поправки
	function utGetTime(){
		$DateTimeCorrection = 0;
		if(isset($this->utArrSetting["DateTime"]['DateTimeCorrection']) && $this->utArrSetting["DateTime"]['DateTimeCorrection'] != ""){
			$DateTimeCorrection = $this->utArrSetting["DateTime"]['DateTimeCorrection'];
		}
		return(time() + $DateTimeCorrection);
	}
	//дата с учетом поправки
	function utGetDate($str_dt = "Y-m-d H:i:s",$str_tm = ""){
		if($str_tm == ""){$str_tm = $this->utGetTime();}
		return(@date($str_dt,$str_tm));
	}
		
// КОНЕЦ ДАТА И ВРЕМЯ 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//страничка с кнопками да/нет(buttonYes / buttonNo)
	function utWindowYesNo($text = "",$ActionString = ""){
		$ShowForm = "";
		$ShowForm.= "<form id='FormYesNo' name='FormYesNo' method='post' action='{%WindowYesNoActionString%}'>";
		$ShowForm.= "<center>";
		$ShowForm.= "<p>{%WindowYesNoText%}</p>";
		$ShowForm.= "<input type='submit' name='buttonYes' id='buttonYes' value='Yes' style='width:40pt;'>";
		$ShowForm.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$ShowForm.= "<input type='submit' name='buttonNo' id='buttonNo' value='No' style='width:40pt;'>";
		$ShowForm.= "</center>";
		$ShowForm.= "</form>";
		//return(str_replace(array("{%WindowYesNoActionString%}","{%WindowYesNoText%}"), array($ActionString,$text), $this->kGetTpl("windowyesno.tpl","windowyesno",$ShowForm)));
		return(str_replace(array("{%WindowYesNoActionString%}","{%WindowYesNoText%}"), array($ActionString,$text), $ShowForm));
	}

	
	

	
} //class_ut

?>