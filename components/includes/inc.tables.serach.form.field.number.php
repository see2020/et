<?php

	if(isset($_GET[$TblSetting[$key]['name']]) ?? !is_numeric($_GET[$TblSetting[$key]['name']])){$_GET[$TblSetting[$key]['name']] = 0;}
	
	echo "<td>".$nm_field.": "."</td>";
	echo "<td>".$action_select."</td>";
	echo "<td>";

	$return_var = "";
	$return_var.= "<div class=\"sel_field\" style=\"width: ".$width_field."px;\">";
	$return_var.= frmInput(array("type"=>"text", "name"=>$TblSetting[$key]['name'], "style"=>"width: ".((int)$width_field - 56 - 56 - 56 - 28)."px; ", "value"=>$_GET[$TblSetting[$key]['name']]??"","id"=>$TblSetting[$key]['name'], ));

	$return_var.= frmInput(array("type" => "button", "value" => "-100", "style"=>"width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',-100);", "title"=>"Значение в поле -100"));
	$return_var.= frmInput(array("type" => "button", "value" => "-10", "style"=>"width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',-10);", "title"=>"Значение в поле -10"));
	$return_var.= frmInput(array("type" => "button", "value" => "-", "style"=>"width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',-1);", "title"=>"Значение в поле -1"));

	$return_var.= frmInput(array("type" => "button", "value" => "0", "style"=>"width: 28px;", "onclick"=>"document.getElementById('".$TblSetting[$key]['name']."').value = 0;", "title"=>"Сросить на ноль"));

	$return_var.= frmInput(array("type" => "button", "value" => "+", "style"=>"width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',1);", "title"=>"Значение в поле +1"));
	$return_var.= frmInput(array("type" => "button", "value" => "+10", "style"=>"width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',10);", "title"=>"Значение в поле +10"));
	$return_var.= frmInput(array("type" => "button", "value" => "+100", "style"=>"width: 28px;", "onclick"=>"increase('".$TblSetting[$key]['name']."',100);", "title"=>"Значение в поле +100"));

	$return_var.= "<div style=\"clear: both;padding: 0; margin: 0;\"></div>";
	$return_var.= "</div>";
	echo $return_var;
	$return_var = "";
	
	echo "</td>";

?>