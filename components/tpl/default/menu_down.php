<?php
	$lnk_order_by = "";
	if($TblSetting["table"]['is_directory'] != "1"){
		if(isset($_GET["or"])){
			$lnk_order_by = "&or=".$_GET["or"]."&direction=".$_GET["direction"];
		}
	}

	$lnk_srch = (isset($lnk_srch))?$lnk_srch:"";

	if(!isset($arrPLS)){
		$arrPLS = array(
			"first" => "",
			"back" => "",
			"now" => "",
			"next" => "",
			"end" => "",
		);
	}

	$tmpArr = array(
		1110 => array(
			"link" => "?tbl=".$TblName.$PageLinkSrch."&pagenum=".$arrPLS['first'].$lnk_srch.$lnk_order_by,
			"img" => "page-first.gif",
			"title" => "На первую",
		),
		1111 => array(
			"link" => "?tbl=".$TblName.$PageLinkSrch."&pagenum=".$arrPLS['back'].$lnk_srch.$lnk_order_by,
			"img" => "page-prev.gif",
			"title" => "Предыдущая",
		),
		1112 => array(
			"link" => "",
			"img" => "",
			"title" => "
				<form method='get'  name='go_page' id='go_page' action=''>
					<input type='hidden' name='tbl' id='tbl' value='".$_GET["tbl"]."'>
					<input type='text' name='pagenum' id='pagenum' value='".((isset($_GET["pagenum"]))?$_GET["pagenum"]:$arrPLS['now'])."' style='border: 1px solid #00bfff; margin:0px;width: 30px; height:18; font-size:10px;'>
				</form>
			",
		),
		1113 => array(
			"link" => "?tbl=".$TblName.$PageLinkSrch."&pagenum=".$arrPLS['next'].$lnk_srch.$lnk_order_by,
			"img" => "page-next.gif",
			"title" => "Следующая",
		),
		1114 => array(
			"link" => "?tbl=".$TblName.$PageLinkSrch."&pagenum=".$arrPLS['end'].$lnk_srch.$lnk_order_by,
			"img" => "page-last.gif",
			"title" => "На последнюю",
		),
		1115 => array("link" => "","img" => "","title" => "",),
		1116 => array(
			"link" => "",
			"img" => "",
			"title" => "
				<span style='color: #00bfff; font-size:10px; font-weight: normal;'>
					<span title='всего записей'>".($row_count??0)."</span> / 
					<span title='записей на страницу'>".$TblSetting["table"]["limit"]."</span> / 
					<span title='всего страниц'>".$arrPLS['end']."</span> 
				</span>
			",
			"width" => false,
		),
		1117 => array("link" => "","img" => "","title" => "",),
		1118 => array(
			"link" => "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
			"img" => "refresh.gif",
			"title" => "Обновить список",
		),
	);
	if(usr_Access("admin")){
		$tmpArr+= array(
			1119111 => array("link" => "","img" => "","title" => "",),
			1120111 => array(
				"link" => "config_edit.php",
				"img" => "cog.gif",
				"title" => "Настройки",
			),
			11191111 => array("link" => "","img" => "","title" => "",),
			11201111 => array(
				"link" => "convert.php",
				"img" => "",
				"title" => "CI",
			),
		);
	}

	$tmpArr+= array(	
		1127 => array("link" => "","img" => "","title" => "",),
		1128 => array(
			"link" => $PageLink."&event=serach".$PageLinkSrch,
			"img" => "search.gif",
			"title" => "Поиск по текущей таблице: ".$TblName,
			"attributes" => array("id" => "showfrmsearch"),
		),
		1129 => array("link" => "","img" => "","title" => "",),
		1130 => array(
			"link" => $PageLink,
			"img" => "cross.gif",
			"title" => "Поиск сбросить",
			"attributes" => array("id" => "showfrmsearch"),
		),
		
	);
	if($arrSetting['Access']['UsePassword']){
		$tmpArr+= array(
			1000000 => array("link" => "","img" => "","title" => "",),
			1000001 => array(
				"link" => "quit.php",
				"img" => "",
				"title" => "Выход(".$_SESSION[D_NAME]['user']['Login'].")",
				"width" => false,
				"attributes" => array("style" => "color:red;"),
			),
		);
	}


	echo mTplMenu(mShowMenu($tmpArr, $arrSetting['Path']['ico']));
	unset($tmpArr);
?>
