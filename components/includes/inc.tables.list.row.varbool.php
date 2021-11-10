<?php
	$return_id = "";
	if($TblSetting["table"]['is_directory'] == "1"){
		$return_id = "&return_".$TblFieldPrimaryKey."=".((isset($_GET[$TblFieldPrimaryKey]))?$_GET[$TblFieldPrimaryKey]:"id");
	}
	
	$tmpLnk2 = "";
	if(isset($_GET['tbl'])){
		$tmpLnk2 = "./aj.php?tbl=".$_GET['tbl']."&af=varbool&".$TblFieldPrimaryKey."=".$query[$TblFieldPrimaryKey]."&event=change&field=".$TblSetting[$key]['name'].$return_id.($PageLinkSrch??"").($lnk_order_by??"");
	}

	if($TblSetting[$key]['link'] != ""){
		$tmpLnk2 = SetTplLnk($tmpArrSetField, $query, $TblSetting[$key]['link']);
	}
	
	$tmpName = $query[$TblSetting[$key]['name']];
	
	if($TblSetting[$key]['link_image']=="1"){
		if($query[$TblSetting[$key]['name']] == 1 && $TblSetting[$key]['image']!=""){
			$tmpName = "<img src='".$arrSetting['Path']['ico']."/".$TblSetting[$key]['image']."' alt='".$tmpName."' title='".$tmpName."' />";
		}
		if($query[$TblSetting[$key]['name']] == 0 && $TblSetting[$key]['image_other']!=""){
			$tmpName = "<img src='".$arrSetting['Path']['ico']."/".$TblSetting[$key]['image_other']."' alt='".$tmpName."' title='".$tmpName."' />";
		}
	}
	
	if($TblSetting["table"]["directory_type"] == $TblSetting[$key]['name']){
		$ShowRow = fLnk($tmpName, "javascript:void(0);");
	}
	else{
		// доступность проверяем только по доступу к таблице
		// если у пользователя к данной таблице есть определнный доступ "new", "read", "edit", ...
		if(usr_AccessTable($TblSetting["table"]["name"],"edit")){
			$tmp_change_id = $TblSetting[$key]['name'].$query[$TblFieldPrimaryKey]."-5c5937a8caba0";
			$ShowRow = "<a href='javascript:void(0);' id='".$tmp_change_id."'
				".SetAttributes(array("style" => $TblStatusColored))." 
				onclick=\"
						$.ajax({
						url: '".$tmpLnk2."',
						type: 'GET',
						success: function(resp){
							$('#".$tmp_change_id."').html(resp.trim());
						}
					});	
				\">".$tmpName."</a>";
		}
		else{
			$ShowRow = "";
		}			
	}
?>