<?php 
// /* созданем папку с названием таблицы */
// $FilesPath = $arrSetting['Path']['tbldata']."/".$TblSetting["table"]['name'];
// if(!is_dir($FilesPath)){if(!mkdir($FilesPath, 0777)){$ut->utLog(__FILE__ . " - Ошибка создания папки ".$FilesPath);}}
// /* созданем папку с файлами */
// $FilesPath = $FilesPath."/files";
// if(!is_dir($FilesPath)){if(!mkdir($FilesPath, 0777)){$ut->utLog(__FILE__ . " - Ошибка создания папки ".$FilesPath);}}

$tblName = trim($TblSetting['table']['name']);

$TblSetting['table']['FileStore'] = trim($TblSetting['table']['FileStore']);
if($TblSetting['table']['FileStore'] == ''){
	$FilesPath = $arrSetting['Path']['tbldata'] . DS . $tblName;
	if(!newDir($FilesPath)){
	    $ut->utLog(__FILE__ . " - Ошибка создания папки ".$FilesPath);
	}
	$FilesPath = $FilesPath . DS . "files";
	if(!newDir($FilesPath)){
	    $ut->utLog(__FILE__ . " - Ошибка создания папки ".$FilesPath);
	}
}
else{
	$FilesPath = $TblSetting['table']['FileStore'];
	if(!is_dir($FilesPath)){
		$FilesPath = $arrSetting['Path']['tbldata'] . DS . $tblName;
		if(!newDir($FilesPath)){
		    $ut->utLog(__FILE__ . " - Ошибка создания папки ".$FilesPath);
		}
		$FilesPath = $FilesPath . DS . "files";
		if(!newDir($FilesPath)){
		    $ut->utLog(__FILE__ . " - Ошибка создания папки ".$FilesPath);
		}
	}
}

$arrActionUT["TabNum"]	    	 = "1";
$arrAction["nametable"]     	 = "tblfiles";
$arrAction["namepage"]      	 = ($TblSetting["table"]["NameTabFileList"] == "")?"Загрузка файлов":$TblSetting["table"]["NameTabFileList"];
$arrAction["FilesPath"]	         = $FilesPath;
$arrAction["thislink"]      	 = (empty($arrAction["thislink"]))?"?tbl=".$tblName."&pagenum=".$pg."&".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."&event=".$_GET['event']."&panel=".$arrActionUT["TabNum"]:$arrAction["thislink"];
$arrAction["ShowGoFolderLink"]   = (empty($arrAction["ShowGoFolderLink"]))?"1":$arrAction["ShowGoFolderLink"];
$arrAction["PanelNum"]      	 = (empty($arrAction["PanelNum"]))?"1":$arrAction["PanelNum"];

if(isset($_POST['AddFile'])){
	if($_FILES){
		$dir_name = $arrAction["FilesPath"];
		newDir($dir_name);
		$txt = new class_txt();
		foreach ($_FILES as $key => $value) {
			for($i = 0; $i < count($value['name']);$i++){
				$string_fname	 = $txt->txtClearStr($txt->txtTranslit(strtolower($value['name'][$i])));
				$FArr			 = $flc->fFileName($dir_name,$string_fname);
				$string_fname	 = $FArr['name'];
				
				if (@is_uploaded_file($value['tmp_name'][$i])){
					if(@move_uploaded_file($value['tmp_name'][$i],$dir_name."/".$string_fname)){
						//$tblName
						$arr['tbl']			 = $tblName;
						$arr['type_row']	 = 'list';
						$arr['id_row']		 = $_GET[$TblFieldPrimaryKey];
						$arr['dt']			 = $ut->utGetTime();
						$arr['f_name']		 = $string_fname;
						$arr['f_path']		 = $arrAction["FilesPath"];
						$arr['f_descr']		 = $value['name'][$i];
						$arr['st']			 = "1";
						$ArrFV = $sql->sql_ExpandArr($arr);
						if($sql->sql_insert($arrAction["nametable"],$ArrFV['ListField'],$ArrFV['ListValue'])){
							$ut->utLog(__FILE__ . " - Успешная загрузка файла. < ".$dir_name."/".$string_fname." >. file_size=".$value["size"][$i].".");
						}else{
							$ut->utLog(__FILE__ . " - Файл загружен, но не отражен в БД. < ".$dir_name."/".$string_fname." >. file_size=".$value["size"][$i].".");
						}
					}
					else{
						$ut->utLog(__FILE__ . " - Ошибка загрузки файла. move_uploaded_file(".$value['tmp_name'][$i].",".$dir_name."/".$string_fname.").");
					}
				}
				else{
					$ut->utLog(__FILE__ . " - Ошибка загрузки файла. is_uploaded_file(".$value['tmp_name'][$i].").");
				}				
			}
		}
	}
	Redirect($arrAction["thislink"],0);
}

$mainSqlStr = "
    SELECT * 
    FROM ".$sql->prefix_db.$arrAction["nametable"]." 
    WHERE 
        type_row='list'
        AND tbl='".$tblName."' 
        AND id_row=".$_GET[$TblFieldPrimaryKey]." 
";

