<?php
	// настройки вкладок
	for ($i = 0; $i <= 15 ; $i++){
		$arrTabThisPage[$i]['name'] = "";
		$arrTabThisPage[$i]['page'] = "class='box' style='display: none; '";
	}
	//запоминаем на какой панеле сохранялись
	if(isset($_GET['panel'])){
		$arrTabThisPage[(int)$_GET['panel']]['name'] = "class='current'";
		$arrTabThisPage[(int)$_GET['panel']]['page'] = "class='box visible' style='display: block; '";
	}else{
		$arrTabThisPage[0]['name'] = "class='current'";
		$arrTabThisPage[0]['page'] = "class='box visible' style='display: block; '";
	}
?>