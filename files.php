<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// 2014
// файл: files.php 
// файловый менеджер
// 2014.10.28
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	session_start();
	include("cfg.php");
	include(ET_PATH_RELATIVE . DS . "config.php");
	
	if(usr_Access("admin")){$u_access = true;}
	else{
		echo Message("Недостаточно прав на изменение этого раздела", "error");
		exit;
		$u_access = false;
	}
	
	$PageLink = "?tbl=".$arrSetting['Table']['DefaultTable'];
	
	// файлы картинок которые мы можем менять программно
	$ArrTypeFiles['img_edit'] = '[\.jpg|\.jpeg|\.png|\.gif|\.JPG|\.JPEG|\.PNG|\.GIF]';
	// картинки которые можем смотреть
	$ArrTypeFiles['img'] = '[\.jpg|\.jpeg|\.png|\.gif|\.tiff|\.tif|\.bmp|\.JPG|\.JPEG|\.PNG|\.GIF|\.TIFF|\.TIF|\.BMP]';
	// файлы которые мы можем просто посмотреть и отредактировать это картикнки и текстовые файлы
	$ArrTypeFiles['file'] = '[\.txt|\.rtf|\.htm|\.html|\.php|\.ini|\.tpl|\.js|\.class|\.inc|\.css|\.mnu|\.sql|\.log|\.htaccess|\.TXT|\.RTF|\.HTM|\.HTML|\.PHP|\.INI|\.TPL|\.JS|\.CLASS|\.INC|\.CSS|\.MNU|\.SQL|\.LOG|\.HTACCESS]';
	
	$RelPath		 = ".";
	$path_now_http	 = $RelPath;
	$var_temp_dir	 = $RelPath;
	
	//получаем текущий http путь папки
	if(isset($_GET['d'])){$path_now_http = $_GET['d'];}
	
	//если не выбрана папка
	if(isset($_GET['d'])){$var_temp_dir = $_GET['d'];}
	if(!empty($_GET['d'])){$var_temp_dir = $_GET['d'];}

	$vtd = "";
	$vtd_array = array();$vtd1 = $RelPath;$vtd_array = explode("/",$var_temp_dir);
	if(isset($_GET['f'])){
		for($i=1;$i < count($vtd_array);$i++){
			$vtd1.= "/".$vtd_array[$i];
			$vtd.= " / <a href='".$_SERVER['PHP_SELF']."?action=files&d=".$vtd1."'>".$vtd_array[$i]."</a>";
		}
		$f_size			 = 0;
		$dtlasteditfile	 = "";
		if(file_exists($_GET['d']."/".$_GET['f'])){
			$f_size = round(filesize($_GET['d']."/".$_GET['f'])/1024,3);
			$dtlasteditfile = " Дата последнего изменения: ".$ut->utGetDate("Y-m-d H:i:s",filemtime($_GET['d']."/".$_GET['f']));
		}
		$vtd.= " / "."<b>".$_GET['f']."</b>"." &nbsp;&nbsp;&nbsp; ".(($f_size>1024)?round($f_size/1024,3)."&nbsp;Mb":$f_size."&nbsp;Kb");
		$vtd.= $dtlasteditfile;
		$vtd = "<a href='".$_SERVER['PHP_SELF']."?action=files&d=".$RelPath."'>&bull;</a>".$vtd;
	}
	else{
		for($i=1;$i < count($vtd_array)-1;$i++){
			$vtd1.= "/".$vtd_array[$i];
			$vtd.= " / <a href='".$_SERVER['PHP_SELF']."?action=files&d=".$vtd1."'>".$vtd_array[$i]."</a>";
		}
		$fCOBF_1 = $flc->fCountObjectByFolder($var_temp_dir);
		$vtd.= " / <b>".$vtd_array[count($vtd_array)-1]."</b>"." &nbsp;&nbsp;&nbsp; dir:".$fCOBF_1['folders']."&nbsp; file:".$fCOBF_1['files'];
		$vtd = "<a href='".$_SERVER['PHP_SELF']."?action=files&d=".$RelPath."'>&bull;</a>".$vtd;
	}

	// разделитель файлов при выборе нескольких файлов
	$FSeparate = "{%}";

	// тема оформления	
	$TblList = array();
	$TblDefTplPath = $arrSetting['Path']['tpl']."/".$arrSetting['Table']['DefaultTpl'];
	$TblSetting["table"]['name'] = "";
	$TblSetting["table"]['ico'] = $arrSetting['Path']['ico']."/album.gif";
	$TblSetting["table"]['description'] = "Файлы";
	if(file_exists($TblDefTplPath."/top.php")){include($TblDefTplPath."/top.php");}
	
	//если файл не выбран
	if(!isset($_GET['f'])){

		echo "<table class='tab_list' >";
		echo "<thead>";
		echo "<tr>";
		echo "<th width='40' align='center'>&nbsp;</th>";
		echo "<th><b>name</b></th>";
		echo "<th width='70' align='center'><b>size</b></th>";
		echo "<th width='120'>Right</th>";
		echo "<th width='120' align='center'><b>Time change</b></th>";
		echo "<th width='120' align='center'><b>action</b></th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		//для возвращения на уровень назад получаем путь минус открытая папка
		$arr_tem_dir = explode("/",$var_temp_dir);$UpDir = "";
		for ($i=0; $i < (count($arr_tem_dir)-1); $i++){$UpDir.= $arr_tem_dir[$i]."/";}
		$UpDir = substr($UpDir, 0, -1); // убираем последний слеш
		if($var_temp_dir == $RelPath){$UpDir = $RelPath;}

		echo "<tr>";
			echo "<td>";
			echo "<a href='tables.php'><img src='".$arrSetting['Path']['ico']."/page-prev.gif' title='К таблицам' class='img_btn'></a>";
			echo "&nbsp;&nbsp;&nbsp;";
			echo "</td>";
			echo "<td colspan = 5>";
			echo $vtd;
			echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td align='center'><input type='checkbox' name='CheckAllFile' value='CheckFile' onClick=\"bldCheckFiles(this.form,'CheckFile[]',this.checked);bldGetFiles(this.form,'CheckFile[]');\"></td>";
		echo "<td>";
		
		echo "[<a href='".$_SERVER['PHP_SELF']."?action=files&d=".$RelPath."' style='color:blue' title='В корневую папку'>&bull;</a>]";
		echo "&nbsp;&nbsp;&nbsp;";
		echo "[<a href='".$_SERVER['PHP_SELF']."?action=files&d=".$UpDir."' style='color:blue' title='В предыдущую папку'>&bull;&bull;</a>]";
		
		echo "&nbsp;&nbsp;&nbsp;";
		
		echo "<a href='?d=".$path_now_http."&f=".$ut->utGetDate("Y-m-d")."&a=create'><img src='".$arrSetting['Path']['ico']."/list-new.gif' title='Новый файл' class='img_btn'></a>";
		echo "&nbsp;&nbsp;&nbsp;";
		echo "<a href='?d=".$path_now_http."&f=".$ut->utGetDate("Y-m-d")."&a=create_dir'><img src='".$arrSetting['Path']['ico']."/folder-new.gif' title='Новая папка' class='img_btn'></a>";
		echo "&nbsp;&nbsp;&nbsp;";
		echo "<a href='?d=".$path_now_http."&f=&a=upload'><img src='".$arrSetting['Path']['ico']."/upload.gif' title='Загрузка файлов' class='img_btn'></a>";
		
		echo "</td>";
		
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "</td>";
		echo "</tr>";
		
		//сначало выводим директории
		$flc->fListFolders($var_temp_dir,$path_now_http,true);
		if(isset($flc->fListFolders[0]['file'])){
			foreach ($flc->fListFolders as $key=>$val){
				$fCOBF = $flc->fCountObjectByFolder($val['server']."/".$val['file']);		
				echo "<tr>";
				echo "<td>&nbsp;</td>";
				echo "<td>
				".(($fCOBF['folders']>0)?"<img src='".$arrSetting['Path']['ico']."/folder-album.gif'>":"<img src='".$arrSetting['Path']['ico']."/folder.gif'>")."
				[<a href='".$_SERVER['PHP_SELF']."?d=".$val['server']."/".$val['file']."' style='color:blue'>".$val['file']."</a>] ".$fCOBF['folders']."/".$fCOBF['files']."</td>";
				echo "<td align='center'>DIR</td>";
				echo "<td align='center'>".$val['right']."</td>";
				echo "<td align='center'>".$ut->utGetDate("Y-m-d H:i:s",$val['time'])."</td>";
				echo "<td align='center'>";
				echo "<a href='".$_SERVER['PHP_SELF']."?d=".$val['server']."&f=".$val['file']."&a=delete_dir' title='Delete dir: ".$val['file']."' style='color:red'>
					<img src='".$arrSetting['Path']['ico']."/folder-delete.gif' alt=''>
				</a>";
				echo "</td>";
				echo "</tr>";
			}
		}		
		
		//выводим файлы
		$flc->fListFiles($var_temp_dir,$path_now_http,true);
		if(isset($flc->fListFiles[0]['file'])){
			$NotShowFileListArray[] = "";
			$NotShowFileListArray[] = ".";
			$NotShowFileListArray[] = "..";
			foreach($flc->fListFiles as $key=>$val){
				echo "<tr>";
				if(!in_array($val['file'],$NotShowFileListArray)){
					echo "<td align='center'><input type='checkbox' name='CheckFile[]' id='".$val['file']."' value='".$val['server']."/".$val['file']."' onClick=\"bldGetFiles(this.form,'CheckFile[]')\" /></td>";
				}else{echo "<td>&nbsp;</td>";}
			
				if(preg_match($ArrTypeFiles['img_edit'], strtolower($val['file']))){

					$ImgSize = getimagesize($val['server']."/".$val['file']);
					echo "<td>";
						echo "<a href='".$_SERVER['PHP_SELF']."?d=".$val['server']."&f=".$val['file']."&a=edit' title='Edit file: ".$val['file']."' style='color:green'>";					
						echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
							echo "<tr>";
								echo "<td width='55' valign='middle' >";
								echo "<img src='".$val['path']."/".$val['file']."' width='50' height='50' border='0' >";
								echo "</td>";
								echo "<td width='100%' valign='middle'>";
								echo "".$val['file']."<br>(".$ImgSize[0]."x".$ImgSize[1].")";
								echo "</td>";
							echo "</tr>";
						echo "</table>";
						echo "</a>";
					echo "</td>";
				}
				else{
					echo "<td>&nbsp;
					<a href='".$_SERVER['PHP_SELF']."?d=".$val['server']."&f=".$val['file']."&a=edit' title='Edit file: ".$val['file']."' style='color:green'>
					".$val['file']."</a></td>";
				}
				if($val['size'] > 1024*1024){
					$val['size'] = round($val['size']/(1024*1024),3)."&nbsp;Gb";
				}
				elseif($val['size'] > 1024){
					$val['size'] = round($val['size']/1024,3)."&nbsp;Mb";
				}
				else{
					$val['size'] = $val['size']."&nbsp;kb";
				}

				echo "<td>".$val['size']."</td>";
				echo "<td align='center'>".$val['right']."</td>";
				echo "<td align='center'>".$ut->utGetDate("Y-m-d H:i:s",$val['time'])."</td>";

				//действия над файлом
				echo "<td align='center'>";
				echo "<a href='".$val['path']."/".$val['file']."' target='_blank' title='Download file: ".$val['file']."' style='color:blue'><img src='".$arrSetting['Path']['ico']."/download.gif' alt=''></a> &nbsp; &nbsp; ";
				echo "<a href='".$_SERVER['PHP_SELF']."?d=".$val['server']."&f=".$val['file']."&a=edit' title='Edit file: ".$val['file']."' style='color:blue'><img src='".$arrSetting['Path']['ico']."/edit.gif' alt=''></a> &nbsp; &nbsp; ";
				echo "<a href='".$_SERVER['PHP_SELF']."?d=".$val['server']."&f=".$val['file']."&a=delete' title='Delete file: ".$val['file']."' style='color:red'><img src='".$arrSetting['Path']['ico']."/list-delete.gif' alt=''></a>";
				echo "</td>";
				echo "</tr>";
			}
		}
		echo "</tbody>";
		echo "</table>";
	}
	else{
		//если выбрано действие
		if(isset($_GET['a'])){
			//редактируем файл
			if($_GET['a']=='edit'){
				$FNameArr = $flc->fFileName($_GET['d'],$_GET['f']);
				$fileName_a = $FNameArr['name_a'];
				$fileName_b = $FNameArr['name_b'];
				$fileName = $_GET['d']."/".$FNameArr['name'];
				
				//переименовываем
				if(isset($_POST['ReName'])){
					if(file_exists($_GET['d']."/".trim($_POST['f_name']))){
						$FNameArr1 = $flc->fFileName($_GET['d'],trim($_POST['f_name']));
						$ut->utLog(__FILE__ . " - Файл с таким именем уже существует: ".$_GET['d']."/".trim($_POST['f_name'])." новое имя: ".$FNameArr1['name']);
						$_POST['f_name'] = $FNameArr1['name'];
					}
					if(rename($_GET['d']."/".$_GET['f'],$_GET['d']."/".trim($_POST['f_name']))){
						echo "<p align='center'>Новое имя файла: ".$_POST['f_name']."</p>";
						echo "<p align='center'><a href='?d=".$_GET['d']."&f=".trim($_POST['f_name'])."&a=".$_GET['a']."' title='refresh' style='color:black;' >refresh page</a></p>";
						$ut->utLog(__FILE__ . " - Переименование файла: ".$_GET['d']."/".$_GET['f']." >> ".$_GET['d']."/".trim($_POST['f_name']));
						Redirect("?d=".$_GET['d']."&f=".trim($_POST['f_name'])."&a=".$_GET['a'],1000);
					
					}else{
						echo "<p align='center'>Ошибка переименования файла </p>";
						$ut->utLog(__FILE__ . " - Ошибка переименования файла: ".$_GET['d']."/".$_GET['f']." >> ".$_GET['d']."/".trim($_POST['f_name']));
						Redirect("?d=".$_GET['d']."&f=".trim($_POST['f_name'])."&a=".$_GET['a'],2000);
					}
				}
				
				// сохраняем содержимое
				if(isset($_POST['SaveContent'])){
					if(preg_match($ArrTypeFiles['file'], strtolower($fileName_b))){
						//сохраняем файл
						$flc->fRewrite($_GET['d']."/".$_GET['f'],stripslashes($_POST['f_content']));
					}
				}

				//сохраняем как копию файла
				if(isset($_POST['FileCopy'])){
					if(!file_exists($_POST['f_name22'])){
						if(copy($_GET['d']."/".$_GET['f'], $_POST['f_name22'])){
							$ut->utLog(__FILE__ . " - Копия файла: ".$_GET['d']."/".$_GET['f']." >> ".$_POST['f_name22']);
							echo "<p align='center'>Копия сохранена в ".$_POST['f_name22']." </p>";
							echo "<p align='center'><a href='?d=".$_GET['d']."&f=".$_GET['f']."&a=".$_GET['a']."' title='refresh' style='color:black;' >refresh page</a></p>";
							Redirect("?d=".$_GET['d']."&f=".$_GET['f']."&a=".$_GET['a'],1000);
						}else{
							echo "<p align='center'>Ошибка копирования файла </p>";
							$ut->utLog(__FILE__ . " - Ошибка копирования файла: ".$_GET['d']."/".$_GET['f']." >> ".$_POST['f_name22']);
							Redirect("?d=".$_GET['d']."&f=".$_GET['f']."&a=".$_GET['a'],2000);
						}
					}else{
						echo "<p align='center'>Ошибка копирования файла, файл существует</p>";
						$ut->utLog(__FILE__ . " - Ошибка копирования файла: ".$_GET['d']."/".$_GET['f']." >> ".$_POST['f_name22']." файл существует");
						Redirect("?d=".$_GET['d']."&f=".$_GET['f']."&a=".$_GET['a'],2000);
					}
				}	
				
				//читаем файл
				echo "<div id='inputArea'><form name='form2' method='post' action=''>";
				echo "<p>".$vtd."</p>";
				echo "<b>Копия: </b> ";
				echo "из ".$_GET['d']."/".$_GET['f']."";
				echo " в <input type='text' name='f_name22' value='".$fileName."' style='width:300px'>&nbsp;&nbsp;";
				echo "<input type='submit' name='FileCopy' value='Copy'>";
				echo "</form>";
				
				echo "<form name='form1' method='post' action=''>";
				echo "<b>file name:</b><br>";
				echo "<input type='text' name='f_name' value='".$_GET['f']."' style='width:300px;'> ";
				echo "<input type='submit' name='ReName' value='ReName'>";
				echo "<br>";
				echo "<b>file content:</b><br>";
				
				$image_size[0] = 0;
				$image_size[1] = 0;
				if(preg_match($ArrTypeFiles['img_edit'], strtolower($_GET['f']))){
					$image_size = getimagesize($_GET['d']."/".$_GET['f']);
				}
				if(preg_match($ArrTypeFiles['img'], strtolower($_GET['f']))){
					$w_img = "512";
					if($image_size[0] < $w_img && $image_size[0] != 0){
						$w_img = $image_size[0];
					}
					echo "<p>".$image_size[0]." x ".$image_size[1]."</a></p>";
					echo "<p><a href='".$path_now_http."/".$_GET['f']."' target='_blank'><img src='".$path_now_http."/".$_GET['f']."' border='0' width='".$w_img."' ></a></p>";
				}
				elseif(preg_match($ArrTypeFiles['file'], strtolower($fileName_b))){
					$f_con = $flc->fGetContent($_GET['d']."/".$_GET['f']);
					echo "<textarea name='f_content' style='width:100%; height:400px;'>".$f_con."</textarea><br>";
					echo "<input type='submit' name='SaveContent' value='SaveContent'>"; //сохраняем в тот же файл
				}
				else{
					echo "<br>";
					echo "<br>";
					echo "Не поддерживаемый тип файла: ".$fileName_b;
					echo "<br>";
					echo "<br>";
					echo "<a href='".$_GET['d']."/".$_GET['f']."' target='_blank'>".$_GET['d']."/".$_GET['f']."</a>";
					echo "<br>";
					echo "<br>";
				}
				
				echo "</form></div>";
			}
		
			//удаляем файл
			if($_GET['a']=='delete'){
				echo "<div align='center'><table width='300' border='0' cellpadding='0' cellspacing='1' bordercolor='#CCCCFF'><tr><td align='center'>";
				if(isset($_POST['buttonYes'])){
					if(file_exists($_GET['d']."/".$_GET['f'])){
						$flc->fDelFile($_GET['d']."/".$_GET['f']);
						$ut->utLog(__FILE__ . " - Файл удален: ".$_GET['d']."/".$_GET['f']);
					}
					echo "<div>file ".$_GET['f']." delete</div>";
					echo "<div><a href='?d=".$_GET['d']."'>back</a></div>";					
					Redirect("?d=".$_GET['d'],1000);
				}
				elseif(isset($_POST['buttonNo'])){Redirect("?d=".$_GET['d']);}
				else{
					$text = "Удалить файл: <b>".$_GET['f']."</b>?<br>";
					$ActionString = "?d=".$_GET['d']."&f=".$_GET['f']."&a=delete";
					echo $ut->utWindowYesNo($text,$ActionString);
				}
				
				echo "</td></tr></table></div>";
			}
		
			// удаляем папку
			if($_GET['a']=='delete_dir1'){
				$flc->fDelDir($_GET['d']."/".$_GET['f']);
				$ut->utLog(__FILE__ . " - Папка удалена: ".$_GET['d']."/".$_GET['f']);
				echo "<div>Папка ".$_GET['f']." Удалена</div>";
				echo "<div><a href='?d=".$_GET['d']."'>back</a></div>";	
				Redirect("?d=".$_GET['d'],1000);
			}
			if($_GET['a']=='delete_dir'){
				echo "<div align='center'><table width='300' border='0' cellpadding='0' cellspacing='1' bordercolor='#CCCCFF'><tr><td align='center'>";
				if(isset($_POST['buttonYes'])){
					if(is_dir($_GET['d']."/".$_GET['f'])){
						
						$flc->fDelDirTreeShow($_GET['d']."/".$_GET['f']);
						reset($flc->fDelDirTree_f);
						while(list($key,$val)=each($flc->fDelDirTree_f)){
							$flc->fDelFile($val);
							$ut->utLog(__FILE__ . " - Файл удален: ".$val);
						}
						echo "<br>";
						reset($flc->fDelDirTree_d);
						while(list($key,$val)=each($flc->fDelDirTree_d)){
							$flc->fDelDir($val);
							$ut->utLog(__FILE__ . " - Папка удалена: ".$val);
						}
						echo "<div>Содержимое удалено</div>";
						Redirect("?d=".$_GET['d']."&f=".$_GET['f']."&a=delete_dir1",1000);
					}
				}
				elseif(isset($_POST['buttonNo'])){Redirect("?d=".$_GET['d']);}
				else{
					$text = "Удалить папку: <b>".$_GET['f']."</b>?<br>Также будут удалены все вложенные папки и файлы<br>";
					$ActionString = "?d=".$_GET['d']."&f=".$_GET['f']."&a=delete_dir";
					echo $ut->utWindowYesNo($text,$ActionString);
				}
				
				echo "</td></tr></table></div>";
			}
			
			//создаем файл
			if($_GET['a']=='create'){
				
				if(isset($_POST['NewFile'])){
				
					$fileName = $txt->txtClearStr($txt->txtTranslit($_POST['f_name']));
					$fileName = $fileName.".".$txt->txtClearStr($_POST['f_ext']);
				
					if(file_exists($_GET['d']."/".$fileName)){
						$FNameArr1 = $flc->fFileName($_GET['d'],$fileName);
						$ut->utLog(__FILE__ . " - Файл с таким именем уже существует: ".$_GET['d']."/".trim($fileName)." новое имя: ".$FNameArr1['name']);
						$fileName = $FNameArr1['name'];
					}
					
					if($flc->fRewrite($_GET['d']."/".$fileName,stripslashes($_POST['f_content']))){
						echo "<p align='center'>Новый файл: ".$fileName."</p>";
						echo "<p align='center'><a href='?d=".$_GET['d']."&f=".$fileName."&a=".$_GET['a']."' title='refresh' style='color:black;' >refresh page</a></p>";
						$ut->utLog(__FILE__ . " - Новый файл: ".$_GET['d']."/".$_GET['f']." >> ".$_GET['d']."/".$fileName);
						Redirect("?d=".$_GET['d']."&f=".$fileName."&a=edit",1000);
					
					}
					else{
						echo "<p align='center'>Ошибка создания файла </p>";
						$ut->utLog(__FILE__ . " - Ошибка сщздания файла: ".$_GET['d']."/".$_GET['f']." >> ".$_GET['d']."/".$fileName);
						Redirect("?d=".$_GET['d']."&f=".$fileName."&a=edit",2000);
					}
				}
				
				echo "<div id='inputArea'><form name='form2' method='post' action=''>";
				echo "<h1>Новый файл</h1>";
				echo "file name:<br>";
				echo "<input type='text' name='f_name' value='".$_GET['f']."' style='width:98%;'><br>";
				echo "file ext:<br>";
				echo "<input type='text' name='f_ext' value='txt' style='width:50px;'><br>";
				echo "file content:<br>";
				echo "<textarea name='f_content' style='width:98%; height:300px;'></textarea><br>";
				echo "<input type='submit' name='NewFile' id='NewFile' value='Save'>";
				echo "</form></div>";
			}
		
			//создаем папку
			if($_GET['a']=='create_dir'){

				//сохраняем
				if(isset($_POST['NewDir'])){
				
					$dName = $txt->txtClearStr($txt->txtTranslit($_POST['d_name']));
					if(is_dir($_GET['d']."/".$dName)){
						$FNameArr1 = $flc->fDirName($_GET['d'],$dName);
						$ut->utLog(__FILE__ . " - Папка с таким именем уже существует: ".$_GET['d']."/".trim($dName)." новое имя: ".$FNameArr1['name']);
						$dName = $FNameArr1['name'];
					}
					echo "<p align='center'>".$_GET['d']."/".$dName."</p>";
					if(mkdir($_GET['d']."/".$dName, 0777)){
						echo "<p align='center'>Новая папка: ".$dName."</p>";
						echo "<p align='center'><a href='?d=".$_GET['d']."/".$dName."' title='refresh' style='color:black;' >refresh page</a></p>";
						$ut->utLog(__FILE__ . " - Новая папка: ".$_GET['d']."/".$dName);
						Redirect("?d=".$_GET['d']."/".$dName."",1000);
					}
					else{
						echo "<p align='center'>Ошибка создания папки </p>";
						$ut->utLog(__FILE__ . " - Ошибка создания папки: ".$_GET['d']."/".$dName."");
						Redirect("?d=".$_GET['d'],2000);
					}

				}
				
				echo "<div id='inputArea'><form name='form2' method='post' action=''>";
				echo "<h1>Новая папка</h1>";
				echo "dir name:<br>";
				echo "<input type='text' name='d_name' id='d_name' value='".$_GET['f']."' style='width:98%'><br>";
				echo "<input type='submit' name='NewDir' id='NewDir' value='Save'>";
				echo "</form></div>";
			}
			
			// загрузка файлов
			if($_GET['a']=='upload'){
				if(isset($_POST['Upload'])){
					if(isset($_FILES["f_name"]) && $_FILES["f_name"]["name"]!=''){
						$tmp_file	 = $_FILES["f_name"]["tmp_name"];
						$file_name	 = $_FILES["f_name"]["name"];
						$file_size	 = $_FILES["f_name"]["size"];

						if($_FILES["f_name"]["error"] == 0){
							$fileName = $txt->txtClearStr($txt->txtTranslit($file_name));
							$fileName = strtr($fileName,array(" "=>"_"));
							if(file_exists($_GET['d']."/".$fileName)){
								$FNameArr1 = $flc->fFileName($_GET['d'],$fileName);
								$ut->utLog(__FILE__ . " - Файл с таким именем уже существует: ".$_GET['d']."/".trim($fileName)." новое имя: ".$FNameArr1['name']);
								$fileName = $FNameArr1['name'];
							}
							if(!copy($tmp_file, $_GET['d']."/".$fileName)){$ut->utLog(__FILE__ . " - Ошибка загрузки файла. < ".$_GET['d']."/".$fileName." >. file_size=".$file_size.".");}
							else{$ut->utLog(__FILE__ . " - Успешная загрузка файла. < ".$_GET['d']."/".$fileName." >. file_size=".$file_size.".");}
						}
						else{
							$ut->utLog(__FILE__ . " - Ошибка загрузки файла. err_num(".$_FILES["f_name"]["error"].").");
						}// ошибка загрузки файла
					}
					Redirect("?d=".$path_now_http."",0);
				}
			
				echo "<div id='inputArea'>
						<form action='' method='post' enctype='multipart/form-data' name='form1' id='form1'>
							<input type='file' name='f_name' id='f_name' />
							<input type='submit' name='Upload' id='Upload' value='Загрузить' />
						</form>
					</div>";
			
			}
		
		}
	}
	
	if(file_exists($TblDefTplPath."/bottom.php")){include($TblDefTplPath."/bottom.php");}
?>