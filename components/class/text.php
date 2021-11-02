<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// файл: text.php 
// класс: class_txt
// обработка текста
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
class class_txt{
	

	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// СТРОКОВЫЕ ОПЕРАЦИИ
	// преобразуем все буквы руского алфавита в трнаслит на латиницу
	// $ToLower - переводить или нет всю строку в нижний регистр
	function txtTranslit($st,$ToLower = true){
		$st = trim($st);

		// Сначала заменяем "односимвольные" фонемы.
	    $st = strtr($st,"абвгдеёзийклмнопрстуфхыэ","abvgdeeziyklmnoprstufhiei");
		$st = strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ","ABVGDEEZIYKLMNOPRSTUFHIEI");
		
		//преобразуем латинские буквы
	    $st = strtr($st,"qwertyuiopasdfghjklzxcvbnm","qwertyuiopasdfghjklzxcvbnm");
	    $st = strtr($st,"QWERTYUIOPASDFGHJKLZXCVBNM","QWERTYUIOPASDFGHJKLZXCVBNM");
	  
		// Затем - "многосимвольные".
		$st = strtr($st,array(
						"ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh",
						"щ"=>"shch","ь"=>"","ъ"=>"", "ю"=>"yu", "я"=>"ya",
						"Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"sh",
						"Щ"=>"SHCH","Ь"=>"","Ъ"=>"", "Ю"=>"YU", "Я"=>"YA",
						"ї"=>"i", "Ї"=>"I", "є"=>"ie", "Є"=>"IE"
						)
				);	
				
		if($ToLower){
			$st = strtolower($st);
		}
	    // Возвращаем результат.
	    return($st);
	}
	
	// оставляем только слова
	function txtClearStr($st){
		$st = strtr($st,array(
						"«"=>"","»"=>"","&laquo;"=>"","&raquo;"=>"","&#171;"=>"","&#187;"=>"",
						"<"=>"",">"=>"","&lt;"=>"","&gt;"=>"","&#60;"=>"","&#62;"=>"",
						"!"=>"","'"=>"","\""=>"","/"=>"",":"=>"","#"=>"",
						"+"=>"","$"=>"","%"=>"","&"=>"","("=>"",")"=>"","№"=>"",
						";"=>"",","=>"","\\"=>"","="=>"","¦"=>"","?"=>"","`"=>"","|"=>""
						//"."=>" "
						)
				);
		$st = trim($st);
		// Возвращаем результат.
	    return($st);
	}
	
// КОНЕЦ СТРОКОВЫЕ ОПЕРАЦИИ
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//убираем из строки все лишнее для получения корректного имени файла
	// function txtGetFileName($string_fname = ""){
		// if($string_fname!=""){
			// $this->txtSpaceRw = true;
			// $string_fname = $this->txtTranslit($string_fname,true);
			// $string_fname = $this->txtClearOtherSymbol($string_fname,true);
			// $this->txtSpaceRw = false;
			
			// $string_fname = strtr($string_fname,array("-"=>"_"));
			
			// return($string_fname);
		// }
		// else{
			// return(false);
		// }
	// }
	
	//подготавливаем текст к записи в базу
	//очищаем тект от не нужных символов и зменяем не угодные символы на угодные :)
	// если парметр установлен $tg='' то вырезаем вообще все теги
	// $tg=true/false
	var $kClearAllTag	 = false;// убираем все теги true/false, доступно при разработке
	var $kCTAllowTag	 = "";// Допустимые теги по умолчанию
	function txtClearText($txt,$tg = false){
		$txt = trim($txt);
		//меняем некоторые не угодные нам символы на их мнемоники или убираем совсем
		$txt = strtr($txt,
					array(
						"'"=>"\"",
						"«"=>"&laquo;","»"=>"&raquo;","&#171;"=>"&laquo;","&#187;"=>"&raquo;", //,""=>""
						)
				);		
		// убираем повторяющиеся переносы строк
		$txt = preg_replace("|[\r\n]+|", "\r\n", $txt); 
		$txt = preg_replace("|[\n]+|", "\n", $txt); 
		// если kClearAllTag не стоит true, значит можно использовать предопределенные теги
		// разрешенные теги
		$this->kCTAllowTag.=", <br>, <b>, </b>, <i>, </i>,  <u>, </u>, <center>, </center>, <hr>, <a>, </a>, <img>, <p>, </p>, <div>, </div>, <strong>, </strong>";
		$this->kCTAllowTag.=", <table>, <thead>, </thead>, <tbody>, </tbody>, </th>, <th>, </th>, <tr>, <td>, </td>, </tr>, </table>";
		$this->kCTAllowTag.=", <sub>, </sub>, <sup>, </sup>, <em>, </em>, <strike>, </strike>, <ol>, </ol>, <ul>, </ul>, <li>, </li>";
		$this->kCTAllowTag.=", <h1>, </h1>, <h2>, </h2>, <h3>, </h3>, <h4>, </h4>, <h5>, </h5>, <h6>, </h6>, <span>, </span>";
		$this->kCTAllowTag.=", <pre>, </pre> ,<blockquote>, </blockquote>, <cite>, </cite>, <abbr>, </abbr>, <acronym>, </acronym>";
		$this->kCTAllowTag.=", <input>, <form>, </form>, <textarea>, </textarea>, <select>, </select>, <option>,</option>";
		$this->kCTAllowTag.=", <dl>,</dl>,<dt>,</dt>,<dd>,</dd>,<marquee>,</marquee>,<noindex>,</noindex>,<noscript>,</noscript>";
		//вырезаем не разрешенные теги
		$tags = "";
		//$tags = $this->txtArrSetting["Edit"]['editor_allow_tag'];
		if(!$this->kClearAllTag){
			$tags = "\r, \n".$this->kCTAllowTag.(($tags!='')?", ":"").$tags;
		}
		else{
			$tags = "\r, \n".(($tags!='')?", ":"").$tags;
		}
		//если переменная tg истина то вырезаем все теги
		if($tg){$txt = strip_tags($txt,"\r, \n");} 
		else{$txt = strip_tags($txt,$tags);}
		return($txt);
	}
	
	// Обрезает строку до определенного количества символов, слова не режет
	// принцип работы: Сначала строка режется до заданного количства символов
	// полученная строка рабирается на массив последний элемент которого не учитывается
	// в качестве параметра передается строка и количество символов для обрезки
	// возвращает обрезанную строку
	function txtCroppingString($TextStr = '',$CountSymbol = 200){
		//количество символов должно быть обязательно целым числом
		$CountSymbol = (int)$CountSymbol;		
		if($CountSymbol > 10){
			//т.к. нам нужен только текст поэтому убираем всю html билеберду за исключением разрешенной, оставляем только текст
			$TextStr	 = $this->txtClearText($TextStr,true);
			// получаем образанную строку
			$TextStr	 = $TextStr." shadowtext"; // чтобы не выкидывались слова, когда символов текста меньше чем указано для обрезки
			$SubText	 = substr($TextStr,0,$CountSymbol);
			// Убираем возможно обрезанное слово в коце строки
			$SubText1	 = "";
			$arr = explode(" ", $SubText);
			reset($arr);
			for($i=0; $i <(count($arr)-1); $i++){$SubText1.=$arr[$i]." ";}
			$SubText = trim($SubText1);
			// возвращаем обрезанную строку
			return($SubText);
		}
		else{
			return($TextStr);
		}
	}

}//class_txt


?>
