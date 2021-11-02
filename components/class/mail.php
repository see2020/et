<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// CMS: BLD - 2014
// файл: mail.php
// класс: class_mail
// класс для работы с отправкой почты
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
//defined('_BLDEXEC') or die('Restricted access');
class class_mail{
	
	var $emlArrSetting	 = array(); // массив с внещшними настройками
	var $emlHeader		 = ""; // заголовок письма
	var $emlBoundary	 = ""; // разделитель
	var $emlBody		 = ""; // текст сообщения
	var $emlMailAttach	 = ""; // ссылка на файл на сервере который надо прикрепить к сообщению
	var $emlPriority	 = "3";
	function __construct(&$arrSetting){
		/* отписание массива с настройками (ini)
		[Mail]
		email_admin="admin@local.loc";
		EmailFromSend="robot@local";
		EmlMessageBodyType="0";  1/0 // text/plain или text/html(по умолчанию)
		ShowEmailFrom="0";  // показывать с кого адреса пришло письмо в теле сообщения
		smtp_on=0;
		smtp_port=25;
		smtp_server="";
		smtp_ehlo="";
		smtp_email="";
		smtp_login="";
		smtp_password="";
		smtp_secure="";

		[DateTime]
		DateTimeCorrection="+10800"
		DateTimeFormat="Y-m-d H:i:s"

		[Log]
		emlLogOnOff="1"

		[Path]
		log="/_data_files/log"
		*/
		
		$this->emlArrSetting = $arrSetting;
		$this->emlArrSetting['Mail']['smtp_secure']				 = '';
		
		
		if(!isset($this->emlArrSetting['Mail']['charset'])){
			$this->emlArrSetting['Mail']['charset']				 = 'windows-1251';	
		}
		$this->emlArrSetting['Mail']['mailer']					 = 'The Bat!(v3.99.3) Professional';
		$this->emlArrSetting['Mail']['ContentTransferEncoding']	 = '8bit';
		
	}
	
	// ведем логи рассылки
	function emlSaveLog($f_cont = "", $f_type = ""){
		$f_name = $this->emlArrSetting["Path"]['log']."/mail_".$f_type."_".date("Y-m-d",(time()+$this->emlArrSetting["DateTime"]['DateTimeCorrection'])).".log";
		$f_content = date("Y.m.d H:i:s",(time()+$this->emlArrSetting["DateTime"]['DateTimeCorrection']))." --- ".str_replace(array("\r", "\n", "\t"), " ", $f_cont)."\r\n";
		// добавляем запись в файл лога
		$fp = fopen($f_name,"a+");
		flock($fp,LOCK_EX);
		fputs($fp,$f_content);
		fflush($fp);
		flock($fp,LOCK_UN);
		fclose($fp);
	}
	
	function emlLog($f_cont = "",$f_type = "err"){
		$f_type = trim(strtolower($f_type));
		if($this->emlArrSetting["Log"]['emlLogOnOff'] == 1){
			$this->emlSaveLog($f_cont, $f_type);
		}
	}
	
	// проверяем на корректность почтовый ящик
	// $eml - почтовый ящик
	// на выходе true - правильный, false - не правильный
	function emlMailCheck($eml){
		if(!preg_match('/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i', $eml))
		{return(false);}
		return(true);
	}	

	// готовим заголовок
	function emlSetHeader($from, $to, $subj,$name_from='',$name_to=''){
		$subj = strip_tags($subj);
		
		if($this->emlArrSetting["Mail"]['smtp_on'] == 1){
			//$subj.= " from ".$from."";
			$from = $this->emlArrSetting["Mail"]['smtp_email'];
		}
		
		//$priority		 = '3(Normal)';
		$mid			 = $this->emlArrSetting["Mail"]['EmailFromSend'];
		$mailer			 = $this->emlArrSetting["Mail"]['mailer'];
	
		if($name_from == ''){$name_from = $this->emlArrSetting['Mail']['NameFromText'].' '.strtr($this->emlArrSetting["Path"]['http'],array("http://"=>""));}
		if($name_to == ''){$name_to = str_replace("@", "_", $to);}
	
		// готовим header сообщения
		$this->emlBoundary = "--".md5(uniqid((time()+$this->emlArrSetting["DateTime"]['DateTimeCorrection'])));  // любая строка, которой не будет ниже в потоке данных.  
		$header="Date: ".date("D, j M Y G:i:s",(time()+$this->emlArrSetting["DateTime"]['DateTimeCorrection']))." +0700\r\n";
		$header.="Content-Type: multipart/mixed; boundary=\"".$this->emlBoundary."\"\r\n"; 
		$header.="From: ".$this->emlSetCharset($name_from)." <".$from.">\r\n";
		$header.="X-Mailer: ".$mailer."\r\n";
		$header.="Reply-To: ".$this->emlSetCharset($name_from)." <".$from.">\r\n";
		$header.="X-Priority: ".$this->emlPriority."\r\n";
		$header.="Message-ID: <172562218.".date("YmjHis",(time()+$this->emlArrSetting["DateTime"]['DateTimeCorrection']))."@".str_replace("@","_",$mid).">\r\n";
		$header.="To: ".$this->emlSetCharset($name_to)." <".$to.">\r\n";
		if($this->emlArrSetting["Mail"]['smtp_on'] == 1){$header.="Subject: ".$subj."\r\n";}
		$header.="MIME-Version: 1.0\r\n";
		$this->emlHeader = $header;
	}
	
