<?php 
	$arrActionUT["TabNum"]		 = "3";
	$arrActionUT["nametable"]	 = (!isset($arrActionUT["nametable"]) || $arrActionUT["nametable"]=="")?"users_tbl":$arrActionUT["nametable"];
	$arrActionUT["namepage"]	 = (!isset($arrActionUT["namepage"]) || $arrActionUT["namepage"]=="")?"Список таблиц пользователя":$TblSetting["table"]["NameTabTableUser"];
	$arrActionUT["thislink"]	 = (!isset($arrActionUT["thislink"]) || $arrActionUT["thislink"]=="")?"?tbl=".$TblName."&pagenum=".$pg."&".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."&event=".$_GET['event']."&panel=".$arrActionUT["TabNum"]:$arrActionUT["thislink"];

	$arrEdit["id"]		 = 0;
	$arrEdit["table_name"]	 = "";
	$arrEdit["user_type"]	 = $qChange["user_type"];
	
	//read=read;new=new;edit=edit;admin=admin;root=root
	$arr_user_type = array("read"=>"read","new"=>"new","edit"=>"edit","admin"=>"admin","root"=>"root",);
	
	if(isset($_POST['CancelEdit'])){Redirect($arrActionUT["thislink"],0);}
	
	if(isset($_POST['AddUserTable'])){
		$arrSave["id"]		 = (int)$_POST["id"];
		$arr['table_name']		 = $_POST["table_name"];
		$arr['user_type']		 = $_POST["user_type"];
		
		if($arrSave["id"] == 0){

			$arr['id_user']		 = $_GET[$TblFieldPrimaryKey];
			$arr['st']			 = "1";
			$ArrFV = $sql->sql_ExpandArr($arr);
			if($sql->sql_insert($arrActionUT["nametable"],$ArrFV['ListField'],$ArrFV['ListValue'])){
				$ut->utLog(__FILE__ . " - запись сохранена");
			}else{
				$ut->utLog(__FILE__ . " - ошибка сохранения записи");
			}
		}
		else{
			$ArrFV = $sql->sql_ExpandArr($arr);
			if(!$sql->sql_update($arrActionUT["nametable"],$ArrFV['FieldAndValue'],"id='".$arrSave["id"]."'")){
				$ut->utLog(__FILE__ ." Не возможно сохранить записью. ID='".$arrSave["id"]);
			}
			
		}
		Redirect($arrActionUT["thislink"],0);
	}

	if(isset($_GET["actionUserTbl"])){
		if($_GET["actionUserTbl"] == "st" ){
			
			$st_field	 = trim($_GET["actionUserTbl"]);
			$action_id	 = (int)$_GET["action_id"];
			$st_other	 = 0;
			$result = $sql->sql_query("select *	from ".$sql->prefix_db.$arrActionUT["nametable"]." WHERE id='".$action_id."'");
			if($sql->sql_rows($result)){
				$query = $sql->sql_array($result);
				if($query[$st_field] == 0) {$st_other = 1;}else{$st_other = 0;}
			}
			$sql->sql_update($arrActionUT["nametable"],$st_field."='".$st_other."'"," id='".$action_id."'");

			$ut->utLog(__FILE__ . " - Изменен статус ".$st_field."=".$st_other." ID: ".$action_id."");
			
			Redirect($arrActionUT["thislink"],0);
		}
		
		if($_GET["actionUserTbl"] == "del"){
			$action_id = (int)$_GET['action_id'];
			$result = $sql->sql_query("select *	from ".$sql->prefix_db.$arrActionUT["nametable"]." where id='".$action_id."'");
			if($sql->sql_rows($result)){
				$query = $sql->sql_array($result);

				if($sql->sql_delete($arrActionUT["nametable"],"id='".$action_id."'")){
				}else{
					$ut->utLog(__FILE__ . " - Ошибка удаления записи");
				}
			}
			Redirect($arrActionUT["thislink"],0);
			
		}
		
		if($_GET["actionUserTbl"] == "autoaddtables"){
			$action_id = (int)$_GET['action_id'];
			$arrAuto['description']		 = "";
			$arrAuto['user_type']	 = $qChange["user_type"];
			$arrAuto['id_user']		 = $_GET[$TblFieldPrimaryKey];
			$arrAuto['st']			 = "1";
			
			if($result1 = $sql->sql_ShowTableFromBD()){
				foreach($result1 as $key => $t_name){
					$tName = str_replace($sql->prefix_db, "", $t_name);
					$arrAuto['table_name'] = $tName;
					
					$resultCF = $sql->sql_query("SELECT * FROM ".$sql->prefix_db.$arrActionUT["nametable"]." 
					WHERE id_user=".$arrAuto['id_user']." 
					AND table_name='".$arrAuto['table_name']."'");
					if(!$sql->sql_rows($resultCF)){

						$ArrFV = $sql->sql_ExpandArr($arrAuto);
						if($sql->sql_insert($arrActionUT["nametable"],$ArrFV['ListField'],$ArrFV['ListValue'])){
							$ut->utLog(__FILE__ . " - запись сохранена. список таблиц");
						}else{
							$ut->utLog(__FILE__ . " - ошибка сохранения записи. список таблиц");
						}
					
					}
				}
			}
			
			Redirect($arrActionUT["thislink"],0);
		}
		
		if($_GET["actionUserTbl"] == "autotablesclear"){
			
			if($sql->sql_delete($arrActionUT["nametable"],"id_user='".$_GET[$TblFieldPrimaryKey]."'")){
				// пишем лог если включена функция авторизации
				if($arrSetting['Access']['UsePassword']){
					$ut->utLog($arrActionUT["nametable"]." Очистка списка таблиц: id_user=".$_GET[$TblFieldPrimaryKey]."; Save array = ".ParseArrForLog($arr).". _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
				}
			}else{
				$ut->utLog(__FILE__ . " - Ошибка очистки списка таблиц");
			}
			
			Redirect($arrActionUT["thislink"],0);
		}
		
		if($_GET["actionUserTbl"] == "autotablesclearuncheck"){
			
			if($sql->sql_delete($arrActionUT["nametable"],"id_user='".$_GET[$TblFieldPrimaryKey]."' AND st='0'")){
				// пишем лог если включена функция авторизации
				if($arrSetting['Access']['UsePassword']){
					$ut->utLog($arrActionUT["nametable"]." Очистка помеченных таблиц: id_user=".$_GET[$TblFieldPrimaryKey]."; Save array = ".ParseArrForLog($arr).". _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
				}
			}else{
				$ut->utLog(__FILE__ . " - Ошибка очистки помеченных таблиц");
			}
			
			Redirect($arrActionUT["thislink"],0);
		}

		if($_GET["actionUserTbl"] == "edit"){
			$action_id = (int)$_GET['action_id'];
			$resultCF = $sql->sql_query("SELECT * FROM ".$sql->prefix_db.$arrActionUT["nametable"]." 
			WHERE id_user=".$_GET[$TblFieldPrimaryKey]." 
			AND id=".$action_id."");
			if($sql->sql_rows($resultCF)){
				$arrEdit = $sql->sql_array($resultCF);
			}
		}
	}
?>

<div id='inputArea'>
<form method="post" action='<?php echo $arrActionUT["thislink"]; ?>'>
<h1 style='color:blue;'><?php echo $arrActionUT["namepage"]; ?></h1>

<?php
	echo frmInput(array("type"=>"hidden", "name"=>"id", "value"=>$arrEdit["id"],));
	
	$arr_user_tables = array();
	$arr_user_tables["none"] = "Выбрать таблицу";
	if($result1 = $sql->sql_ShowTableFromBD()){
		foreach($result1 as $key => $t_name){
			$tName = str_replace($sql->prefix_db, "", $t_name);
			$arr_user_tables[$tName] = $tName.": ";
		}		
	}
	
	echo "<table border='0' cellspacing='1' cellpadding='7'>";
	echo "<tr>";
	echo "<td width='210' align='left' valign='top'>";
		echo "Таблица:<br>";
		echo frmSelect("table_name", $arr_user_tables, $arrEdit["table_name"], array("style"=>"width: 200px;",));
	echo "</td>";
	echo "<td width='210' align='left' valign='top'>";
		echo "Разрешение:<br>";
		echo frmSelect("user_type", $arr_user_type, $arrEdit["user_type"], array("style"=>"width: 200px;",));	
		// echo "Описание:<br>";
		// echo frmTextarea("description", $arrEdit["description"], array("id"=>$TblSetting[$key]['name'],"style"=>"width: 90%; height:50px;",));
	echo "</td>";
	echo "<td align='left' valign='top'>";
	echo "<br>";
	echo frmInput(array("type"=>"submit","name"=>"AddUserTable","id"=>"AddUserTable","value"=>"Сохранить","title"=>"",));
	echo frmInput(array("type"=>"submit","name"=>"CancelEdit","id"=>"CancelEdit","value"=>"Отмена","title"=>"",));

	echo "</td>";
	echo "</tr>";
	echo "</table>";

echo "<p style='text-align:right;'>";
echo "<a href='".$arrActionUT["thislink"]."&action_id=0&actionUserTbl=autoaddtables' title='Добавит не добавленные таблицы!'>Заполнить таблицы</a>";
echo "&nbsp;|&nbsp;";
echo "<a href='".$arrActionUT["thislink"]."&action_id=0&actionUserTbl=autotablesclear' style='color:red;' title='Выполняется без подтверждения!!!'>Очистить список таблиц</a>";
echo "&nbsp;|&nbsp;";
echo "<a href='".$arrActionUT["thislink"]."&action_id=0&actionUserTbl=autotablesclearuncheck' style='color:red;' title='Выполняется без подтверждения!!!'>Очистить помеченые на удаление</a>";
echo "</p>";
?>

</form>

   <table class='tab_list'>
		<thead>
			<tr>
				<th align='center' width='120'>Таблица</th>
				<th align='center' width='120'>Разрешения</th>
				<th align='left'>&nbsp;</th>
				<th align='center' width='100'>Удалить</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$resultCF = $sql->sql_query("SELECT * FROM ".$sql->prefix_db.$arrActionUT["nametable"]." 
		WHERE id_user=".$_GET[$TblFieldPrimaryKey]." ORDER BY `id` desc");
		if($sql->sql_rows($resultCF)){
			while($queryCF = $sql->sql_array($resultCF)){

				$val_loc = "style='color: #494c74;text-decoration: none;'";
				$ActionLNK = $arrActionUT["thislink"]."&action_id=".$queryCF['id']."";
				
				$delLink = "<a href='".$ActionLNK."&actionUserTbl=st' ".$val_loc."><img src=".$arrSetting["Path"]["ico"]."/delete.gif></a>";
				if($queryCF['st'] == 0){
					$val_loc = " style='color:#999999;'";
					$delLink = "<a href='".$ActionLNK."&actionUserTbl=del' ".$val_loc.">Удалить совсем</a>";
					$delLink.= "<br><a href='".$ActionLNK."&actionUserTbl=st' ".$val_loc.">Восстановить</a>";
				}

				echo "<tr>";
				echo "<td align='center'><a href='".$ActionLNK."&actionUserTbl=edit' ".$val_loc.">".$queryCF['table_name']."</a></td>";
				echo "<td align='left' ".$val_loc.">".$queryCF['user_type']."</td>";
				echo "<td align='left' ".$val_loc.">&nbsp;</td>";
				echo "<td align='center'>".$delLink."</td>";

				echo "</tr>";
			}
		}
	?>
		</tbody>
	</table>
</div>

<?php unset($arrActionUT); ?>

