<?php

			$show_table = "";
			// выполняем функцию перед загрузкой таблицы
			if(	isset($allSettings["TblPath"]["function"])
				&& file_exists($allSettings["TblPath"]["function"]."/".$TblSetting['table']['BeforeLoadingTable']) 
				&& is_file($allSettings["TblPath"]["function"]."/".$TblSetting['table']['BeforeLoadingTable'])){
				include($allSettings["TblPath"]["function"]."/".$TblSetting['table']['BeforeLoadingTable']);
			}
				$show_field = "";
				reset($TblSetting["sortfield"]);
				foreach($TblSetting["sortfield"] as $key=>$val){
					if($TblSetting[$key]["forprint"] == 1  && $TblSetting[$key]['type'] != "hide"){
						
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
			
				// если установле настройка это справочник
				if($TblSetting["table"]['is_directory'] == "1"){
					$tblWhere = " WHERE ".(($TblSetting["table"]['StatusField']!="" && $TblSetting["table"]['AllRows']=="0" )?"`".$TblSetting["table"]['StatusField']."`='1'":"`".$TblFieldPrimaryKey."`<>'0'")." AND `".$TblSetting["table"]['directory_root']."`='0'";
					$tblOrder = " ORDER BY ".$TblSetting["table"]["directory_type"]." desc".(($TblSetting["table"]['order']!="")?", ".$TblSetting["table"]['order']:"");
				}
				else{
					$tblWhere = " WHERE ".(($TblSetting["table"]['StatusField']!="" && $TblSetting["table"]['AllRows']=="0" )?"`".$TblSetting["table"]['StatusField']."`='1'":"`".$TblFieldPrimaryKey."`<>'0'");
					$tblOrder = ($TblSetting["table"]['order']!="")?"ORDER BY ".$TblSetting["table"]['order']:"";
				}
				
				$tblWhereField = "";
				reset($TblSetting["sortfield"]);
				while(list($key,$val) = each($TblSetting["sortfield"])){
					if($TblSetting[$key]["visible"] == 1 
					&& isset($_GET[$TblSetting[$key]["name"]])
					){
						$tblWhereField.= " ".(($FirstElem==0)?"":$TblSetting["table"]['WhereType'])." `".$TblSetting[$key]["name"]."` = '".$_GET[$TblSetting[$key]["name"]]."'";
					}
				}
				if($tblWhereField != ""){
					$tblWhere.= " and (".$tblWhereField.")";
				}
					
				//Поиск
				include(GetIncFile($arrSetting,"inc.tables.list.search.php", $TblSetting["table"]["name"]));
				// запрос к таблице
				include(GetIncFile($arrSetting,"inc.tables.list.select.php", $TblSetting["table"]["name"]));
				
				// обработка результата
				if($sql->sql_rows($result)){
					
					$show_row  = "";
					$tmpArrSetField = $TblSetting["sortfield"];
					while($query = $sql->sql_array($result)){
							$show_field = "";
							reset($TblSetting["sortfield"]);
							foreach($TblSetting["sortfield"] as $key=>$val){
								if($TblSetting[$key]["forprint"] == 1  && $TblSetting[$key]['type'] != "hide"){
									include(GetIncFile($arrSetting,"inc.tables.list.row.php", $TblSetting["table"]["name"]));
								}
							}

						$show_row.= GetTpl("row", array("field" => $show_field), $TblDefTplPath);

					}
				}
				
				// итоги в низу таблицы по колонкам с числовым типом
				include(GetIncFile($arrSetting,"inc.tables.list.total.php", $TblSetting["table"]["name"]));

			$show_list = GetTpl("list", array("row_head" => $show_row_head, "row" => $show_row, ), $TblDefTplPath);
			echo "<h1>".(($TblSetting["table"]['description']!="")?$TblSetting["table"]['description']:$TblSetting["table"]['name'])."</h1>";
			echo $show_list;

			// выполняем функцию после загрузкой таблицы
			if(file_exists($allSettings["TblPath"]["function"]."/".$TblSetting['table']['AfterLoadingTable']) && is_file($allSettings["TblPath"]["function"]."/".$TblSetting['table']['AfterLoadingTable'])){
				include($allSettings["TblPath"]["function"]."/".$TblSetting['table']['AfterLoadingTable']);
			}

?>