	// готовим тело письма
	function emlSetBody($from, $to, $body){
			
		// if($this->emlArrSetting["Mail"]['ShowEmailFrom'] == 1){
			// $body.= "<br><br>from ".$from."";
		// }
			
		$content_type	 = 'text/html';
		 // если включено делать отправку в сообщений в текстовом формате
		if(isset($this->emlArrSetting["Mail"]['EmlMessageBodyType']) && $this->emlArrSetting["Mail"]['EmlMessageBodyType'] == 1){
			$content_type='text/plain';
			$body = strip_tags(str_replace(array("<br>", "</p>", "</tr>", "</div>"), "\r\n", $body));
		}else{
			//$body = strip_tags(str_replace(array("\r", "\n", "\t"), "<br>", $body),"<br>");
			$body = str_replace(array("\r", "\n", "\t"), "<br>", $body);
		}
		
		$charset				 = $this->emlArrSetting["Mail"]['charset'];
		$ContentTransferEncoding = $this->emlArrSetting['Mail']['ContentTransferEncoding'];
		
		$multipart= "\r\n--".$this->emlBoundary."\r\n"; 
		$multipart.="Content-Type: ".$content_type."; charset=".$charset."\r\n";
		$multipart.="Content-Transfer-Encoding: ".$ContentTransferEncoding."\r\n";
		$multipart.="\r\n";// раздел между заголовками и телом html-части 
		$multipart.=$body;
		
		//если есть файлик для прикрепления
		if($this->emlMailAttach != ''){
			$arrNameFile = explode("/", $this->emlMailAttach);
			reset($arrNameFile);
			$ikey		 = count($arrNameFile)-1;

			$fileContent = "";
			if(file_exists($this->emlMailAttach)){
				if($fd = fopen ($this->emlMailAttach, "r")){
					while (!feof ($fd)){
						$fileContent.= fgets($fd, 4096);
					}
					fclose ($fd);
				}else{
					$this->emlLog(__METHOD__." - Error open file: ".$this->emlMailAttach,"err");
				}
			}
	
			$multipart.= "\r\n--".$this->emlBoundary."\r\n"; 
			$multipart.="Content-Type: application/octet-stream; name=\"".$arrNameFile[$ikey]."\"\r\n"; 
			$multipart.= "Content-Transfer-Encoding: base64\r\n";   
			$multipart.= "Content-Disposition: attachment; filename=\"".$arrNameFile[$ikey]."\"\r\n";   
			$multipart.="\r\n"; // раздел между заголовками и телом прикрепленного файла 
			$multipart.= chunk_split(base64_encode($fileContent)); 
		}			
		
		$this->emlBody = $multipart;
	}
	
	
	function emlSetCharset($val){return("=?".$this->emlArrSetting["Mail"]['charset']."?Q?".str_replace("+","_",str_replace("%","=",urlencode($val)))."?=");}
	
