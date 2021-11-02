<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// ����: text.php 
// �����: class_txt
// ��������� ������
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
class class_txt{
	

	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ��������� ��������
	// ����������� ��� ����� ������� �������� � �������� �� ��������
	// $ToLower - ���������� ��� ��� ��� ������ � ������ �������
	function txtTranslit($st,$ToLower = true){
		$st = trim($st);

		// ������� �������� "��������������" ������.
	    $st = strtr($st,"�����������������������","abvgdeeziyklmnoprstufhiei");
		$st = strtr($st,"�����Ũ�����������������","ABVGDEEZIYKLMNOPRSTUFHIEI");
		
		//����������� ��������� �����
	    $st = strtr($st,"qwertyuiopasdfghjklzxcvbnm","qwertyuiopasdfghjklzxcvbnm");
	    $st = strtr($st,"QWERTYUIOPASDFGHJKLZXCVBNM","QWERTYUIOPASDFGHJKLZXCVBNM");
	  
		// ����� - "���������������".
		$st = strtr($st,array(
						"�"=>"zh", "�"=>"ts", "�"=>"ch", "�"=>"sh",
						"�"=>"shch","�"=>"","�"=>"", "�"=>"yu", "�"=>"ya",
						"�"=>"ZH", "�"=>"TS", "�"=>"CH", "�"=>"sh",
						"�"=>"SHCH","�"=>"","�"=>"", "�"=>"YU", "�"=>"YA",
						"�"=>"i", "�"=>"I", "�"=>"ie", "�"=>"IE"
						)
				);	
				
		if($ToLower){
			$st = strtolower($st);
		}
	    // ���������� ���������.
	    return($st);
	}
	
	// ��������� ������ �����
	function txtClearStr($st){
		$st = strtr($st,array(
						"�"=>"","�"=>"","&laquo;"=>"","&raquo;"=>"","&#171;"=>"","&#187;"=>"",
						"<"=>"",">"=>"","&lt;"=>"","&gt;"=>"","&#60;"=>"","&#62;"=>"",
						"!"=>"","'"=>"","\""=>"","/"=>"",":"=>"","#"=>"",
						"+"=>"","$"=>"","%"=>"","&"=>"","("=>"",")"=>"","�"=>"",
						";"=>"",","=>"","\\"=>"","="=>"","�"=>"","?"=>"","`"=>"","|"=>""
						//"."=>" "
						)
				);
		$st = trim($st);
		// ���������� ���������.
	    return($st);
	}
	
// ����� ��������� ��������
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//������� �� ������ ��� ������ ��� ��������� ����������� ����� �����
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
	
	//�������������� ����� � ������ � ����
	//������� ���� �� �� ������ �������� � ������� �� ������� ������� �� ������� :)
	// ���� ������� ���������� $tg='' �� �������� ������ ��� ����
	// $tg=true/false
	var $kClearAllTag	 = false;// ������� ��� ���� true/false, �������� ��� ����������
	var $kCTAllowTag	 = "";// ���������� ���� �� ���������
	function txtClearText($txt,$tg = false){
		$txt = trim($txt);
		//������ ��������� �� ������� ��� ������� �� �� ��������� ��� ������� ������
		$txt = strtr($txt,
					array(
						"'"=>"\"",
						"�"=>"&laquo;","�"=>"&raquo;","&#171;"=>"&laquo;","&#187;"=>"&raquo;", //,""=>""
						)
				);		
		// ������� ������������� �������� �����
		$txt = preg_replace("|[\r\n]+|", "\r\n", $txt); 
		$txt = preg_replace("|[\n]+|", "\n", $txt); 
		// ���� kClearAllTag �� ����� true, ������ ����� ������������ ���������������� ����
		// ����������� ����
		$this->kCTAllowTag.=", <br>, <b>, </b>, <i>, </i>,  <u>, </u>, <center>, </center>, <hr>, <a>, </a>, <img>, <p>, </p>, <div>, </div>, <strong>, </strong>";
		$this->kCTAllowTag.=", <table>, <thead>, </thead>, <tbody>, </tbody>, </th>, <th>, </th>, <tr>, <td>, </td>, </tr>, </table>";
		$this->kCTAllowTag.=", <sub>, </sub>, <sup>, </sup>, <em>, </em>, <strike>, </strike>, <ol>, </ol>, <ul>, </ul>, <li>, </li>";
		$this->kCTAllowTag.=", <h1>, </h1>, <h2>, </h2>, <h3>, </h3>, <h4>, </h4>, <h5>, </h5>, <h6>, </h6>, <span>, </span>";
		$this->kCTAllowTag.=", <pre>, </pre> ,<blockquote>, </blockquote>, <cite>, </cite>, <abbr>, </abbr>, <acronym>, </acronym>";
		$this->kCTAllowTag.=", <input>, <form>, </form>, <textarea>, </textarea>, <select>, </select>, <option>,</option>";
		$this->kCTAllowTag.=", <dl>,</dl>,<dt>,</dt>,<dd>,</dd>,<marquee>,</marquee>,<noindex>,</noindex>,<noscript>,</noscript>";
		//�������� �� ����������� ����
		$tags = "";
		//$tags = $this->txtArrSetting["Edit"]['editor_allow_tag'];
		if(!$this->kClearAllTag){
			$tags = "\r, \n".$this->kCTAllowTag.(($tags!='')?", ":"").$tags;
		}
		else{
			$tags = "\r, \n".(($tags!='')?", ":"").$tags;
		}
		//���� ���������� tg ������ �� �������� ��� ����
		if($tg){$txt = strip_tags($txt,"\r, \n");} 
		else{$txt = strip_tags($txt,$tags);}
		return($txt);
	}
	
	// �������� ������ �� ������������� ���������� ��������, ����� �� �����
	// ������� ������: ������� ������ ������� �� ��������� ��������� ��������
	// ���������� ������ ���������� �� ������ ��������� ������� �������� �� �����������
	// � �������� ��������� ���������� ������ � ���������� �������� ��� �������
	// ���������� ���������� ������
	function txtCroppingString($TextStr = '',$CountSymbol = 200){
		//���������� �������� ������ ���� ����������� ����� ������
		$CountSymbol = (int)$CountSymbol;		
		if($CountSymbol > 10){
			//�.�. ��� ����� ������ ����� ������� ������� ��� html ��������� �� ����������� �����������, ��������� ������ �����
			$TextStr	 = $this->txtClearText($TextStr,true);
			// �������� ���������� ������
			$TextStr	 = $TextStr." shadowtext"; // ����� �� ������������ �����, ����� �������� ������ ������ ��� ������� ��� �������
			$SubText	 = substr($TextStr,0,$CountSymbol);
			// ������� �������� ���������� ����� � ���� ������
			$SubText1	 = "";
			$arr = explode(" ", $SubText);
			reset($arr);
			for($i=0; $i <(count($arr)-1); $i++){$SubText1.=$arr[$i]." ";}
			$SubText = trim($SubText1);
			// ���������� ���������� ������
			return($SubText);
		}
		else{
			return($TextStr);
		}
	}

}//class_txt


?>
