<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
		<title><?php echo $TblSetting["table"]["description"].((isset($arrSetting["Main"]))?" | ".$arrSetting["Main"]["Name"]:""); ?></title>
		<link rel="stylesheet" href="<?php echo $TblDefTplPath; ?>/css/style.css" />
		<link rel="stylesheet" href="<?php echo $TblDefTplPath; ?>/css/table.css" />
		<link rel="stylesheet" href="<?php echo $TblDefTplPath; ?>/css/menu.css" />
		<script type="text/javascript" src="<?php echo $TblDefTplPath; ?>/js/jquery.min.js"></script>
		<script type="text/javascript" language='JavaScript'>
			function jsAddField(field_id,vl){
				var addffile = field_id;
				document.getElementById(addffile).value	 = vl;
			}
			
			function jsAddFieldPlus(field_id,vl){
				var addffile = field_id;
				document.getElementById(addffile).value	 = document.getElementById(addffile).value + vl;
			}
		</script>	
		<link rel='stylesheet' href='<?php echo $TblDefTplPath; ?>/js/jquery.nyroModal/styles/nyroModal.css' type='text/css' media='screen' />
		<script type='text/javascript' src='<?php echo $TblDefTplPath; ?>/js/jquery.nyroModal/js/jquery.nyroModal.custom.js'></script>
		<!--[if IE 6]><script type='text/javascript' src='<?php echo $TblDefTplPath; ?>/js/jquery.nyroModal-ie6.min.js'></script><![endif]-->
		
		<style>
			.sektion_key,
			.sektion_key_open{
				width: 150px;
				padding: 7px;
				margin: 5px 0 5px 0;
				font-size:14px; 	
			}
			
			.sektion_key{
				/*background-color: #dedede;*/
				background-color: #ededed;
				border: 1px solid #cccccc;
			}
			.sektion_key_open,
			.sektion_key:hover{
				background-color: #d1d1d1;
				border: 1px solid #bdbdbd;
			}
			.sektion_key a,
			.sektion_key_open a{
				font-size:14px; 
				text-decoration: none;
			}
			.sektion_key_open,
			.sektion_key_open a{
				font-weight: bold;
			}
			.sektion_description{
				font-size:10px;
				color:grey;
			}
			.input-text{
				padding: 3px;
				font-size: 13px;
				width: 300px;
			}
			.input-text[readonly=readonly] {
				border: 1px solid #dadada;
				color: #3c3c3c;
			}
			.input-select{
				padding: 3px;
				font-size: 13px;
				width: 310px;
			}
			.input-button[type="button"], .input-button[type="submit"] {
				background: #dadada;
				font-size: 13px;
				border: 1px solid #a9a9a9;
				cursor: pointer;
				padding: 7px 15px 7px 15px;
			}

			.input-button[type="button"]:hover, .input-button[type="submit"]:hover {
				background: #a9a9a9;
				border: 1px solid #9a9a9a;
			}

		</style>
		
	</head>
	<body>
		<div id="up_mn" style="position: fixed;z-index: 10;width: 100%;margin: 0;padding: 0;">
			<table width='100%' border='0' cellspacing='0' cellpadding='0'>
				<tr>
					<td align='left' valign='middle'>
						<div class='panel_title'>
							<div style="font: bold 11px/20px Arial, Helvetica, sans-serif; padding: 0 0 0 20px; text-transform: uppercase;">
								Редактирование настроек
							</div>
						</div>
					</td>
					<td width='40' align='center' valign='middle'><strong><a href="readme.htm" target="_blank"><?php echo $arrSetting['Version']['ver']; ?></a></strong></td>
				</tr>
			</table>
			<div class='panel_btn'>
				<a href='./config_edit.php'>Вернуться к выбору настроек</a>
				&nbsp;|&nbsp;
				<a href="./tables.php" >Вернуться к таблицам</a>
				&nbsp;|&nbsp;
				<a href='<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>'>Обновить страницу</a>
				
			</div>
		</div>
		<?php if(!$PrintPage){ ?>	
			<div style="margin-top: 49px;"></div>
		<?php } ?>			