<?php

    $tblName = trim($TblSetting['table']['name']);

	$arrAction["nametable"]	 = (!isset($arrAction["nametable"]) || $arrAction["nametable"]=="")?"tbllist":$arrAction["nametable"];
	$arrAction["namepage"]	 = (!isset($arrAction["namepage"]) || $arrAction["namepage"]=="")?"Список":$TblSetting["table"]["NameTabTableList"];
	$arrAction["thislink"]	 = (!isset($arrAction["thislink"]) || $arrAction["thislink"]=="")?"?tbl=".$tblName."&pagenum=".$pg."&".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."&event=".$_GET['event']."&panel=2":$arrAction["thislink"];

	$arrEdit["id"]		 = 0;
	$arrEdit["descr"]	 = "";
	
	if(isset($_POST['CancelEdit'])){Redirect($arrAction["thislink"],0);}

    $mainSqlStr = "
        SELECT * 
        FROM ".$sql->prefix_db.$arrAction["nametable"]." 
        WHERE 
            type_row='list'
            AND tbl='".$tblName."' 
            AND id_row=".$_GET[$TblFieldPrimaryKey]." 
    ";

	if(isset($_POST['AddList'])){
		$arrSave["id"]		 = (int)$_POST["id"];
		$arr['type_row']	 = 'list';
		$arr['descr']		 = strtr($_POST["descr"],array("\r\n"=>"<br>"));

		if($arrSave["id"] == 0){
			$arr['tbl']			 = $tblName;
			$arr['type_row']	 = 'list';
			$arr['id_row']		 = $_GET[$TblFieldPrimaryKey];
			$arr['dt']			 = $ut->utGetTime();
			$arr['ch1']			 = "0";
			$arr['ch2']			 = "0";
			$arr['ch3']			 = "0";
			$arr['st']			 = "1";
			
			$ArrFV = $sql->sql_ExpandArr($arr);
			if($sql->sql_insert($arrAction["nametable"],$ArrFV['ListField'],$ArrFV['ListValue'])){
				$ut->utLog(__FILE__ . " - запись сохранена");
			}else{
				$ut->utLog(__FILE__ . " - ошибка сохранения записи");
			}
		}
		else{
			$ArrFV = $sql->sql_ExpandArr($arr);
			if(!$sql->sql_update($arrAction["nametable"],$ArrFV['FieldAndValue'],"type_row = 'list' and tbl='".$tblName."' and id='".$arrSave["id"]."'")){
				$ut->utLog(__FILE__ ." Не возможно сохранить записью. ID='".$arrSave["id"]);
			}
			
		}
		Redirect($arrAction["thislink"],0);
	}

	if(isset($_GET["actionList"])){
		if($_GET["actionList"] == "st" || $_GET["actionList"] == "ch1" || $_GET["actionList"] == "ch2" || $_GET["actionList"] == "ch3"){
			
			$st_field	 = trim($_GET["actionList"]);
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
			$sql->sql_update($arrAction["nametable"],$st_field."='".$st_other."'","type_row = 'list' and tbl='".$tblName."' and id='".$action_id."'");

			$ut->utLog(__FILE__ . " - Изменен статус ".$st_field."=".$st_other." ID: ".$action_id."");
			
			Redirect($arrAction["thislink"],0);
		}
		
		if($_GET["actionList"] == "del"){
			$action_id = (int)$_GET['action_id'];
			$result = $sql->sql_query("
                {$mainSqlStr} 
                and id='".$action_id."'
            ");
			if($sql->sql_rows($result)){
				$query = $sql->sql_array($result);
		    		if($sql->sql_delete($arrAction["nametable"],"tbl='".$tblName."' and id='".$action_id."'")){
				}else{
					$ut->utLog(__FILE__ . " - Ошибка удаления записи");
				}
			}
			Redirect($arrAction["thislink"],0);
		}
		
		if($_GET["actionList"] == "edit"){
			$action_id = (int)$_GET['action_id'];
			$resultCF = $sql->sql_query("
                {$mainSqlStr}
                AND id=".$action_id."
			");
			if($sql->sql_rows($resultCF)){
				$arrEdit = $sql->sql_array($resultCF);
				$arrEdit['descr']		 = strtr($arrEdit["descr"],array("<br>"=>"\r\n"));
			}
		}
	}
?>

<div id='inputArea'>
<?php if(usr_Access("new")) { ?>
<form method="post" enctype="multipart/form-data" action='<?php echo $arrAction["thislink"]; ?>'>
<h1 style='color:blue;'><?php echo $arrAction["namepage"]; ?></h1>

<?php
	echo frmInput(array("type"=>"hidden", "name"=>"id", "value"=>$arrEdit["id"],));
	echo frmTextarea("descr", $arrEdit["descr"], array("id"=>"descr","style"=>"width: 90%; height:50px;",));
	
	//echo "<br>";
	echo "<br>";
	echo frmInput(array("type"=>"submit","name"=>"AddList","id"=>"AddList","value"=>"Сохранить","title"=>"",));
	echo frmInput(array("type"=>"submit","name"=>"CancelEdit","id"=>"CancelEdit","value"=>"Отмена","title"=>"",));
	echo "<br>";
	echo "<br>";
?>

</form>
<?php } ?>

   <table class='tab_list'>
		<thead>
			<tr>
				<th align='center' width='25'>М1</th>
				<th align='center' width='25'>М2</th>
				<th align='center' width='25'>М3</th>
				<th align='center' width='120'>Дата</th>
				<th align='left'>Описание</th>
				<th align='center' width='100'>Удалить</th>
			</tr>
		</thead>
		<tbody>
	<?php
	
		$resultCF = $sql->sql_query("
            {$mainSqlStr} 
            ".((usr_Access("admin"))?"":" and st='1' ")."
		    ORDER BY `dt` desc
		");
		if($sql->sql_rows($resultCF)){
			while($queryCF = $sql->sql_array($resultCF)){

				$val_loc = "style='color: #494c74;text-decoration: none;'";
				$ActionLNK = $arrAction["thislink"]."&action_id=".$queryCF['id']."";
				
				$delLink = "<a href='".$ActionLNK."&actionList=st' ".$val_loc."><img src=".$arrSetting["Path"]["ico"]."/delete.gif></a>";
				if($queryCF['st'] == 0){
					$val_loc = " style='color:#999999;'";
					$delLink = "<a href='".$ActionLNK."&actionList=del' ".$val_loc.">Удалить совсем</a>";
					$delLink.= "<br><a href='".$ActionLNK."&actionList=st' ".$val_loc.">Восстановить</a>";
				}
				
				$ch_var1 = "<a href='".$ActionLNK."&actionList=ch1' ".$val_loc.">".(($queryCF['ch1'] == 0)?"<img src=".$arrSetting["Path"]["ico"]."/unchecked.gif>":"<img src=".$arrSetting["Path"]["ico"]."/checked.gif>")."</a>";
				$ch_var2 = "<a href='".$ActionLNK."&actionList=ch2' ".$val_loc.">".(($queryCF['ch2'] == 0)?"<img src=".$arrSetting["Path"]["ico"]."/unchecked.gif>":"<img src=".$arrSetting["Path"]["ico"]."/checked.gif>")."</a>";
				$ch_var3 = "<a href='".$ActionLNK."&actionList=ch3' ".$val_loc.">".(($queryCF['ch3'] == 0)?"<img src=".$arrSetting["Path"]["ico"]."/unchecked.gif>":"<img src=".$arrSetting["Path"]["ico"]."/checked.gif>")."</a>";
				
				echo "<tr>";
	
				echo "<td align='center'>".$ch_var1."</td>";
				echo "<td align='center'>".$ch_var2."</td>";
				echo "<td align='center'>".$ch_var3."</td>";
				echo "<td align='center'><a href='".$ActionLNK."&actionList=edit' ".$val_loc.">".$ut->utGetDate("d.m.Y H:i:s",$queryCF['dt'])."</a></td>";
				echo "<td align='left' ".$val_loc.">".$queryCF['descr']."</td>";
				
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
