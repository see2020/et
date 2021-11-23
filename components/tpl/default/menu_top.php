<?php

	if(isset($_GET['tbl'])){
		$tmpArr = array(
			5 => array(
				"link" => "?tbl=".$_GET["tbl"],
				"img" => "main-list.png",
				"title" => "К списку: ".$tbl_description."",
			),

			6 => array("link" => "","img" => "","title" => "",),
			10 => array(
				"link" => "".$PageLink."&id=0&event=edit",
				"img" => "add.gif",
				"title" => "Добавить запись в текущую таблицу: ".$tbl_description."",
			),
		);
	}else{
		$tmpArr = array(
			10 => array(
				"link" => "./tables.php",
				"img" => "page-prev.gif",
				"title" => "К таблицам",
			),
		);				
	}
	
//	if($upMenuType == 1){
//		$tmpArr+= mUpMenuArr($TblList);
//	}
//	elseif($upMenuType == 2){
		// менюшку всегда формируем из таблицы меню
		if($sql->sql_table_exist($arrSetting["Other"]["tablesysmenu"])){
			$tmpArr+= mUpMenuArrTbl($sql);
		}
		else{
			$tmpArr+= array(
				11140 => array("link" => "","img" => "sep.gif","title" => "",),
				11150 => array(
					"link" => "#",
					"img" => "",
//					"title" => $arrSetting['Other']['upMenuTypeName']??""."<img src='".$arrSetting['Path']['ico']."/arrow.gif'>",
					"title" => "<img src='".$arrSetting['Path']['ico']."/arrow.gif'>",
					"attributes" => array("target" => "_blank"),
					"submenu" => mUpMenuArr($TblList),
				),
			);
		}

//	}

	if(usr_Access("admin")){
		$tmpArr+= array(
			1110 => array("link" => "","img" => "sep.gif","title" => "",),
			1111 => array(
				"link" => "./dump.php",
				"img" => "mysql_dump.gif",
				"title" => "Копия всех таблиц",
			),
			1112 => array("link" => "","img" => "","title" => "",),
			1113 => array(
				"link" => "./files.php",
				"img" => "folder-album.gif",
				"title" => "Файлы",
			),

		);		
	}
	
	$tmpArr+= array(
		1114 => array("link" => "","img" => "sep.gif","title" => "",),
		1115 => array(
			"link" => "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&print=1",
			"img" => "printer.gif",
			"title" => "Печать текущей таблицы: ".($TblName??"")."",
			"attributes" => array("target" => "_blank"),
		),
		1116 => array("link" => "","img" => "","title" => "",),
		1117 => array(
			"link" => "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."",
			"img" => "refresh.gif",
			"title" => "Обновить страницу",
		),
		1118 => array("link" => "","img" => "sep.gif","title" => "",),
		1119 => array(
			"link" => "".$PageLink."&event=serach".($PageLinkSrch??"")."",
			"img" => "search.gif",
			"title" => "Поиск по текущей таблице: ".($TblName??"")."",
		),
		
		1120 => array("link" => "","img" => "","title" => "
		<form method='get'  name='srch_tpl' id='srch_tpl' action='?tbl=".((isset($_GET["tbl"]))?$_GET["tbl"]:($TblName??""))."&event=serach'>
		<input type='hidden' name='tbl' id='tbl' value='".((isset($_GET["tbl"]))?$_GET["tbl"]:($TblName??""))."'>
		<input type='hidden' name='srch' id='srch' value='1'>
		<input type='text' name='allfield' id='allfield' value='".((isset($_GET["allfield"]))?$_GET["allfield"]:"")."' style='border: 1px solid ".((isset($_GET["srch"]))?"red":"#00bfff")."; margin:0px;width: 100px; height:18; font-size:10px;'>
		</form>
		",),
		1122 => array(
			"link" => "javascript: document.srch_tpl.submit();",
			"img" => "page-next.gif",
			"title" => "Найти",
		),
		1124 => array(
			"link" => $PageLink,
			"img" => "cross.gif",
			"title" => "Поиск сбросить",
			"attributes" => array("id" => "showfrmsearch"),
		),
	);

//	if($upMenuType== 1){
//		echo mTplMenu(mShowMenu($tmpArr, $arrSetting['Path']['ico']));
//	}
//	elseif($upMenuType == 2){
		echo mTplMenu1(mShowMenu1($tmpArr, $arrSetting['Path']['ico']));
//	}
	unset($tmpArr);
 ?>

