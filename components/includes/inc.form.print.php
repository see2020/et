<h1><?php echo ($TblSetting["table"]['description']!="")?$TblSetting["table"]['description']:$TblSetting["table"]['name']; ?></h1>
<hr style='border-bottom: 0;border-left: 0;border-right: 0;border-top: 2px solid #000000;'>
<?php
	asort($TblSetting['sortfieldform']);
	reset($TblSetting['sortfieldform']);
	while(list($key,$val) = each($TblSetting['sortfieldform'])){
		if($TblSetting[$key]["forprint"] == 1){
			echo "<b>".frmGetName($TblSetting,$key)."</b>: ";
			if($TblSetting[$key]['type']=='date'){
				$ShowRow = $ut->utGetDate($TblSetting[$key]['dateformat'],$qChange[$TblSetting[$key]['name']]);
				echo $ShowRow."<br>";
			}
			elseif($TblSetting[$key]['type']=='directory_id'){
				echo spr_get_element($sql, array("table_name"=>$TblSetting[$key]['directory_table'],"id"=>$TblSetting[$key]['directory_field_id'],"field_name_show"=>$TblSetting[$key]['directory_field_name'],), $qChange[$TblSetting[$key]['name']]);
			}
			elseif($TblSetting[$key]['type']=='selectarea'){
				if($TblSetting[$key]['directory_table'] != ""){
					$tmp_arr_td =spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key);
				}
				else{
					$tmp_arr_td = GetArrTypeData($TblSetting[$key]['type_data']);
				}
				echo $tmp_arr_td[$qChange[$TblSetting[$key]['name']]];
				unset($tmp_arr_td);
			}
			elseif($TblSetting[$key]['type']=='radiobutton'){
				if($TblSetting[$key]['directory_table'] != ""){
					$tmp_arr_td = spr_GetArrTypeData($sql, $arrSetting, $TblSetting, $key);
				}
				else{
					$tmp_arr_td = GetArrTypeData($TblSetting[$key]['type_data']);
				}
				echo $tmp_arr_td[$qChange[$TblSetting[$key]['name']]];
				unset($tmp_arr_td);
			}
			else{
				$ShowRow = strtr($qChange[$TblSetting[$key]['name']],array("\r"=>"<br>"));
				echo $ShowRow."<br>";
			}
			echo "<br>";

		}
	}
?>

<hr style='border-bottom: 0;border-left: 0;border-right: 0;border-top: 1px solid #000000;'>
<!-- Файлы -->
<?php 
	if($TblSetting["table"]['UseTableFileList'] == "1") { 

		$arrAction["nametable"]	 = ($arrAction["nametable"]=="")?"tblfiles":$arrAction["nametable"];
		$arrAction["namepage"]	 = ($arrAction["namepage"]=="")?"Загрузка файлов":$TblSetting["table"]["NameTabFileList"];
		$arrAction["FilesPath"]	 = ($arrAction["FilesPath"]=="")?$arrSetting['Path']['tbldata']:$arrSetting['Path']['tbldata']."/".$TblSetting["table"]['name']."/files";
		$arrAction["thislink"]	 = ($arrAction["thislink"]=="")?"?tbl=".$TblName."&pagenum=".$pg."&".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."&event=edit&panel=1":$arrAction["thislink"];
		$arrAction["ShowGoFolderLink"] = ($arrAction["ShowGoFolderLink"]=="")?"1":$arrAction["ShowGoFolderLink"];
		$arrAction["PanelNum"]	 = ($arrAction["PanelNum"]=="")?"1":$arrAction["PanelNum"];

		echo "<h2>".$arrAction["namepage"]."</h2>";

		echo "
		<table class='tab_print'>
			<thead>
				<tr>
					<th align='center' width='150'><strong>Дата</strong></th>
					<th align='left' width='500'><strong>Файлы</strong></th>
				</tr>
			</thead>
			<tbody>
		";
				$resultCF = $sql->sql_query("select * from ".$sql->prefix_db.$arrAction["nametable"]." where tbl='".trim($TblSetting["table"]['name'])."' and id_row=".$_GET[$TblFieldPrimaryKey]." and st='1' order by `dt` desc");
				if($sql->sql_rows($resultCF)){
					while($queryCF = $sql->sql_array($resultCF)){
					
						echo "<tr>";
						echo "<td align='center'>".$ut->utGetDate("d.m.Y H:i:s",$queryCF['dt'])."</td>";
						echo "<td align='left'>".$queryCF['f_descr']."</td>";
						echo "</tr>";
					}
				}
		echo "
			</tbody>
		</table>
		";
	} 
	unset($arrAction);
 ?>

