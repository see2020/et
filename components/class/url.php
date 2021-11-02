<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// файл: url.class 
// класс: class_url
// Класс для работы с url
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
class class_url{

	// раскодирует закодированную строку js:escape(), php аналог js:unescape()
	function uJsUrlDecode($str) { 
		$js_rus_unicode['%20'] = ' '; 
		$js_rus_unicode['%2C'] = ','; 
		$js_rus_unicode['%21'] = '!'; 
		$js_rus_unicode['%22'] = '"'; 
		$js_rus_unicode['%3B'] = ';'; 
		$js_rus_unicode['%25'] = '%'; 
		$js_rus_unicode['%3A'] = ':'; 
		$js_rus_unicode['%3F'] = '?'; 
		$js_rus_unicode['%28'] = '('; 
		$js_rus_unicode['%29'] = ')'; 
		$js_rus_unicode['%7E'] = '~'; 
		$js_rus_unicode['%23'] = '#'; 
		$js_rus_unicode['%24'] = '$'; 
		$js_rus_unicode['%5E'] = '^'; 
		$js_rus_unicode['%26'] = '&'; 
		$js_rus_unicode['%3D'] = '='; 
		$js_rus_unicode['%27'] = "'"; 
		$js_rus_unicode['%u2116'] = '№';
		
		$js_rus_unicode['%u0451'] = 'ё'; 
		$js_rus_unicode['%u0439'] = 'й'; 
		$js_rus_unicode['%u0446'] = 'ц'; 
		$js_rus_unicode['%u0443'] = 'у'; 
		$js_rus_unicode['%u043A'] = 'к'; 
		$js_rus_unicode['%u0435'] = 'е'; 
		$js_rus_unicode['%u043D'] = 'н'; 
		$js_rus_unicode['%u0433'] = 'г'; 
		$js_rus_unicode['%u0448'] = 'ш'; 
		$js_rus_unicode['%u0449'] = 'щ'; 
		$js_rus_unicode['%u0437'] = 'з'; 
		$js_rus_unicode['%u0445'] = 'х'; 
		$js_rus_unicode['%u044A'] = 'ъ'; 
		$js_rus_unicode['%u0444'] = 'ф'; 
		$js_rus_unicode['%u044B'] = 'ы'; 
		$js_rus_unicode['%u0432'] = 'в'; 
		$js_rus_unicode['%u0430'] = 'а'; 
		$js_rus_unicode['%u043F'] = 'п'; 
		$js_rus_unicode['%u0440'] = 'р'; 
		$js_rus_unicode['%u043E'] = 'о'; 
		$js_rus_unicode['%u043B'] = 'л'; 
		$js_rus_unicode['%u0434'] = 'д'; 
		$js_rus_unicode['%u0436'] = 'ж'; 
		$js_rus_unicode['%u044D'] = 'э'; 
		$js_rus_unicode['%u044F'] = 'я'; 
		$js_rus_unicode['%u0447'] = 'ч'; 
		$js_rus_unicode['%u0441'] = 'с'; 
		$js_rus_unicode['%u043C'] = 'м'; 
		$js_rus_unicode['%u0438'] = 'и'; 
		$js_rus_unicode['%u0442'] = 'т'; 
		$js_rus_unicode['%u044C'] = 'ь'; 
		$js_rus_unicode['%u0431'] = 'б'; 
		$js_rus_unicode['%u044E'] = 'ю'; 
		$js_rus_unicode['%u0401'] = 'Ё'; 
		$js_rus_unicode['%u0419'] = 'Й'; 
		$js_rus_unicode['%u0426'] = 'Ц'; 
		$js_rus_unicode['%u0423'] = 'У'; 
		$js_rus_unicode['%u041A'] = 'К'; 
		$js_rus_unicode['%u0415'] = 'Е'; 
		$js_rus_unicode['%u041D'] = 'Н'; 
		$js_rus_unicode['%u0413'] = 'Г'; 
		$js_rus_unicode['%u0428'] = 'Ш'; 
		$js_rus_unicode['%u0429'] = 'Щ'; 
		$js_rus_unicode['%u0417'] = 'З'; 
		$js_rus_unicode['%u0425'] = 'Х'; 
		$js_rus_unicode['%u042A'] = 'Ъ'; 
		$js_rus_unicode['%u0424'] = 'Ф'; 
		$js_rus_unicode['%u042B'] = 'Ы'; 
		$js_rus_unicode['%u0412'] = 'В'; 
		$js_rus_unicode['%u0410'] = 'А'; 
		$js_rus_unicode['%u041F'] = 'П'; 
		$js_rus_unicode['%u0420'] = 'Р'; 
		$js_rus_unicode['%u041E'] = 'О'; 
		$js_rus_unicode['%u041B'] = 'Л'; 
		$js_rus_unicode['%u0414'] = 'Д'; 
		$js_rus_unicode['%u0416'] = 'Ж'; 
		$js_rus_unicode['%u042D'] = 'Э'; 
		$js_rus_unicode['%u042F'] = 'Я'; 
		$js_rus_unicode['%u0427'] = 'Ч'; 
		$js_rus_unicode['%u0421'] = 'С'; 
		$js_rus_unicode['%u041C'] = 'М'; 
		$js_rus_unicode['%u0418'] = 'И'; 
		$js_rus_unicode['%u0422'] = 'Т'; 
		$js_rus_unicode['%u042C'] = 'Ь'; 
		$js_rus_unicode['%u0411'] = 'Б'; 
		$js_rus_unicode['%u042E'] = 'Ю'; 
		 
		foreach ($js_rus_unicode as $k=>$v) { 
			$str = str_replace($k,$v,$str); 
		} 
		$str = urldecode($str); 
		return($str); 
	} 

