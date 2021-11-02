<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// ����: url.class 
// �����: class_url
// ����� ��� ������ � url
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
class class_url{

	// ����������� �������������� ������ js:escape(), php ������ js:unescape()
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
		$js_rus_unicode['%u2116'] = '�';
		
		$js_rus_unicode['%u0451'] = '�'; 
		$js_rus_unicode['%u0439'] = '�'; 
		$js_rus_unicode['%u0446'] = '�'; 
		$js_rus_unicode['%u0443'] = '�'; 
		$js_rus_unicode['%u043A'] = '�'; 
		$js_rus_unicode['%u0435'] = '�'; 
		$js_rus_unicode['%u043D'] = '�'; 
		$js_rus_unicode['%u0433'] = '�'; 
		$js_rus_unicode['%u0448'] = '�'; 
		$js_rus_unicode['%u0449'] = '�'; 
		$js_rus_unicode['%u0437'] = '�'; 
		$js_rus_unicode['%u0445'] = '�'; 
		$js_rus_unicode['%u044A'] = '�'; 
		$js_rus_unicode['%u0444'] = '�'; 
		$js_rus_unicode['%u044B'] = '�'; 
		$js_rus_unicode['%u0432'] = '�'; 
		$js_rus_unicode['%u0430'] = '�'; 
		$js_rus_unicode['%u043F'] = '�'; 
		$js_rus_unicode['%u0440'] = '�'; 
		$js_rus_unicode['%u043E'] = '�'; 
		$js_rus_unicode['%u043B'] = '�'; 
		$js_rus_unicode['%u0434'] = '�'; 
		$js_rus_unicode['%u0436'] = '�'; 
		$js_rus_unicode['%u044D'] = '�'; 
		$js_rus_unicode['%u044F'] = '�'; 
		$js_rus_unicode['%u0447'] = '�'; 
		$js_rus_unicode['%u0441'] = '�'; 
		$js_rus_unicode['%u043C'] = '�'; 
		$js_rus_unicode['%u0438'] = '�'; 
		$js_rus_unicode['%u0442'] = '�'; 
		$js_rus_unicode['%u044C'] = '�'; 
		$js_rus_unicode['%u0431'] = '�'; 
		$js_rus_unicode['%u044E'] = '�'; 
		$js_rus_unicode['%u0401'] = '�'; 
		$js_rus_unicode['%u0419'] = '�'; 
		$js_rus_unicode['%u0426'] = '�'; 
		$js_rus_unicode['%u0423'] = '�'; 
		$js_rus_unicode['%u041A'] = '�'; 
		$js_rus_unicode['%u0415'] = '�'; 
		$js_rus_unicode['%u041D'] = '�'; 
		$js_rus_unicode['%u0413'] = '�'; 
		$js_rus_unicode['%u0428'] = '�'; 
		$js_rus_unicode['%u0429'] = '�'; 
		$js_rus_unicode['%u0417'] = '�'; 
		$js_rus_unicode['%u0425'] = '�'; 
		$js_rus_unicode['%u042A'] = '�'; 
		$js_rus_unicode['%u0424'] = '�'; 
		$js_rus_unicode['%u042B'] = '�'; 
		$js_rus_unicode['%u0412'] = '�'; 
		$js_rus_unicode['%u0410'] = '�'; 
		$js_rus_unicode['%u041F'] = '�'; 
		$js_rus_unicode['%u0420'] = '�'; 
		$js_rus_unicode['%u041E'] = '�'; 
		$js_rus_unicode['%u041B'] = '�'; 
		$js_rus_unicode['%u0414'] = '�'; 
		$js_rus_unicode['%u0416'] = '�'; 
		$js_rus_unicode['%u042D'] = '�'; 
		$js_rus_unicode['%u042F'] = '�'; 
		$js_rus_unicode['%u0427'] = '�'; 
		$js_rus_unicode['%u0421'] = '�'; 
		$js_rus_unicode['%u041C'] = '�'; 
		$js_rus_unicode['%u0418'] = '�'; 
		$js_rus_unicode['%u0422'] = '�'; 
		$js_rus_unicode['%u042C'] = '�'; 
		$js_rus_unicode['%u0411'] = '�'; 
		$js_rus_unicode['%u042E'] = '�'; 
		 
		foreach ($js_rus_unicode as $k=>$v) { 
			$str = str_replace($k,$v,$str); 
		} 
		$str = urldecode($str); 
		return($str); 
	} 

	// �������� ������ �� �������� � js:escape()
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
		'�' => '%u2116',
		
		'�' => '%u0410', 
		'�' => '%u0411', 
		'�' => '%u0412', 
		'�' => '%u0413', 
		'�' => '%u0414', 
		'�' => '%u0415', 
		'�' => '%u0401', 
		'�' => '%u0416', 
		'�' => '%u0417', 
		'�' => '%u0418',
		'�' => '%u0419',
		'�' => '%u041A',
		'�' => '%u041B',
		'�' => '%u041C',
		'�' => '%u041D',
		'�' => '%u041E',
		'�' => '%u041F',
		'�' => '%u0420',
		'�' => '%u0421',
		'�' => '%u0422',
		'�' => '%u0423',
		'�' => '%u0424',
		'�' => '%u0425',
		'�' => '%u0426',
		'�' => '%u0427',
		'�' => '%u0428',
		'�' => '%u0429',
		'�' => '%u042A',
		'�' => '%u042B',
		'�' => '%u042C',
		'�' => '%u042D',
		'�' => '%u042E',
		'�' => '%u042F',

		'�' => '%u0430',
		'�' => '%u0431',
		'�' => '%u0432',
		'�' => '%u0433',
		'�' => '%u0434',
		'�' => '%u0435',
		'�' => '%u0451',
		'�' => '%u0436',
		'�' => '%u0437',
		'�' => '%u0438',
		'�' => '%u0439',
		'�' => '%u043A',
		'�' => '%u043B',
		'�' => '%u043C',
		'�' => '%u043D',
		'�' => '%u043E',
		'�' => '%u043F',
		'�' => '%u0440',
		'�' => '%u0441',
		'�' => '%u0442',
		'�' => '%u0443',
		'�' => '%u0444',
		'�' => '%u0445',
		'�' => '%u0446',
		'�' => '%u0447',
		'�' => '%u0448',
		'�' => '%u0449',
		'�' => '%u044A',
		'�' => '%u044B',
		'�' => '%u044C',
		'�' => '%u044D',
		'�' => '%u044E',
		'�' => '%u044F');
		
		//$str = strtr($str, $js_rus_unicode);
		
		foreach ($js_rus_unicode as $k=>$v) { 
			$str = str_replace($k,$v,$str); 
		} 
		$str = urlencode($str);
		
		return ($str);
	}

	
}

?>