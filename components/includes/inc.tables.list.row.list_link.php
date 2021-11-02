<?php
	//$ShowRow = $query[$TblSetting[$key]['name']];
	$ShowRow = "";
	if($query[$TblSetting[$key]['name']] != ""){
		$arrListLink = explode("|||", $query[$TblSetting[$key]['name']]);
		if(count($arrListLink) > 0){
			$arrListLink1 = array();
			$cntListLink = 1;
			$trgt = "";
			if($TblSetting[$key]['link_newwindow']){$trgt = "target='_blank'";}
			foreach($arrListLink as $valListLink){
				$row_id = "row_".$TblSetting[$key]['name']."_ls_lnk_id_".$cntListLink;
				//$ShowRow.= "<span id='".$row_id."'>".$valListLink."</span><br>";
				$arrListLink1 = explode("::", $valListLink);
				$ShowRow.= "<span id='".$row_id."'><a href='".$arrListLink1[0]."' ".$trgt.">".$arrListLink1[1]."</a></span><br>";
				$cntListLink++;
			}
		}
	}	
?>