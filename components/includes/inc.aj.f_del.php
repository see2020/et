<?php
	//inc.aj.f_del.php

	// ����������� ��������� ������ �� ������� � �������
	// ���� � ������������ � ������ ������� ���� ����������� ������ "new", "read", "edit", ...
	if(usr_AccessTable($TblSetting["table"]["name"],"admin")){$u_access = true;}
	else{
		$ut->utLog($TblSetting["table"]["name"]." ������������ ���� �� �������� ������. ".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."; _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
		$u_access = false;
	}
	
	// �������� ������ ��� �������
	if($u_access){
	
		if($arrSetting['Access']['UsePassword']){
			$ut->utLog($TblSetting["table"]["name"]." �������� ������: ".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."; _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
		}
		
		// ��������� ������� ����� ���������
		$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["BeforeDelRow"];
		if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
		
		// ������� ������������� �����
		//asort($TblSetting["sortfieldform"]);
		foreach($TblSetting["sortfieldform"] as $key => $val){
			if($TblSetting[$key]['type']=='file' || $TblSetting[$key]['type']=='image'){
				
				$f_query = GetRowDataByPrimaryKey($sql,$ut,$TblSetting,$_GET[$TblFieldPrimaryKey]);
				$f_name = trim($f_query[$TblSetting[$key]['name']]);
				$ut->utLog($TblSetting["table"]["name"].". ������. ������� �������� �����: ".$f_name.".".(($arrSetting['Access']['UsePassword'])?" _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']):""));
				if($f_name != ""){
					if($flc->fDelFile($f_name)){
						if($arrSetting['Access']['UsePassword']){
							$ut->utLog($TblSetting["table"]["name"].". ������. ������ ����: ".$f_name.". _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
						}
					}
					else{
						$ut->utLog($TblSetting["table"]["name"].". ������. ������ �������� �����: ".$f_name.".".(($arrSetting['Access']['UsePassword'])?" _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']):""));
					}
				}
			}
		}
		
		if(!$sql->sql_delete($TblSetting["table"]['name'],"`".$TblFieldPrimaryKey."`='".$_GET[$TblFieldPrimaryKey]."'")){
			if($arrSetting['Access']['UsePassword']){
				$ut->utLog($TblSetting["table"]["name"]." ������ �������� ������: ".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."; _SESSION[user]".ParseArrForLog($_SESSION[D_NAME]['user']));
				$tmpName = "error";
				echo $tmpName;
			}
			else{
				$tmpName = "deleted";
				// if($TblSetting["f_del"]['link_image']=="1" && trim($TblSetting["f_del"]['image_other']) != ""){
					// $tmpName = "<img src=".$arrSetting['Path']['ico']."/".$TblSetting["f_del"]['image_other'].">";
				// }
				echo $tmpName;
			}
		}
		else{
			// ��������� ������� ����� ��������
			$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["table"]["AfterDelRow"];
			if(file_exists($func_file) && is_file($func_file)){include($func_file);}$func_file = "";
		}


	}
	
?>