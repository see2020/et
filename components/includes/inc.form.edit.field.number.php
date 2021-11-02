<?php

	if(!is_numeric($qChange[$TblSetting[$key]['name']])){$qChange[$TblSetting[$key]['name']] = 0;}
	
	echo "<span class='field_name'>".$nm_field.":</span><br>";
	$st_all = "margin: 0;padding: 0;";
	$number_var = "";
	$number_var.= "<div class=\"sel_field\" style=\"".$st_all."width: ".$width_field."px;\">";
	if(!$readonly_var){
		if(isset($TblSetting[$key]['maxlength']) && $TblSetting[$key]['maxlength'] != "" && $TblSetting[$key]['maxlength'] != 0){
			$TblSetting[$key]['maxlength'] = (int)$TblSetting[$key]['maxlength'];
			$number_var.= frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name'], "style"=>"width: ".((int)$width_field - 56 - 56 - 56 - 28)."px; ", "value"=>$qChange[$TblSetting[$key]['name']],"id"=>$TblSetting[$key]['name'],"maxlength"=>$TblSetting[$key]['maxlength'] ));
		}
		else{
			$number_var.= frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name'], "style"=>"width: ".((int)$width_field - 56 - 56 - 56 - 28)."px; ", "value"=>$qChange[$TblSetting[$key]['name']],"id"=>$TblSetting[$key]['name'], ));
		}
		
		$number_var.= frmInput(array("type" => "button", "value" => "-100", "style"=>"".$st_all."width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',-100);", "title"=>"«начение в поле -100"));
		$number_var.= frmInput(array("type" => "button", "value" => "-10", "style"=>"".$st_all."width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',-10);", "title"=>"«начение в поле -10"));
		$number_var.= frmInput(array("type" => "button", "value" => "-", "style"=>"".$st_all."width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',-1);", "title"=>"«начение в поле -1"));

		$number_var.= frmInput(array("type" => "button", "value" => "0", "style"=>"".$st_all."width: 28px;", "onclick"=>"document.getElementById('".$TblSetting[$key]['name']."').value = 0;", "title"=>"—бросить на ноль"));

		$number_var.= frmInput(array("type" => "button", "value" => "+", "style"=>"".$st_all."width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',1);", "title"=>"«начение в поле +1"));
		$number_var.= frmInput(array("type" => "button", "value" => "+10", "style"=>"".$st_all."width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',10);", "title"=>"«начение в поле +10"));
		$number_var.= frmInput(array("type" => "button", "value" => "+100", "style"=>"".$st_all."width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',100);", "title"=>"«начение в поле +100"));
		
	}
	else{
		$number_var.= frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name'], "readonly"=>"readonly", "style"=>"".$st_all."width: ".((int)$width_field - 56 - 56 - 56)."px; ", "value"=>$qChange[$TblSetting[$key]['name']],"id"=>$TblSetting[$key]['name'], ));							
	}
	$number_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
	$number_var.= "</div>";
	echo $number_var;
	$number_var = "";
?>