if(isset($_GET["actionFiles"])){
	if($_GET["actionFiles"] == "st"){
		
		$st_field	 = trim($_GET["actionFiles"]);
		$action_id	 = (int)$_GET["action_id"];
		$st_other	 = 0;
		$result = $sql->sql_query("
            {$mainSqlStr} 
            and id='".$action_id."'
        ");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			if($query[$st_field] == 0) {$st_other = 1;}else{$st_other = 0;}
		}
		$sql->sql_update($arrAction["nametable"],$st_field."='".$st_other."'","tbl='".$tblName."' and id='".$action_id."'");

		$ut->utLog(__FILE__ . " - Изменен статус ".$st_field."=".$st_other." ID: ".$query['id']."");
		
		Redirect($arrAction["thislink"],0);
	}
	if($_GET["actionFiles"] == "del"){
		$action_id = (int)$_GET['action_id'];
		$result = $sql->sql_query("
            {$mainSqlStr} 
            and id='".$action_id."'
        ");
		if($sql->sql_rows($result)){
			$query = $sql->sql_array($result);
			if($sql->sql_delete($arrAction["nametable"],"tbl='".$tblName."' and id='".$action_id."'")){
				$flc->fDelFile($arrAction["FilesPath"] . DS . $query['f_name']);
				$ut->utLog(__FILE__ . " - Файл удален FILE: ".$query['f_descr']."; ID_FILE: ".$query['f_descr']."");
			}else{
				$ut->utLog(__FILE__ . " - Ошибка удаления файла FILE: ".$query['f_descr']."; ID_FILE: ".$query['f_descr']."");
			}
		}
		Redirect($arrAction["thislink"],0);
	}
}
?>

<div id='inputArea'>
<?php if(usr_Access("new")) { ?>
	<form method="post" enctype="multipart/form-data" action='<?php echo $arrAction["thislink"]; ?>'>
	<h1 style='color:blue;'><?php echo $arrAction["namepage"]; ?></h1>
	<p><input type="file" multiple="multiple" name="Add_File[]" style="width:400px;" /> <input type="submit" value="Загрузить" name="AddFile" ></p>
	<?php if($arrAction["ShowGoFolderLink"]!="0") { ?>
		<?php if(usr_Access("admin")) { ?>
			<p><a href="files.php?d=<?php echo $arrAction["FilesPath"] ; ?>" target="_blank">Перейти к папке</a></p>
		<?php } ?>
	<?php } ?>
	</form>
<?php } ?>
   <table class='tab_list'>
		<thead>
			<tr>
				<th align='center' width='120'>Дата</th>
				<th align='center' width='100'>&nbsp;</th>
				<th align='left' width='550'>Файлы</th>
				<th align='center' width='100'>Удалить</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$arrImg = array(".jpg",".png",".gif",".bmp",);

		$resultCF = $sql->sql_query("
            {$mainSqlStr}
            ".((usr_Access("admin"))?"":" and st='1' ")." 
            ORDER BY `dt` DESC
        ");
		if($sql->sql_rows($resultCF)){
			while($queryCF = $sql->sql_array($resultCF)){
				$val_loc	 = "";
				$ActionLNK	 = $arrAction["thislink"]."&action_id=".$queryCF['id']."";
				$f_info = $flc->fFileName($arrAction["FilesPath"],$queryCF['f_name']);
				$delLink = "<a href='".$ActionLNK."&actionFiles=st'><img src=".$arrSetting["Path"]["ico"]."/delete.gif></a>";
				if($queryCF['st'] == 0){
					$val_loc.= " style='color:#999999;'";
					$delLink = "<a href='".$ActionLNK."&actionFiles=del' ".$val_loc.">Удалить совсем</a>";
					$delLink.= "<br><a href='".$ActionLNK."&actionFiles=st' ".$val_loc.">Восстановить</a>";
				}
				$sz = $flc->fGetFileSize($arrAction["FilesPath"] . DS . $queryCF['f_name'],3,"kb");
				echo "<tr>";
				
				echo "<td align='center' ".$val_loc.">".$ut->utGetDate("d.m.Y H:i:s",$queryCF['dt'])."</td>";
				if(in_array($f_info["name_b"],$arrImg)){
					$img_val = "<div style=' width: 100px; height: 100px; overflow: hidden; text-align: center; margin: 0; padding: 0; '>
					<a href='".$queryCF['f_path'] . DS . $queryCF['f_name']."' target='_blank' title='".$queryCF['f_descr']."'><img src='download.php?fl=".$queryCF['id']."'  border='0' style='height:100px;'></a>
					</div>";
					echo "<td align='center' ".$val_loc.">".$img_val."</td>";
				}
				else{
					echo "<td align='center' ".$val_loc.">".$f_info["name_b"]."</td>";
				}
				echo "<td align='left'><a href='download.php?fl=".$queryCF['id']."' " . $val_loc . " >".$queryCF['f_descr']."</a> <span " . $val_loc . ">(".$sz['size']." ".$sz['type'].")</span></td>";
				if(usr_Access("edit")){
					echo "<td align='center'>".$delLink."</td>";
				}
				else{
					echo "<td align='center'></td>";
				}
				echo "</tr>";
			}
		}
	?>
		</tbody>
	</table>
</div>
<?php unset($arrAction); ?>