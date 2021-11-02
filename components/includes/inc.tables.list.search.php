<?php
	// обработка для поискового условия
	if(isset($_GET['srch'])){
		$tblWhereSrch	 = "";
		$SrchLink		 = "&srch=1";
		if($AllFieldSrch == ""){
			$FirstElem = 0;
			foreach($TblSetting["sortfieldform"] as $key => $val){
				if($TblSetting[$key]["type"] != "support" && $TblSetting[$key]["for_search"] == "1"){
					if((isset($_GET[$TblSetting[$key]["name"]]) && $_GET[$TblSetting[$key]["name"]] != "") 
						|| (
							isset($_GET[$TblSetting[$key]["name"]."_start"]) && isset($_GET[$TblSetting[$key]["name"]."_end"]) &&
							$_GET[$TblSetting[$key]["name"]."_start"] != "" && $_GET[$TblSetting[$key]["name"]."_end"] != ""
						)
					){
						if($TblSetting[$key]["type"] == "date"){
							if($_GET[$TblSetting[$key]["name"]."_tp"] != 0){
								$tblWhereSrch = trim($tblWhereSrch);
								$dt1 = date("Y-m-d",strtotime($_GET[$TblSetting[$key]["name"]."_start"]));
								$dt2 = date("Y-m-d",strtotime($_GET[$TblSetting[$key]["name"]."_end"]));
								$tblWhereSrch.= " ".(($tblWhereSrch == "")?"":$TblSetting["table"]['WhereType'])." (a.`".$TblSetting[$key]["name"]."` >= '".strtotime($dt1." 00:00:00")."' AND a.`".$TblSetting[$key]["name"]."` <= '".strtotime($dt2." 23:59:59")."')";
							}
						}
						else{
							if($_GET[$TblSetting[$key]["name"]."_tp"] == 10){
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."`='".$_GET[$TblSetting[$key]["name"]]."'";
							}
							elseif($_GET[$TblSetting[$key]["name"]."_tp"] == 11){
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."`<>'".$_GET[$TblSetting[$key]["name"]]."'";
							}
							elseif($_GET[$TblSetting[$key]["name"]."_tp"] == 12){
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."` LIKE '%".$_GET[$TblSetting[$key]["name"]]."%'";
							}
							elseif($_GET[$TblSetting[$key]["name"]."_tp"] == 13){
								$arr_in_val = explode(",",$_GET[$TblSetting[$key]["name"]]);
								for($i = 0; $i < count($arr_in_val); $i++){
									$arr_in_val[$i] = "'".$arr_in_val[$i]."'";
								}
								$txt_in_val = implode(",",$arr_in_val);
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."` IN (".$txt_in_val.")";
							}
							elseif($_GET[$TblSetting[$key]["name"]."_tp"] == 14){
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."`>='".$_GET[$TblSetting[$key]["name"]]."'";
							}
							elseif($_GET[$TblSetting[$key]["name"]."_tp"] == 15){
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."`<='".$_GET[$TblSetting[$key]["name"]]."'";
							}
							elseif($_GET[$TblSetting[$key]["name"]."_tp"] == 16){
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."`>'".$_GET[$TblSetting[$key]["name"]]."'";
							}
							elseif($_GET[$TblSetting[$key]["name"]."_tp"] == 17){
								$tblWhereSrch.=" ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." a.`".$TblSetting[$key]["name"]."`<'".$_GET[$TblSetting[$key]["name"]]."'";
							}
							else{
							}
						}
						$FirstElem++;
					}
				}
			}
		}
		else{
			$tmp_array_af = array("text","textarea","link","directory_name","number",);
			$FirstElem = 0;
			foreach($TblSetting["sortfieldform"] as $key => $val){
				if(in_array($TblSetting[$key]["type"],$tmp_array_af) && $TblSetting[$key]["for_search"] == "1"){
					$tblWhereSrch.=" ".(($FirstElem==0)?"":" OR ")." a.`".$TblSetting[$key]["name"]."` LIKE '%".$AllFieldSrch."%'";
					$FirstElem++;
				}
			}
			unset($tmp_array_af);
		}
		
		$tblWhere.= " AND (".$tblWhereSrch.")";
	}
?>