	// кодирует строку по аналогии с js:escape()
	function uJsUrlEncode($str){
		
		$js_rus_unicode = array(
		' ' => '%20',
		',' => '%2C',
		'!' => '%21',
		'"' => '%22',
		';' => '%3B',
		'%' => '%25',
		':' => '%3A',
		'?' => '%3F',
		'(' => '%28',
		')' => '%29',
		'~' => '%7E',
		'#' => '%23',
		'$' => '%24',
		'^' => '%5E',
		'&' => '%26',
		'=' => '%3D',
		"'" => '%27',
		'№' => '%u2116',
		
		'А' => '%u0410', 
		'Б' => '%u0411', 
		'В' => '%u0412', 
		'Г' => '%u0413', 
		'Д' => '%u0414', 
		'Е' => '%u0415', 
		'Ё' => '%u0401', 
		'Ж' => '%u0416', 
		'З' => '%u0417', 
		'И' => '%u0418',
		'Й' => '%u0419',
		'К' => '%u041A',
		'Л' => '%u041B',
		'М' => '%u041C',
		'Н' => '%u041D',
		'О' => '%u041E',
		'П' => '%u041F',
		'Р' => '%u0420',
		'С' => '%u0421',
		'Т' => '%u0422',
		'У' => '%u0423',
		'Ф' => '%u0424',
		'Х' => '%u0425',
		'Ц' => '%u0426',
		'Ч' => '%u0427',
		'Ш' => '%u0428',
		'Щ' => '%u0429',
		'Ъ' => '%u042A',
		'Ы' => '%u042B',
		'Ь' => '%u042C',
		'Э' => '%u042D',
		'Ю' => '%u042E',
		'Я' => '%u042F',

		'а' => '%u0430',
		'б' => '%u0431',
		'в' => '%u0432',
		'г' => '%u0433',
		'д' => '%u0434',
		'е' => '%u0435',
		'ё' => '%u0451',
		'ж' => '%u0436',
		'з' => '%u0437',
		'и' => '%u0438',
		'й' => '%u0439',
		'к' => '%u043A',
		'л' => '%u043B',
		'м' => '%u043C',
		'н' => '%u043D',
		'о' => '%u043E',
		'п' => '%u043F',
		'р' => '%u0440',
		'с' => '%u0441',
		'т' => '%u0442',
		'у' => '%u0443',
		'ф' => '%u0444',
		'х' => '%u0445',
		'ц' => '%u0446',
		'ч' => '%u0447',
		'ш' => '%u0448',
		'щ' => '%u0449',
		'ъ' => '%u044A',
		'ы' => '%u044B',
		'ь' => '%u044C',
		'э' => '%u044D',
		'ю' => '%u044E',
		'я' => '%u044F');
		
		//$str = strtr($str, $js_rus_unicode);
		
		foreach ($js_rus_unicode as $k=>$v) { 
			$str = str_replace($k,$v,$str); 
		} 
		$str = urlencode($str);
		
		return ($str);
	}

	
}

?>