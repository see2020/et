<?php
	$show_table = "";
	$show_field = "";
	
	asort($TblSetting["sortfield"]);
	foreach($TblSetting["sortfield"] as $key=>$val){
		if(!empty($TblSetting[$key]) && $TblSetting[$key]["visible"] == 1){
			$tpl_name_field	 = "field_head";
			$tpl_path		 = $TblDefTplPath;
			if($TblSetting[$key]['theme'] != ""){
				if(file_exists($allSettings["TblPath"]["theme"]."/".trim($TblSetting[$key]['theme'])."_head.php")){
					$tpl_name_field	 = trim($TblSetting[$key]['theme'])."_head";
					$tpl_path		 = $allSettings["TblPath"]["theme"];
				}
			}
			$show_field.= GetTpl($tpl_name_field, array(
			"value" => (($TblSetting[$key]['description']=="")?$TblSetting[$key]['name']:$TblSetting[$key]['description']), 
			"attribute" => (($TblSetting[$key]['width']!=0 && $TblSetting[$key]['width']!="")?"width='".$TblSetting[$key]['width']."'":""), ), 
			$tpl_path);
		}
	}

	$show_row_head = GetTpl("row_head", array("field_head" => $show_field), $TblDefTplPath);

	$tblWhere = ($TblSetting["table"]['StatusField']!="" && $TblSetting["table"]['AllRows']=="0" )?"where `".$TblSetting["table"]['StatusField']."`='1'":"where `".$TblFieldPrimaryKey."`<>'0'";
	if($TblSetting["table"]['order'] != ""){
		$tblOrder = "ORDER BY ".$TblSetting["table"]['order'];
	}
	else{
		$tblOrder = " ORDER BY ".$TblSetting["table"]["directory_type"]." DESC, ".$TblSetting["table"]["directory_name"]." ASC";
	}

	$tblWhereField = "";
	foreach($TblSetting["sortfield"] as $key=>$val){
		if(!empty($TblSetting[$key]) && $TblSetting[$key]["visible"] == 1
		&& isset($_GET[$TblSetting[$key]["name"]])
		&& $TblSetting[$key]['primarytable'] != "" 
		&& $TblSetting[$key]['primarykey'] != "" 
		&& $TblSetting[$key]['primaryvalue'] != ""){
			$tblWhereField.= " ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." `".$TblSetting[$key]["name"]."` = '".$_GET[$TblSetting[$key]["name"]]."'";
		}
	}
	if($tblWhereField != ""){
		$tblWhere.= " and (".$tblWhereField.")";
	}

	$tblWhere.= " and ".$arrAction["FieldRootName"]."='".$arrAction["FieldRootValue"]."' ";

	// запрос к таблице
	// если лимит записей не установлен или это форма для печати то выводим все записи
	$show_row  = "";
	$result = $sql->sql_query("select * from `".$sql->prefix_db.$TblSetting["table"]['name']."` ".$tblWhere." ".$tblOrder."");
	if($sql->sql_rows($result)){

		$tmpArrSetField = $TblSetting["sortfield"];
		while($query = $sql->sql_array($result)){
				$show_field = "";
				foreach($TblSetting["sortfield"] as $key=>$val){
					$tmpLnk = '';
					if(!empty($TblSetting[$key])){
						$tmpLnk = $TblSetting[$key]['link'];
						if($TblSetting[$key]["visible"] == 1){
							include(GetIncFile($arrSetting,"inc.tables.list.row.php", $TblSetting["table"]["name"]));
						}
					}
				}
			$show_row.= GetTpl("row", array("field" => $show_field), $TblDefTplPath);
		}
	}
	$show_list = GetTpl("list", array("row_head" => $show_row_head, "row" => $show_row, ), $TblDefTplPath);
	echo $show_list;
?>