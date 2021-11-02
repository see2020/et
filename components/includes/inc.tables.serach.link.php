<?php
	//srch
	$PageLinkSrch = "";
	$AllFieldSrch = "";
	if(isset($_GET['srch'])){
		$PageLinkSrch = "&srch=1";
		if(isset($_GET['allfield'])){
			if($_GET['allfield'] != ""){
				$AllFieldSrch = trim($_GET['allfield']);
				$TblSetting["table"]['WhereType'] = " or ";
			}
		}
		if($AllFieldSrch == ""){
			foreach($TblSetting["sortfieldsearch"] as $key => $val){
				if($TblSetting[$key]["type"] != "support"){
					
					// if(isset($_GET[$TblSetting[$key]["name"]]) 
						// && $_GET[$TblSetting[$key]["name"]] != "" 
						// && $TblSetting[$key]["name"] != $TblFieldPrimaryKey 
						// || ($_GET[$TblSetting[$key]["name"]."_start"] != "" && $_GET[$TblSetting[$key]["name"]."_end"] != "")){
						
					if(isset($_GET[$TblSetting[$key]["name"]]) && $TblSetting[$key]["name"] != $TblFieldPrimaryKey 
						|| (
							isset($_GET[$TblSetting[$key]["name"]."_start"]) && isset($_GET[$TblSetting[$key]["name"]."_end"]) &&
							$_GET[$TblSetting[$key]["name"]."_start"] != "" && $_GET[$TblSetting[$key]["name"]."_end"] != ""
							)
						){
						
						if($TblSetting[$key]["type"] == "date"){
							$PageLinkSrch.= "&".$TblSetting[$key]["name"]."_tp"."=".$_GET[$TblSetting[$key]["name"]."_tp"];
							$PageLinkSrch.= "&".$TblSetting[$key]["name"]."_start=".$_GET[$TblSetting[$key]["name"]."_start"];
							$PageLinkSrch.= "&".$TblSetting[$key]["name"]."_end=".$_GET[$TblSetting[$key]["name"]."_end"];
						}else{
							$PageLinkSrch.= "&".$TblSetting[$key]["name"]."_tp"."=".$_GET[$TblSetting[$key]["name"]."_tp"];
							$PageLinkSrch.= "&".$TblSetting[$key]["name"]."=".$_GET[$TblSetting[$key]["name"]];
						}
					}
				}
			}
		}
		else{
			$PageLinkSrch.= "&allfield=".$AllFieldSrch;
		}
	}
	if(isset($_GET['pagenum']) && @$_GET['pagenum']!=0){$pg=(int)$_GET['pagenum'];$PageLink.= "&pagenum=".$_GET['pagenum'];}else{$pg=1;}
	if(isset($_GET[$TblFieldPrimaryKey]) && @$_GET[$TblFieldPrimaryKey]!=0){$IdChange = $_GET[$TblFieldPrimaryKey];}else{$IdChange = 0;}
?>