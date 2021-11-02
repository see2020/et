<?php
	// запрос к таблице
	$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["SelectFunction"];
	if(file_exists($func_file) && is_file($func_file)){include($func_file);}
	else{
		$tblOrder = (isset($tblOrder))?$tblOrder:"";
		$tblWhere = (isset($tblWhere))?$tblWhere:"";
		if($TblSetting["table"]["limit"] == 0 || $PrintPage){
			$result = $sql->sql_query("SELECT a.* FROM `".$sql->prefix_db.$TblSetting["table"]["name"]."` AS a ".$tblWhere." ".$tblOrder."");
		}
		else{
			$row_count	 = $sql->sql_rows($sql->sql_query("SELECT a.* FROM `".$sql->prefix_db.$TblSetting["table"]["name"]."` AS a ".$tblWhere." ".$tblOrder.""));
			$start		 = PageGetCount($pg,$row_count,$TblSetting["table"]["limit"]);
			$arrPLS		 = PageListShow($pg, $row_count, $TblSetting["table"]["limit"]);
			$result		 = $sql->sql_query("SELECT a.* FROM `".$sql->prefix_db.$TblSetting["table"]["name"]."` AS a ".$tblWhere." ".$tblOrder." LIMIT ".$start.",".$TblSetting["table"]["limit"]);
		}		
	}
	$func_file = "";
?>