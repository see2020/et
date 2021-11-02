<?php
	//$ShowRow = $query[$TblSetting[$key]['name']];
	$ShowRow = "";
	if($query[$TblSetting[$key]['name']] != ""){
		$arrListStr = explode("|||", $query[$TblSetting[$key]['name']]);
		if(count($arrListStr) > 0){
			$cntListStr = 1;
			foreach($arrListStr as $valListStr){
				$row_id = "row_".$TblSetting[$key]['name']."_ls_id_".$cntListStr;
				//$ShowRow.= "<span id='".$row_id."'>".$cntListStr.". ".$valListStr."</span><br>";
				$ShowRow.= "<span id='".$row_id."'>".$valListStr."</span><br>";
				$cntListStr++;
			}
		}
	}	
?>