	//занимается отправкой почты как таковой,
	// $from			 - от какого ящика отправлять
	// $to				 - куда отправлять
	// $subj			 - тема сообщения
	// $body			 - содержимое письма
	// $name_from = ''	 - текстовая строка от имени кого отправляется, если пусто, то подставляется ящик $from
	// $name_to = ''	 - текстовая строка кому отправляется, если пусто, то подставляется ящик $to
	// на выходе true - успешно отправлено, false - при ошибке отправки
	function emlMailSend($from, $to, $subj, $body,$name_from='',$name_to=''){

		// if($this->emlArrSetting['Mail']['charset'] == "utf-8"){
			// $from		 = mb_convert_encoding($from, "utf-8", "windows-1251");	
			// $to			 = mb_convert_encoding($to, "utf-8", "windows-1251");	
			// $subj		 = mb_convert_encoding($subj, "utf-8", "windows-1251");	
			// $body		 = mb_convert_encoding($body, "utf-8", "windows-1251");	
			// $name_from	 = mb_convert_encoding($name_from, "utf-8", "windows-1251");	
			// $name_to	 = mb_convert_encoding($name_to, "utf-8", "windows-1251");	
		// }
	
		$subj = $this->emlSetCharset($subj);

	
		// если адрес отправления не прошел проверку
		if(!$this->emlMailCheck($from)){
			$this->emlLog(__METHOD__ ." E-mail error. <from>: ".$from."","err");
			return(false);
		}
		// если адрес получения не прошел проверку
		if(!$this->emlMailCheck($to)){
			$this->emlLog(__METHOD__ ." E-mail error. <to>: ".$to."","err");
			return(false);
		}
	
		$this->emlSetHeader($from, $to, $subj, $name_from, $name_to);
		$this->emlSetBody($from, $to,$body);
		
		if($this->emlArrSetting["Mail"]['smtp_on'] == 1){
			if(!$this->emlSMTPSend($from,$to)){
				$this->emlLog(__METHOD__ ." Send SMTP error. From: ".$from.". To: ".$to,"smtp_err");
				return(false);
			}else{
				$this->emlLog(__METHOD__ ." <SMTP> From: ".$from.". To: ".$to.". Subject: ".strip_tags($subj).". Мessage: ".strip_tags($body),"smtp_send");
			}
		}
		else{
			if(!@mail($to,$subj,$this->emlBody,$this->emlHeader."\r\n")){
				$this->emlLog(__METHOD__ ." Send mail error. From: ".$from.". To: ".$to." emlBody: ".$this->emlBody." emlHeader: ".$this->emlHeader,"err");
				return(false);
			}else{
				$this->emlLog(__METHOD__ ." From: ".$from.". To: ".$to.". Subject: ".strip_tags($subj).". Мessage: ".strip_tags($body),"send");
			}
		}
		return(true);
	}
	
