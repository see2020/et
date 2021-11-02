<?php
		if($query[$TblSetting[$key]['name']] != ""){
			$ShowRow = fLnk("Скачать", $query[$TblSetting[$key]['name']], array("target" => (($TblSetting[$key]['link_newwindow']=="1")?"_blank":""),"style" => $TblStatusColored));
		}
		else{$ShowRow = "";}
?>