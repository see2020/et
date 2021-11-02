<?php
	/* навигация по справочнику */
	if($TblSetting["table"]['is_directory'] == "1"){
		/* список дочерних элементов */
		if($qChange[$arrTableSpr["field_type_row"]] == 1 && $_GET[$TblFieldPrimaryKey] != 0){
			echo "<br>";
			echo "<br>";
			$arrAction["FieldRootName"]	 = $arrTableSpr["id_root"];
			$arrAction["FieldRootValue"] = $qChange[$arrTableSpr["id"]];
			$arrAction["order_by"]		 = $arrTableSpr["order_by"];
			include(GetIncFile($arrSetting,"inc.form.edit.spr_child_list.php", $TblSetting["table"]['name']));
		}
	}
?>