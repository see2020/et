<?php
	// ����������� ��������� ������ �� ������� � �������
	// ���� � ������������ � ������ ������� ���� ����������� ������ "new", "read", "edit", ...
	if($TblSetting["table"]["FormButtonShowSave"]){
		//if(usr_Access("new")){
		if(usr_AccessTable($TblSetting["table"]["name"],"new")){
			if($_GET[$TblFieldPrimaryKey] == 0){
				$EditActionButton.= frmInput(array("type"=>"submit","name"=>"Save","id"=>"Save","value"=>"���������","title"=>"",));
			}
		}
		//if(usr_Access("edit")){
		if(usr_AccessTable($TblSetting["table"]["name"],"edit")){
			if($_GET[$TblFieldPrimaryKey] != 0){
				$EditActionButton.= frmInput(array("type"=>"submit","name"=>"Save","id"=>"Save","value"=>"���������","title"=>"",));
			}
		}
	}
	if($TblSetting["table"]["FormButtonShowCancel"]){
		$EditActionButton.= frmInput(array("type"=>"submit","name"=>"NoSave","id"=>"NoSave","value"=>"������","title"=>"",));	
	}
	if($_GET[$TblFieldPrimaryKey] != 0){
		if($TblSetting["table"]["FormButtonShowPrint"]){
			$EditActionButton.= frmInput(array("type"=>"button","name"=>"PrintThis","id"=>"PrintThis","value"=>"������","onclick"=>"window.open('?tbl=".$TblName."&pagenum=".$pg."&id=".$_GET[$TblFieldPrimaryKey]."&event=edit&print=1');",));
		}
		if($TblSetting["table"]["FormButtonShowCopy"]){
			//if(usr_Access("new")){
			if(usr_AccessTable($TblSetting["table"]["name"],"new")){
				$EditActionButton.= frmInput(array("type"=>"button","name"=>"CopyThis","id"=>"CopyThis","value"=>"������� �����","onclick"=>"location=('?tbl=".$TblName."&pagenum=".$pg."&id=0&f_copy=".$_GET[$TblFieldPrimaryKey]."&event=edit');",));
			}
		}
	}
?>