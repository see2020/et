<?php
	if($TblSetting[$key]['type']=='varbool'){$arr[$TblSetting[$key]["name"]] = (isset($_POST[$TblSetting[$key]["name"]]))?"1":"0";}
?>