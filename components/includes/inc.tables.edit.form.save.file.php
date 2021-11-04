<?php
	// ./_data_files/tbldata/division/files/prosto_fayl.txt
	if($TblSetting[$key]['type']=='file' || $TblSetting[$key]['type']=='image'){
		if(isset($_FILES[$TblSetting[$key]["name"]])){
			if($_FILES[$TblSetting[$key]["name"]]["name"]!=''){
				$tmp_file	 = $_FILES[$TblSetting[$key]["name"]]["tmp_name"];
				$file_name	 = $_FILES[$TblSetting[$key]["name"]]["name"];
				$file_size	 = $_FILES[$TblSetting[$key]["name"]]["size"];
				$arr[$TblSetting[$key]["name"]] = $file_name;
				
				if($_FILES[$TblSetting[$key]["name"]]["error"] == 0){
					
					$TblSetting["table"]['FileStore'] = trim($TblSetting["table"]['FileStore']);
					if($TblSetting["table"]['FileStore'] == ""){
						$FilesPath = $arrSetting['Path']['tbldata']."/".$TblSetting["table"]['name'];
						if(!newDir($FilesPath)){
							$ut->utLog("Ошибка создания папки ".$FilesPath." ".__FILE__);
						}
						$FilesPath = $FilesPath."/files";
						if(!newDir($FilesPath)){
							$ut->utLog("Ошибка создания папки ".$FilesPath." ".__FILE__);
						}
					}
					else{
						$FilesPath = $TblSetting["table"]['FileStore'];
						if(!is_dir($FilesPath)){
							$FilesPath = $arrSetting['Path']['tbldata']."/".$TblSetting["table"]['name'];
							if(!newDir($FilesPath)){
								$ut->utLog("Ошибка создания папки ".$FilesPath." ".__FILE__);
							}
							$FilesPath = $FilesPath."/files";
							if(!newDir($FilesPath)){
								$ut->utLog("Ошибка создания папки ".$FilesPath." ".__FILE__);
							}
						}
					}

					$txt = new class_txt();
					$string_fname = $txt->txtClearStr($txt->txtTranslit(strtolower($file_name)));
					
					$FArr = $flc->fFileName($FilesPath,$string_fname);
					$string_fname = $FArr['name'];
				
					if (@is_uploaded_file($tmp_file)){
						if(@move_uploaded_file($tmp_file,$FilesPath."/".$string_fname)){
							$arr[$TblSetting[$key]["name"]] = $FilesPath."/".$string_fname;
						}
						else{
							$ut->utLog("Ошибка загрузки файла. < ".$FilesPath."/".$string_fname." >. fsize=".$file_size."."." ".__FILE__);
							$arr[$TblSetting[$key]["name"]] = "";
						}
					}
					else{
						$ut->utLog("Ошибка загрузки файла. is_uploaded_file(".$tmp_file."). ".__FILE__);
						$arr[$TblSetting[$key]["name"]] = "";
					}	
				}
				else{
					$ut->utLog("Ошибка загрузки файла. ERROR: ".$_FILES[$TblSetting[$key]["name"]]["error"].". < ".$file_name." >. size=".$file_size."."." ".__FILE__);
				}
			}
			else{
				$arr[$TblSetting[$key]["name"]] = "";
			}
		}
		else{
			if($_POST[$TblSetting[$key]["name"]] != ""){
				$arr[$TblSetting[$key]["name"]] = $_POST[$TblSetting[$key]["name"]];
			}
			else{
				$f_name = trim($_SESSION[D_NAME][$TblSetting["table"]['name']]["edit"][$TblSetting[$key]["name"]]);
				if($f_name != ""){
					if($flc->fDelFile($f_name)){
						if($arrSetting['Access']['UsePassword']){
							$ut->utLog($TblSetting["table"]["name"]." Удален файл: ".$f_name.". _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
						}
					}
					else{
						$ut->utLog($TblSetting["table"]["name"]." Ошибка удаления файла: ".$f_name.".".(($arrSetting['Access']['UsePassword'])?" _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']):""));
					}
				}
			}
		}
	}
?>