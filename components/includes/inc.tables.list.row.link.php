<?php
	$tmpLnk	 = $PageLink."&".$TblFieldPrimaryKey."=".$query[$TblFieldPrimaryKey]."&event=edit".$PageLinkSrch;
	$tmpName = $query[$TblSetting[$key]['name']];
	
	if($TblSetting[$key]['link']!=""){
		$tmpLnk = $TblSetting[$key]['link'];
		$tmpLnk = SetTplLnk($tmpArrSetField, $query, $TblSetting[$key]['link']);
	}
	if($TblSetting[$key]['link_image']=="1"){
		if($TblSetting[$key]['image']!=""){
			$tmpName = "<img src='".$arrSetting['Path']['ico']."/".$TblSetting[$key]['image']."' alt='".$query[$TblSetting[$key]['name']]."' title='".$query[$TblSetting[$key]['name']]."' />";	
		}
		else{
			if($TblSetting[$key]['description'] != ""){
				$tmpName = $TblSetting[$key]['description'];
			}
			else{
				$tmpName = $TblSetting[$key]['name'];
			}
		}
	}
	$ShowRow = fLnk($tmpName, $tmpLnk, array("target" => (($TblSetting[$key]['link_newwindow']=="1")?"_blank":""),"title" =>$query[$TblSetting[$key]['name']],"style" => $TblStatusColored));
?>