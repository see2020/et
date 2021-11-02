<?php
	//inc.aj.cfg.window.ico.php
	
	$path_lnk = "aj.php?af=cfg.window.ico".((isset($_GET["ret_field"]))?"&ret_field=".$_GET['ret_field']:"");

	include($arrSetting["Path"]["class"]."/a.charset.php");
	$sql->sql_connect();
	$txt->txtSpaceRw = true;	

	$show_window_content = "";
	$show_window_content.= spr_WindowSetHead($arrSetting);

	//$return_var = "";
	$return_var.= spr_windowSetTpl("<a href='".$path_lnk."' id='id_refr'><img src='".$arrSetting['Path']['ico']."/refresh.gif'/></a>", "ico");
	//$return_var.= spr_windowSetTpl("", "max_block","left");
	
	$return_var.= spr_windowSetTpl("<a href='javascript:void(0);' OnClick=\"$.nmTop().close();\">Закрыть</a>", "min_block", "right");
	//$return_var.= spr_windowSetTpl("<a href='".$path_lnk."' id='id_back'>В начало</a>", "min_block", "right");
	$show_window_content.= spr_windowSetTpl($return_var, "row");
	
	//$show_window_content.= Message($_GET['ret_field']);
	$show_window_content.= Message($arrSetting['Path']['ico']);
	
	$flc->fListFiles($arrSetting['Path']['ico'],"",true);
	$iPath = $flc->fListFiles;
	
	if(is_array($iPath)){
		foreach($iPath as $keyFunc => $valFunc){
			$show_window_content.= "<a href='javascript:void(0);' title='".$valFunc['file']."' onclick='jsAddField(\"".$_GET['ret_field']."\",\"".$valFunc['file']."\");$.nmTop().close();'><img src=".$arrSetting['Path']['ico']."/".$valFunc['file']." style='padding: 7px;'></a>";
		}
	}

	$nyroModal = '$("#id_refr").nyroModal();';
	//$("#id_back").nyroModal();
	$show_window_content.= spr_WindowSetFoot($arrSetting, $nyroModal);
	
	echo "<div style=\"width: 800px; height: 600px;\">";
	echo $show_window_content;
	echo "</div>";
?>