	//отправляем почту через smtp (без всякийх ssl, tls и прочей безопасности)
	function emlGetSMTPData($smtp_conn){
		$data = "";
		while($str = fgets($smtp_conn,515)){
			$this->emlLog(__METHOD__." --- SMTP: ".$str."","smtp_send");
			$data .= $str;
			if(substr($str,3,1) == " ") { break; }
		}
		return($data);
	}
	function emlSMTPSend($from,$to){
		
		$MessSendBody = $this->emlHeader."\r\n".$this->emlBody."\r\n.\r\n";
			
		$smtp_conn = fsockopen(trim($this->emlArrSetting["Mail"]['smtp_server']), trim($this->emlArrSetting["Mail"]['smtp_port']),$errno, $errstr, 10);
		if(!$smtp_conn) {$this->emlLog(__METHOD__." --- SMTP conn: ".$smtp_conn,"smtp_err");fclose($smtp_conn);return(false);}
		$data = $this->emlGetSMTPData($smtp_conn);

		fwrite($smtp_conn,"EHLO ".trim($this->emlArrSetting["Mail"]['smtp_ehlo'])."\r\n");
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code!==250) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,"AUTH LOGIN\r\n");
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code!==334) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,base64_encode(trim($this->emlArrSetting["Mail"]['smtp_login']))."\r\n");
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code!==334) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,base64_encode(trim($this->emlArrSetting["Mail"]['smtp_password']))."\r\n");
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code!==235) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,"MAIL FROM:".trim($from)."\r\n");
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code!==250) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,"RCPT TO:".trim($to)."\r\n");
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code!==250 AND(int)$code!== 251) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,"DATA\r\n");
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code!==354) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,$MessSendBody);
		$code = substr($this->emlGetSMTPData($smtp_conn),0,3);
		if((int)$code !== 250) {$this->emlLog(__METHOD__." --- SMTP: ".$code,"smtp_err"); fclose($smtp_conn);return(false);}

		fwrite($smtp_conn,"QUIT\r\n");
		fclose($smtp_conn);
		return(true);
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
/* настройка формы сообщения */

	// настройки по умолчанию
	function emlFormConfigGetDefault($type = ""){
		if($type == ""){return("");}
		
		reset($this->emlFieldForm);
		if($type == "field_show"){
			while(list($key,$val) = each($this->emlFieldForm)){
				$arrFieldShow[]		 = $val;
			}
			return(ArrToStr($arrFieldShow, ";"));
		}
		if($type == "field_require"){
			while(list($key,$val) = each($this->emlFieldForm)){
				$arrFieldRequire[]	 = $val;
			}
			return(ArrToStr($arrFieldRequire, ";"));
		}
		if($type == "field_sort"){
			$num = 10;
			while(list($key,$val) = each($this->emlFieldForm)){
				$arrFieldSort_forsave[]	 = $val.":".$num;
				$num = $num + 10;
			}
			return(ArrToStr($arrFieldSort_forsave, ";"));
		}
		if($type == "field_name"){
			while(list($key,$val) = each($this->emlFieldForm)){
				$arrFieldName_forsave[]	 = $val.":".$this->emlFieldName[$val];
			}
			return(ArrToStr($arrFieldName_forsave, ";"));
		}
	}
	
	// получаем данные для вывода ормы посетителю
	function emlFormData(&$my, $to_page = ""){
		$qMailForm["alias"]			 = "";
		$qMailForm["fio"]			 = "Отправка сообщения";
		$qMailForm["eml"]			 = "";
		$qMailForm["addfile"]		 = $my->kSettingGet('mailAddFile'); // прикреплять файл ввиде ссылки
		$qMailForm["addattach"]		 = $my->kSettingGet('mailAddAttach'); // + прикреппить файл к телу письма
		$qMailForm["tpl"]			 = 0;
		$qMailForm["message"]		 = "";
		$qMailForm["field_show"]	 = $this->emlFormConfigGetDefault("field_show");
		$qMailForm["field_require"]	 = $this->emlFormConfigGetDefault("field_require");
		$qMailForm["field_sort"]	 = $this->emlFormConfigGetDefault("field_sort");
		$qMailForm["field_name"]	 = $this->emlFormConfigGetDefault("field_name");
		
		$rMailForm = $my->sql->sql_query("select * from ".$my->sql->prefix_db."mail where alias='".$to_page."'");
		if($my->sql->sql_err){
			$this->emlLog("Ошибка получаения формы: ".$to_page,"err");
		}
		if($my->sql->sql_rows($rMailForm)){
			$qMailForm = $my->sql->sql_array($rMailForm);
			if($qMailForm["tpl"] == ""){$qMailForm["tpl"] = 0;}
		}
		return($qMailForm);
	}
	function emlFormDataById(&$my, $id_form = ""){
		$qMailForm["alias"]			 = "";
		$qMailForm["fio"]			 = "Отправка сообщения";
		$qMailForm["eml"]			 = "";
		$qMailForm["addfile"]		 = $my->kSettingGet('mailAddFile'); // прикреплять файл ввиде ссылки
		$qMailForm["addattach"]		 = $my->kSettingGet('mailAddAttach'); // + прикреппить файл к телу письма
		$qMailForm["tpl"]			 = 0;
		$qMailForm["message"]		 = "";
		$qMailForm["field_show"]	 = $this->emlFormConfigGetDefault("field_show");
		$qMailForm["field_require"]	 = $this->emlFormConfigGetDefault("field_require");
		$qMailForm["field_sort"]	 = $this->emlFormConfigGetDefault("field_sort");
		$qMailForm["field_name"]	 = $this->emlFormConfigGetDefault("field_name");
		
		$rMailForm = $my->sql->sql_query("select * from ".$my->sql->prefix_db."mail where id='".$id_form."'");
		if($my->sql->sql_err){
			$this->emlLog("Ошибка получаения формы: ".$to_page,"err");
		}
		if($my->sql->sql_rows($rMailForm)){
			$qMailForm = $my->sql->sql_array($rMailForm);
			if($qMailForm["tpl"] == ""){$qMailForm["tpl"] = 0;}
		}
		return($qMailForm);
	}
	
	// получаем настройки полей для формы
	function emlFormGetConfig($qMailForm = array()){
		$tmp_arr = array();
		
		$tmp = $qMailForm["field_show"];
		$arrMailShow	 = explode(";", $tmp);
		
		$tmp = $qMailForm["field_require"];
		$arrMailRequire	 = explode(";", $tmp);
		
		$tmp = $qMailForm["field_sort"];
		$tmp1	 = explode(";", $tmp);
		reset($tmp1);
		while(list($key,$val) = each($tmp1)){
			$tmp2 = explode(":", $val);
			
			$arrMailSort_cl[$tmp2[1]] = $tmp2[0];
			
			$arrMailSort_ad[$tmp2[0]] = $tmp2[1];
		}
		
		
		
		$tmp = $qMailForm["field_name"];
		$tmp1	 = explode(";", $tmp);
		reset($tmp1);
		while(list($key,$val) = each($tmp1)){
			$tmp2 = explode(":", $val);
			$arrMailName[$tmp2[0]] = $tmp2[1];
		}
		unset($tmp,$tmp1,$tmp2);
		
		$tmp_arr["show"]		 = $arrMailShow;
		$tmp_arr["require"]		 = $arrMailRequire;
		$tmp_arr["sort_client"]	 = $arrMailSort_cl;
		$tmp_arr["sort_admin"]	 = $arrMailSort_ad;
		$tmp_arr["name"]		 = $arrMailName;
		
		return($tmp_arr);
	}

}//class_mail
?>