<hr style='border-bottom: 0;border-left: 0;border-right: 0;border-top: 1px solid #000000;'>
<!-- Список -->
<?php 
	if($TblSetting["table"]['UseTableList'] == "1") { 

		$arrAction["nametable"]	 = ($arrAction["nametable"]=="")?"tbllist":$arrAction["nametable"];
		$arrAction["namepage"]	 = ($arrAction["namepage"]=="")?"Список":$TblSetting["table"]["NameTabTableList"];
		$arrAction["thislink"]	 = ($arrAction["thislink"]=="")?"?tbl=".$TblName."&pagenum=".$pg."&".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."&event=edit&panel=2":$arrAction["thislink"];
		echo "<h2>".$arrAction["namepage"]."</h2>";
		echo "
	   <table class='tab_print'>
			<thead>
				<tr>
					<th align='center' width='25'><strong>М1</strong></th>
					<th align='center' width='25'><strong>М2</strong></th>
					<th align='center' width='25'><strong>М3</strong></th>
					<th align='center' width='150'><strong>Дата</strong></th>
					<th align='left' width='400'><strong>Описание</strong></th>
				</tr>
			</thead>
			<tbody>
		";
		$resultCF = $sql->sql_query("SELECT * FROM ".$sql->prefix_db.$arrAction["nametable"]." WHERE tbl='".trim($TblName)."' AND id_row=".$_GET[$TblFieldPrimaryKey]." and st='1' ORDER BY `dt` desc");
		if($sql->sql_rows($resultCF)){
			while($queryCF = $sql->sql_array($resultCF)){
				$ch_var1 = "".(($queryCF['ch1'] == 0)?"<img src=".$arrSetting["Path"]["ico"]."/unchecked.gif>":"<img src=".$arrSetting["Path"]["ico"]."/checked.gif>")."";
				$ch_var2 = "".(($queryCF['ch2'] == 0)?"<img src=".$arrSetting["Path"]["ico"]."/unchecked.gif>":"<img src=".$arrSetting["Path"]["ico"]."/checked.gif>")."";
				$ch_var3 = "".(($queryCF['ch3'] == 0)?"<img src=".$arrSetting["Path"]["ico"]."/unchecked.gif>":"<img src=".$arrSetting["Path"]["ico"]."/checked.gif>")."";
				
				echo "<tr>";
	
				echo "<td align='center'>".$ch_var1."</td>";
				echo "<td align='center'>".$ch_var2."</td>";
				echo "<td align='center'>".$ch_var3."</td>";
				echo "<td align='center'>".$ut->utGetDate("d.m.Y H:i:s",$queryCF['dt'])."</td>";
				echo "<td align='left'>".$queryCF['descr']."</td>";


				echo "</tr>";
			}
		}
		echo "
				</tbody>
			</table>
		";
		
	} 
	unset($arrAction);
?>	
 
<hr style='border-bottom: 0;border-left: 0;border-right: 0;border-top: 1px solid #000000;'>
<!-- таблицы пользователя -->
<?php 
if($TblSetting["table"]['UseTableUser'] == "1") { 

	$arrAction["nametable"]	 = ($arrAction["nametable"]=="")?"users_tbl":$arrAction["nametable"];
	$arrAction["namepage"]	 = ($arrAction["namepage"]=="")?"Список таблиц пользователя":$TblSetting["table"]["NameTabTableUser"];
	$arrAction["thislink"]	 = ($arrAction["thislink"]=="")?"?tbl=".$TblName."&pagenum=".$pg."&".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."&event=edit&panel=3":$arrAction["thislink"];
	echo "<h2>".$arrAction["namepage"]."</h2>";
	
		echo "
	   <table class='tab_print'>
			<thead>
				<tr>
				<th align='center' width='120'><strong>Таблица</strong></th>
				<th align='center' width='120'><strong>Разрешения</strong></th>
				<th align='left' width='410'><strong>Описание</strong></th>
				</tr>
			</thead>
			<tbody>
		";
		$resultCF = $sql->sql_query("SELECT * FROM ".$sql->prefix_db.$arrAction["nametable"]." WHERE id_user=".$_GET[$TblFieldPrimaryKey]." and st='1' ORDER BY `id` desc");
		if($sql->sql_rows($resultCF)){
			while($queryCF = $sql->sql_array($resultCF)){

				echo "<tr>";
				echo "<td align='center'>".$queryCF['table_name']."</td>";
				echo "<td align='left'>".$queryCF['user_type']."</td>";
				echo "<td align='left'>".$queryCF['description']."</td>";
				echo "</tr>";
			}
		}
		echo "
				</tbody>
			</table>
		";
	
	unset($arrAction);
} 
 ?>	
 