<?php
	//вспомогательные поля
	$SupportNameLink = ($TblSetting[$key]['description']=="")?$TblSetting[$key]['name']:$TblSetting[$key]['description'];
	if($TblSetting[$key]['link_image']=="1" && $TblSetting[$key]['image']!=""){
		$SupportNameLink = "<img src='".$arrSetting['Path']['ico']."/".$TblSetting[$key]['image']."' alt='".$SupportNameLink."' />";
	}
	
	$SupportLink = "";
	if($TblSetting[$key]['name']=="f_copy"){
		// доступность проверяем только по доступу к таблице
		// если у пользователя к данной таблице есть определнный доступ "new", "read", "edit", ...
		if(usr_AccessTable($TblSetting["table"]["name"],"edit")){
			//if(usr_Access("edit")){
			$SupportLink = $PageLink."&".$TblFieldPrimaryKey."=0&f_copy=".$query[$TblFieldPrimaryKey]."&event=edit";	
			$ShowRow = fLnk($SupportNameLink, $SupportLink.$PageLinkSrch, array("target" => (($TblSetting[$key]['link_newwindow']=="1")?"_blank":""),"style" => $TblStatusColored));
		}
		else{
			$SupportLink = "javascript:void(0);";
			$ShowRow = "";
		}
	}
	elseif($TblSetting[$key]['name']=="f_edit"){
		$SupportLink = $PageLink."&".$TblFieldPrimaryKey."=".$query[$TblFieldPrimaryKey]."&event=edit";
		$ShowRow = fLnk($SupportNameLink, $SupportLink.$PageLinkSrch, array("target" => (($TblSetting[$key]['link_newwindow']=="1")?"_blank":""),"style" => $TblStatusColored));
	}
	elseif($TblSetting[$key]['name']=="f_del"){
		// доступность проверяем только по доступу к таблице
		// если у пользователя к данной таблице есть определнный доступ "new", "read", "edit", ...
		if(usr_AccessTable($TblSetting["table"]["name"],"admin")){
			// $SupportLink = $PageLink."&".$TblFieldPrimaryKey."=".$query[$TblFieldPrimaryKey]."&event=del";
			// $ShowRow = fLnk($SupportNameLink, $SupportLink.$PageLinkSrch, array("target" => (($TblSetting[$key]['link_newwindow']=="1")?"_blank":""),"style" => $TblStatusColored));
			// tables.php?tbl=maintbl&id=7&event=del
			$f_del_AjLink = "./aj.php?tbl=".((isset($_GET['tbl']))?$_GET['tbl']:$TblSetting["table"]["name"])."&af=f_del&".$TblFieldPrimaryKey."=".$query[$TblFieldPrimaryKey]."&event=del";
			$tmp_change_id = $TblSetting[$key]['name'].$query[$TblFieldPrimaryKey]."-5c5379a6chea5";
			$ShowRow = "<a href='javascript:void(0);' id='".$tmp_change_id."'
				".SetAttributes(array("style" => $TblStatusColored))." 
				onclick=\"
				if(confirm('Удаление записи.\\r\\n".$TblSetting["table"]["name"]."::".$TblFieldPrimaryKey."=".$query[$TblFieldPrimaryKey]."\\r\\nПосле удаления восстанвление будет не возможно!\\r\\nПроложить?\\r\\n')){
					$.ajax({
						url: '".$f_del_AjLink."',
						type: 'GET',
						success: function(resp){
							$('#".$tmp_change_id."').html(resp.trim());
						}
					});	
				}
				\">".$SupportNameLink."</a>";
		}
		else{
			$SupportLink = "javascript:void(0);";
			$ShowRow = "";
		}
	}
?>