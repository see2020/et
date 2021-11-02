<?php
	// итоги в низу таблицы по колонкам с числовым типом
	if($TblSetting["table"]['TotalSumm'] == "1"){
		$show_field_foot = "";
		asort($TblSetting['sortfield']);
		foreach($TblSetting["sortfield"] as $key => $val){
			if($TblSetting[$key]["visible"] == 1){
				if($TblSetting[$key]["type"] == "number"){
					if($TblSetting["table"]['limit'] == 0 || $PrintPage){
						$result = $sql->sql_query("SELECT SUM(".$TblSetting[$key]['name'].") AS sum_".$TblSetting[$key]['name']." 
						FROM `".$sql->prefix_db.$TblSetting["table"]['name']."` ".$tblWhere." ".$tblOrder."");
					}
					else{
						$result = $sql->sql_query("SELECT SUM(".$TblSetting[$key]['name'].") AS sum_".$TblSetting[$key]['name']." 
						FROM `".$sql->prefix_db.$TblSetting["table"]['name']."` ".$tblWhere." ".$tblOrder." LIMIT ".$start.",".$TblSetting["table"]['limit']);
					}
					$query = $sql->sql_array($result);
					
					$show_field_foot.= GetTpl("field_foot", array(
					"value" => $query["sum_".$TblSetting[$key]['name']], 
					"attribute" => "", ), 
					$TblDefTplPath);
					}else{
					$show_field_foot.= GetTpl("field_foot", array(
					"value" => "&nbsp;", 
					"attribute" => "", ), 
					$TblDefTplPath);
				}
			}
		}
		$show_row.= GetTpl("row_foot", array("field_foot" => $show_field_foot), $TblDefTplPath);
	}	
?>