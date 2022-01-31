<?php
/**
 * dump.php - копия таблиц
 */
	session_start();
	
    include("cfg.php");
    include(ET_PATH_RELATIVE . DS . "config.php");

	if(usr_Access("admin")){$u_access = true;}
	else{
		echo Message("Недостаточно прав на изменение этого раздела", "error");
		$u_access = false;
		exit;
	}

	echo 'В данный момент функция не доступна!';

//	$sql->sql_connect();
//
//	$PageLink = "dump.php?tbl1=0";
//
//	// тема оформления
//	$TblList = array();
//	$TblDefTplPath = $arrSetting['Path']['tpl'] . DS . $arrSetting['Table']['DefaultTpl'];
//	$TblSetting["table"]['name'] = "";
//	$TblSetting["table"]['ico'] = $arrSetting['Path']['ico'] . DS . "mysql_dump.gif";
//	$TblSetting["table"]['description'] = "Дамп таблиц";
//	if(file_exists($TblDefTplPath . DS . "top.php")){include($TblDefTplPath . DS . "top.php");}

?>
<!--<div class='panel_btn'>-->
<!--	<table border='0' cellspacing='0' cellpadding='2'>-->
<!--	  <tr>-->
<!--		<td width='20' align='center' valign='middle'>&nbsp;</td>-->
<!--		<td align='center' valign='middle'><a href='--><?php //echo $PageLink; ?><!--'>Снять дамп</a></td>-->
<!---->
<!--		<td width='20' align='center' valign='middle'>&nbsp;</td>-->
<!--		<td align='center' valign='middle'><a href='--><?php //echo $PageLink; ?><!--&queryrun=1'>Выполнить запрос</a></td>-->
<!--	  </tr>-->
<!--	</table>-->
<!--</div>-->
<?php
/*
		// настройки получения дампа
		$dmpDropTableIfNotExists	 = $arrSetting['Dump']['dmpDropTableIfNotExists']; // 1/0
		$dmpCreateTableIfNotExists	 = $arrSetting['Dump']['dmpCreateTableIfNotExists']; // 1/0
		$dmpStructureCopy			 = $arrSetting['Dump']['dmpStructureCopy']; // 1/0
		$dmpDataCopy				 = $arrSetting['Dump']['dmpDataCopy']; // 1/0
		$dmpCountTrans				 = $arrSetting['Dump']['dmpCountTrans']; // 100
		$dmpInsertType				 = $arrSetting['Dump']['dmpInsertType']; // insert(default)/update/replace 
		
		//путь к дампу на сервере
		$PathDump					 = $arrSetting['Path']['dump'];	
		//файл дампа
		$PathDumpFile =  $PathDump . DS . "dump_".$ut->utGetDate("Ymd_His").".sql";
		
		if(isset($_GET['action'])){
			if($_GET['action']=="dump_del"){
				if(file_exists($PathDump . DS . $_GET['f_dump'])){
					$flc->fDelFile($PathDump . DS . $_GET['f_dump']);
					$ut->utLog(__FILE__ . " - dump удален: ".$PathDump . DS . $_GET['f_dump']);
					Redirect($PageLink,0);
				}else{
					echo Message("Файл не найден. ".$PathDump . DS . $_GET['f_dump'],"alert");
				}
			}
		}
		
		echo "<form name='form2' method='post' action='' >";
		echo "<table width='100%' border='0'><tr valign='top'>";
		
		if(isset($_GET['queryrun'])){
		
			echo "<td align='left'>";
			echo "Текст запроса<br>";
			echo "<textarea name='query_text' id='query_text' style='width: 95%;height:250px;'></textarea><br>";
			echo "<input type='submit' name='query_load' id='query_load' value='Выполнить' style='width: 80px;' title='Выполнить'> &nbsp; ";
			
 			//делаем обработку и загрузку дампа
			if(isset($_POST['query_load'])){
				$_POST['query_text'] = strtr($_POST['query_text'],array("\'"=>"'","\r"=>"","\n"=>""));
				echo "<br>";
				echo '<b>Обработка дампа из текстового поля</b><br>';
				$sql_parser = new SQLParser;
				$arr2 = $sql_parser->getQueries($_POST['query_text']);
				reset($arr2);
				$valCounter = 0;
				foreach($arr2 as $key=>$val){
					echo Message("row: ".$valCounter.". ".$val);
					$valCounter++;
					$result2 = $sql->sql_query($val);
					if($sql->sql_err){
						echo Message("<b>MYSQL ERROR:</b><br> ".$sql->sql_err,"error");
					}
				}
				echo '<b>Обработка завершена</b><br>';
				echo 'Обработано строк: '.$valCounter.'<br>';
				unset($_POST['query_load']);
			}
			echo "</td>";
		}
		else{
			echo "<td align='left' width='180'>";
			echo "<select name='table_list[]' size='15'  multiple='multiple' style='width: 170px;'>";
			$arr_tbl = array();
			$result1 = $sql->sql_query("SHOW TABLES FROM `".$arrSetting['MySQL']['database']."`");
			if ($sql->sql_err){$ut->utLog(__FILE__ ." ошибка получения списка таблиц");}
			else{
				while($table = $sql->sql_array($result1)){
					echo "<option value='".trim($table['Tables_in_'.$arrSetting['MySQL']['database']])."'>".trim($table['Tables_in_'.$arrSetting['MySQL']['database']])."</option>";
				}
			}
			echo "</select>";
			echo "</td>";
			echo "<td align='left'>";
			echo "<input type='checkbox' name='dmpStructureCopy' id='dmpStructureCopy' ".(($dmpStructureCopy == 1)?"checked='checked'":"")." /> Копия структуры<br><br>";
			echo "<input type='checkbox' name='dmpDataCopy' id='dmpDataCopy' ".(($dmpDataCopy == 1)?"checked='checked'":"")." /> Копия данных<br><br>";
			echo "<input type='checkbox' name='dmpDropTableIfNotExists' id='dmpDropTableIfNotExists' ".(($dmpDropTableIfNotExists == 1)?"checked='checked'":"")." />Add \"DROP TABLE IF EXISTS\"<br><br>";
			echo "<input type='checkbox' name='dmpCreateTableIfNotExists' id='dmpCreateTableIfNotExists' ".(($dmpCreateTableIfNotExists == 1)?"checked='checked'":"")." />Add \"CREATE TABLE IF NOT EXISTS\"<br><br>";
			echo "<input type='submit' name='SaveCopy' value='Copy' style='width: 80px;' title='Полный дамп'> &nbsp; ";
			echo "<br><br><br>";
			echo "<input type='submit' name='TruncTable' value='TRUNCATE TABLE' title='Очищает выбранные таблицы.' style='width: 80px; color:red;'><br>";
			echo "<br>";
			
			if(isset($_POST['SaveCopy'])){
				$dmpDropTableIfNotExists	 = (isset($_POST['dmpDropTableIfNotExists']))?1:0;
				$dmpCreateTableIfNotExists	 = (isset($_POST['dmpCreateTableIfNotExists']))?1:0;
				$dmpStructureCopy			 = (isset($_POST['dmpStructureCopy']))?1:0;
				$dmpDataCopy				 = (isset($_POST['dmpDataCopy']))?1:0;

				echo "<strong>START</strong> ".$ut->utGetDate("Y.m.d H:i:s")."<hr>";
				echo "<strong>DB</strong> ".$arrSetting['MySQL']['database']."<br>";
				echo "<br>";
				
				//папка для сохранения дампа из нее же берется для востановления
				if(!newDir($PathDump)){
					$ut->utLog(__FILE__ . " - Ошибка создания папки ".$PathDump);
                }

				// получаем список колонок таблицы
				function dddGetFieldList($sql,$table){
					$fld = '';
					$arrColumn = $sql->sql_GetFieldFromTable($table);
					if(is_array($arrColumn)){
						reset($arrColumn);
						foreach($arrColumn as $val){
							$fld.=",`".$val."`";
						}
					}
					return(trim(substr($fld,1)));
				}

				// структура
				function dddGetTableStructure($sql,$table,$dmpDropTableIfNotExists,$dmpCreateTableIfNotExists){
					$FullDump = "";
					
					$content_table_creat = "";
					$content_table_creat.= "-- Structure for table: `".$table."` \r\n";
					$content_table_creat.= ($dmpDropTableIfNotExists == 1)?"DROP TABLE IF EXISTS `".$table."`;\r\n":"";

					$sql->sql_query("OPTIMIZE TABLE `".$table."`");

					$row_by_table = $sql->sql_f_row($sql->sql_query("SHOW CREATE TABLE `".$table."`"));
					$row_by_table[1] = str_replace("CREATE TABLE", (($dmpCreateTableIfNotExists == 1)?"CREATE TABLE IF NOT EXISTS":"CREATE TABLE"), $row_by_table[1]);
					$content_table_creat.= $row_by_table[1].";"."\r\n";
					$content_table_creat.= "\r\r\r\n";
					$FullDump.= $content_table_creat;
					
					return($FullDump);
				}
				
				// данные
				function dddGetTableDate($sql,$table,$fields,$dmpInsertType,$dmpCountTrans){
					$FullDump = "";
					
					$FullDump.= "-- Data for table: `".$table."` \r\n";
					//получаем поля каждой таблицы и пишем в файл по строчно
					$result = $sql->sql_query("select ".$fields." from `".$table."`");
					$rows = $sql->sql_rows($result);
					$row = 0;
					if($rows){
						// названия колонок таблицы в массив
						$arr_fields = explode(",", $fields);
						// получаем имя колонки Primary Key;
						$p_key	 = $sql->sql_GetPrimaryKey($table);
						
						$CountTrans = 0;
						while($query = $sql->sql_array($result)){
							
							//считаем сколько записей в таблице
							$row++;
							if($dmpInsertType != "update"){
								$data_field = '';
								// получаем данные по колонкам
								reset($arr_fields);
								//получаем данные одной записи таблицы в строку
								foreach($arr_fields as $val){
									$data_field.=",'".$query[trim(strtr($val, array("`"=>"")))]."'";
								}
								$data_field = strtr(substr($data_field,1), array("\r"=>"","\n"=>""));
								
								//создаем одну запись таблицы для дампа
								if($dmpCountTrans == 0){$content = (($dmpInsertType=="replace")?"REPLACE":"INSERT")." INTO `".$table."` (".$fields.") VALUES(".$data_field.");"."\r\n";}
								else{
									if($CountTrans == 0){$content = (($dmpInsertType=="replace")?"REPLACE":"INSERT")." INTO `".$table."` (".$fields.") VALUES";}else{$content = "";}
									$CountTrans++;
									if($CountTrans < $dmpCountTrans){$content .= "(".$data_field.")".(($row == $rows)?";":",").""."\r\n";}
									else{
										$content .= "(".$data_field.");"."\r\n";
										$CountTrans = 0;
									}								
								}
							}
							else{
								$p_key_val	 = "";
								$data_field	 = "";
								// получаем данные по колонкам
								reset($arr_fields);
								//получаем данные одной записи таблицы в строку
								foreach($arr_fields as $val){
									$data_field.=",".$val."='".$query[trim(strtr($val, array("`"=>"")))]."'";
									//получаем значение PrimaryKey-я
									if(trim(strtr($val, array("`"=>""))) == $p_key){
										$p_key_val = $query[trim(strtr($val, array("`"=>"")))];
									}
								}
								$data_field = strtr(substr($data_field,1), array("\r"=>"","\n"=>""));
								$content = "UPDATE `".$table."` SET ".$data_field." WHERE `".$p_key."`='".$p_key_val."';"."\r\n";
							}

							//и сразу дописываем ее в файл
							$FullDump.= $content;
						}
					}
					$content = "\r\r\r\n";
					$FullDump.=$content;
					
					$arr["FullDump"] = $FullDump;
					$arr["row"]		 = $row;
					return($arr);
				}
				
				//шапка в файле дампа
				$content_descr="";
				$content_descr.="-- ----------------------------------------------------------\r\n";
				$content_descr.="-- DB: ".$arrSetting['MySQL']['database']."\r\n";
				$content_descr.="-- PATH: ".$PathDumpFile."\r\n";
				$content_descr.="-- Date: ".$ut->utGetDate("Y-m-d H:i:s")."\r\n";
				$content_descr.="-- ----------------------------------------------------------\r\r\r\r\n";
					
				$FullDump = "";
				//записываем шапку
				$FullDump.=$content_descr;
				//получаем список таблиц в базе с их полями 
				// и на основании резултатов делаем дамп
				$tc = 0;

				// дамп выбранных таблиц
				if(count($_POST['table_list']) > 0){
					for($num_t=0;$num_t<count($_POST['table_list']);$num_t++){
						$t_name = trim($_POST['table_list'][$num_t]);
						
						// получаем список колонок таблицы
						$fields = dddGetFieldList($sql,$t_name);
						
						//ДЕЛАЕМ ДАМП ДАННЫХ
						$table = $t_name;
						
						//дамп структуры таблицы
						$content_table_creat = "";
						if($dmpStructureCopy == 1){
							$FullDump.= dddGetTableStructure($sql,$table,$dmpDropTableIfNotExists,$dmpCreateTableIfNotExists);
						}
						
						//сначала записываем создание таблицы
						if($dmpDataCopy == 1){
							$arrFullDump = dddGetTableDate($sql,$table,$fields,$dmpInsertType,$dmpCountTrans);
							$FullDump.= $arrFullDump["FullDump"];
							$row = $arrFullDump["row"];
						}
						
						//считаем сколько таблиц
						$tc++;
						echo "<b>Table:</b>&nbsp;".$table."&nbsp;&nbsp;&nbsp;<b>[rows:</b>&nbsp;".$row."<b>]</b><br>";
					}
				}
				else{
					if($result1 = $sql->sql_ShowTableFromBD()){
						foreach($result1 as $t_key=>$t_name){
							
							// получаем список колонок таблицы
							$fields = dddGetFieldList($sql,$t_name);
							
							//ДЕЛАЕМ ДАМП ДАННЫХ
							$table = trim($t_name);		
							//дамп структуры таблицы
							$content_table_creat = "";
							if($dmpStructureCopy == 1){
								$FullDump.= dddGetTableStructure($sql,$table,$dmpDropTableIfNotExists,$dmpCreateTableIfNotExists);
							}
							
							//сначала записываем создание таблицы
							if($dmpDataCopy == 1){
								$arrFullDump = dddGetTableDate($sql,$table,$fields,$dmpInsertType,$dmpCountTrans);
								$FullDump.= $arrFullDump["FullDump"];
								$row = $arrFullDump["row"];
							}
							//считаем сколько таблиц
							$tc++;
							echo "<b>Table:</b>&nbsp;".$table."&nbsp;&nbsp;&nbsp;<b>[rows:</b>&nbsp;".$row."<b>]</b><br>";
						} 
					}
					else{echo Message("ошибка получения списка таблиц для получения дампа","error");$ut->utLog(__FILE__ . " - ошибка получения списка таблиц для получения дампа");}
				}
				
				// записываем дамп в файл
				$UseZlibFlag = $flc->fFileCompression("",$PathDumpFile,$FullDump,"zlib");
				if($UseZlibFlag){$ut->utLog(__FILE__ ." - Файл загружен с сипользованием сжатия zlib. Имя файла: ".$PathDumpFile.".gz");}
				else{
					$ut->utLog(__FILE__ ." - ошибка сипользования сжатия zlib. Имя файла: ".$PathDumpFile." файл создан без сжатия");
					$flc->fRewrite($PathDumpFile,$FullDump);
				}
			
				echo "<br>";
				echo "<strong>table count</strong> ".$tc."<br>";
				echo "<strong>DUMP FILE</strong> <a href='".(($UseZlibFlag)?$PathDumpFile.".gz":$PathDumpFile)."'>".(($UseZlibFlag)?$PathDumpFile.".gz":$PathDumpFile)."</a><br>";
				echo "<hr>";
				echo "<strong>STOP</strong> ".$ut->utGetDate("Y.m.d H:i:s")."<br>";		
			}
			
			/////////////////////////////////////////////////////////////////////////////////////////
			// удаление данных из выбранных таблиц	
			if(isset($_POST['TruncTable'])){
				$table = "";
				for($num_t=0;$num_t<count($_POST['table_list']);$num_t++){$table.=",".trim($_POST['table_list'][$num_t]);}
				$table = substr($table,1);
				if($table!=""){
					$text = Message("Эта функция удалит все записи в выбранных таблицах!","Alert")."<br><b>".str_replace(",","<br>",$table)."</b>";
					$ActionString = $PageLink."&tbl_list=".$table;
					$arrSettingForm['bNameYes']	 = "TruncTableYes";
					$arrSettingForm['bNameNo']	 = "TruncTableNo";
					echo WindowYesNo($text,$ActionString,$arrSettingForm);					
				}else{echo Message("Таблицы не выбраны","Error");}
			}
			if(isset($_POST['TruncTableYes'])){
				$arr = explode(",", $_GET['tbl_list']);reset($arr);
				foreach($arr as $key=>$val){
					$val = trim($val);
					$result = $sql->sql_query("TRUNCATE TABLE `".$val."`");
					echo Message("TRUNCATE TABLE `".$val."` complete!</a>");
				}				
				echo Message("Данные из выбранных таблиц удалены!<br><a href='".$PageLink."'>Обновить страницу</a>","alert");
			}
			elseif(isset($_POST['TruncTableNo'])){Redirect($PageLink);}	
			echo "</td>";
		}
		
		// список файлов дампов
		echo "<td align='left' width='300'>";
		
			//Получаем список файлов дампов на сервере
			$fd_list = '';
			$fd_list.= "<table class='tab_list'>";
			$fd_list.= "<thead>";
			$fd_list.= "<tr>";
			$fd_list.= "<th><b>name</b></th>";
			$fd_list.= "<th width='80' align='center'><b>size</b></th>";
			$fd_list.= "<th width='25' align='center'>&nbsp;</th>";
			$fd_list.= "<th width='25' align='center'>&nbsp;</th>";
			$fd_list.= "</tr>";
			$fd_list.= "</thead>";
			$fd_list.= "<tbody>";
			
			//выводим файлы
			$flc->fNotShowFile = 0;
			$flc->fListFiles($PathDump,$PageLink,true);
			$flc->fNotShowFile = 1;
			if(isset($flc->fListFiles[0]['file'])){
				foreach($flc->fListFiles as $key=>$val){
					$fd_list.= "<tr>";
					$fd_list.= "<td><a href='".$PageLink."&dump_open=".$val['file']."#' title='Open: ".$val['file']."' style='color:green'>".$val['file']."</a></td>";
					$fd_list.= "<td>".(($val['size']>1024)?round($val['size']/1024,3)."&nbsp;Mb":$val['size']."&nbsp;Kb")."</td>";
					$fd_list.= "<td>";
						if(file_exists(ET_PATH_RELATIVE . DS . "files.php")){
							$fd_list.= "<a href='files.php?d=".$PathDump."&f=".$val['file']."&a=edit'><img src='".$arrSetting["Path"]['ico']."/folder-album.gif' title='Файлы' class='img_btn'></a>";
						}
					$fd_list.= "</td>";
					
					$fd_list.= "<td><a href='".$PageLink."&action=dump_del&f_dump=".$val['file']."' style='color:green'>
						<img src='".$arrSetting["Path"]['ico']."/delete.gif' title='".$tbl_description."' class='img_btn'>
					</a></td>";
					$fd_list.= "</tr>";
				}
			}
			
			$fd_list.= "</tbody>";
			$fd_list.= "</table>";
			echo $fd_list;
		
		echo "</td>";
		echo "</tr></table>";
		echo "</form>";
		
	if(file_exists($TblDefTplPath . DS . "bottom.php")){include($TblDefTplPath . DS . "bottom.php");}
	$sql->sql_close();
*/
?>
























