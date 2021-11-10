<?php

	$upMenuType = (int)$arrSetting['Other']['upMenuType'];

	function mTplMenu($param = ""){ return("<table border='0' cellspacing='0' cellpadding='0'><tr>".$param."</tr></table>"); }
	function mTplMenuSec($param = "&nbsp;", $w = true){ return("<td ".(($w)?"width='20'":"")." align='center' valign='middle'>".$param."</td>"); }
	function mShowMenu(&$tmpArr,&$IcoPath){
		$ReturnVar = "";
		foreach($tmpArr as $val){
			if(isset($val['img']) && $val['img'] != ""){
				$echoVar = "<img src='".$IcoPath."/".$val['img']."' title='".$val['title']."' class='img_btn'>";
			}else{
				$echoVar = $val['title'];
			}
			if(isset($val['link']) && $val['link'] != ""){
				$echoVar = fLnk($echoVar, $val['link'], ((isset($val['attributes']) && is_array($val['attributes']))?$val['attributes']:""));
			}
			$echoVar = trim($echoVar);
			
			$ReturnVar.= ($echoVar != "")?mTplMenuSec($echoVar,((isset($val['width']))?$val['width']:true)):mTplMenuSec();
		}
		return($ReturnVar);
	}
	
	function mTplMenu1($param = ""){ return("<ul class='menu1'>".$param."</ul><div class='clr'></div>"); }
	function mTplMenu2($param = ""){ return("<ul>".$param."</ul>"); }
	function mShowMenu1(&$tmpArr, &$IcoPath, $two_level = false){
		$ReturnVar = "";
		foreach($tmpArr as $val){
			if(isset($val['img']) && $val['img'] != ""){
				$echoVar = "<img src='".$IcoPath."/".$val['img']."' title='".$val['title']."' class='img_btn'>".(($two_level)?"&nbsp;".$val['title']:"");
			}else{
				$echoVar = $val['title'];
			}
			if(isset($val['link']) && $val['link'] != ""){
				$echoVar = fLnk($echoVar, $val['link'], ((isset($val['attributes']) && is_array($val['attributes']))?$val['attributes']:""));
				if(isset($val['submenu'])){
					if(is_array($val['submenu'])){
						$echoVar.= mTplMenu2(mShowMenu1($val['submenu'], $IcoPath, true));
					}
				}
			}
			$echoVar = trim($echoVar);
			if(($echoVar != "")){
				$ReturnVar.= "<li>".$echoVar."</li>";
			}
		}
		return($ReturnVar);
	}
	
?>