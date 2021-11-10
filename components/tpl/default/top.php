<?php //$IcoPath = $arrSetting['Path']['ico']; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><?php echo $TblSetting["table"]["description"].((isset($arrSetting["Main"]))?" | ".$arrSetting["Main"]["Name"]:""); ?></title>

	<link rel="stylesheet" href="<?php echo $TblDefTplPath; ?>/css/style.css" />
	<link rel="stylesheet" href="<?php echo $TblDefTplPath; ?>/css/table.css" />
	<link rel="stylesheet" href="<?php echo $TblDefTplPath; ?>/css/menu.css" />
	<script type="text/javascript" src="<?php echo $TblDefTplPath; ?>/js/jquery.min.js"></script>
<?php
if(!empty($PrintPage)){
	echo '<link rel="stylesheet" href="'.$TblDefTplPath.'/css/print.css" />';
}
else{
	if(isset($_GET['event'])){
	if($_GET['event']=="edit" || $_GET['event']=="serach" || $_GET['event']=="tblconfig"){
?>
	<link rel="stylesheet" href="<?php echo $TblDefTplPath; ?>/css/tabs.css" />	

	<script type="text/javascript" src="<?php echo $TblDefTplPath; ?>/js/jquery.calendar/jquery.dynDateTime.js"></script>
	<script type="text/javascript" src="<?php echo $TblDefTplPath; ?>/js/jquery.calendar/calendar-russian.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $TblDefTplPath; ?>/js/jquery.calendar/calendar-system.css" />

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
	
	<script type='text/javascript' language='JavaScript'>
		$(document).ready(function() {
			$('ul.tabs').delegate('li:not(.current)', 'click', function() {
				$(this).addClass('current').siblings().removeClass('current').parents('div.section').find('div.box').hide().eq($(this).index()).fadeIn(150);
			})
		});
	</script>
	
	<link rel='stylesheet' href='<?php echo $TblDefTplPath; ?>/js/jquery.nyroModal/styles/nyroModal.css' type='text/css' media='screen' />
	<script type='text/javascript' src='<?php echo $TblDefTplPath; ?>/js/jquery.nyroModal/js/jquery.nyroModal.custom.js'></script>
	<!--[if IE 6]>
		<script type='text/javascript' src='<?php echo $TblDefTplPath; ?>/js/jquery.nyroModal-ie6.min.js'></script>
	<![endif]-->

<?php 
	}
} 
} 

?>

<?php if(isset($_GET['cfg']) && isset($_GET['sk'])) { ?>
	
	<script type="text/javascript" language="JavaScript">
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
<?php }?>
	
	
<?php 
	if(isset($_GET['event'])){
		if($_GET['event']=="tblconfig" || $_GET['event']=="tblconfiglist" || $_GET['event']=="iniconfig" || $_GET['event']=="iniconfig1") {
?>

<style>
.post {
	padding: 10px 20px;
	position: relative;
	background: #eee;
	margin-top: 20px;
	margin-bottom: 20px;
	border: 1px solid #CCCCCC;
}
.inactive {
}
.post .title {
	position: relative;
	height: 1%;
	}
.post .title span {
	position: absolute;
	right: 0;
	top: 0%;
	/*top: -5px;*/
	/*left: 250px;*/
	
	cursor: pointer;
	width: 14px;
	height: 14px;
	/*background: url(trigger.gif) no-repeat left bottom;*/
	display: block;
	font-size: 0;
	border: 1px solid blue;
	background-color: blue;
}
#content .inactive .title span {
	/*background-position: left top;*/
	background-color: transparent;
}
#content .post .entry {
	padding: 10px 0;
}
</style>

<?php 
		} 
	} 
?>

</head>
<body>


<div id="up_mn" style="position: fixed;z-index: 10;width: 100%;margin: 0;padding: 0;">

<table width='100%' border='0' cellspacing='0' cellpadding='0'>
	<tr>
		<td align='left' valign='middle'>
		
			<div class='panel_title'>
				<?php 
					$tbl_description = ($TblSetting["table"]['description']!="")?$TblSetting["table"]['description']:$TblSetting["table"]['name'];
					$TblIcoImg = ($TblSetting["table"]['ico']!="")?" background: url(".$arrSetting['Path']['ico']."/".$TblSetting["table"]['ico'].") no-repeat left; ":""; 
				?>
				
				<div style="font: bold 11px/20px Arial, Helvetica, sans-serif; padding: 0 0 0 20px; text-transform: uppercase;<?php echo $TblIcoImg; ?>">
					<?php echo $tbl_description; ?>
<?php
					if(!isset($_GET['event'])){
						echo " <span style='text-transform: lowercase;color: #8d8d8d;'>(Список)</span>";
					}
					if(isset($_GET['event'])){
						if($_GET['event']=="edit"){
							//$_SESSION[D_NAME]['user']['Login']
							if(isset($_GET["f_copy"]) && $_GET["f_copy"] > 0){
								echo " <span style='text-transform: lowercase;color: #8d8d8d;'>(Копирование)</span>";
							}
							elseif(isset($_GET[$TblSetting["table"]['PrimaryKey']]) && $_GET[$TblSetting["table"]['PrimaryKey']] == 0){
								echo " <span style='text-transform: lowercase;color: #8d8d8d;'>(Новая запись)</span>";
							}
							else{
								echo " <span style='text-transform: lowercase;color: #8d8d8d;'>(Редактирование)</span>";
							}
						}
						if($_GET['event']=="serach"){
							echo " <span style='text-transform: lowercase;color: #8d8d8d;'>(Поиск)</span>";
						}
					}
?>
				</div>
			</div>
		</td>
		<td width='40' align='center' valign='middle'><strong><a href="readme.htm" target="_blank"><?php echo $arrSetting['Version']['ver']; ?></a></strong></td>
	</tr>
</table>


<div class='panel_btn'>
	<?php if(file_exists($TblDefTplPath."/menu_top.php")){include($TblDefTplPath."/menu_top.php");} ?>
</div>

</div>
<?php if(empty($PrintPage)){ ?>
<div style="margin-top: 49px;"></div>
<?php } ?>
