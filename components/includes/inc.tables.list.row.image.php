<?php
		if($query[$TblSetting[$key]['name']] != ""){
			$ShowImage = "<img src='".$query[$TblSetting[$key]['name']]."' style='width:100px;' border='0' title='".$query[$TblSetting[$key]['name']]."'>";
			$ShowRow = fLnk($ShowImage, $query[$TblSetting[$key]['name']], array("target" => (($TblSetting[$key]['link_newwindow']=="1")?"_blank":""),"style" => $TblStatusColored));
		}
		else{$ShowRow = "";}
?>