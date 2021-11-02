<?php
	$EditActionButton	 = "";
	$EditActionLink		 = "?tbl=".$TblSetting["table"]['name']."&pagenum=".$pg."&".$TblFieldPrimaryKey."=".$_GET[$TblFieldPrimaryKey]."&event=".$_GET['event']."";
	include(GetIncFile($arrSetting,"inc.form.edit.spr_config.php", $TblSetting["table"]['name']));
	include(GetIncFile($arrSetting,"inc.form.edit.tabs.php", $TblSetting["table"]['name']));
	include(GetIncFile($arrSetting,"inc.form.edit.button.php", $TblSetting["table"]['name']));
	include(GetIncFile($arrSetting,"inc.form.edit.status.php", $TblSetting["table"]['name']));
	include(GetIncFile($arrSetting,"inc.form.edit.js.php", $TblSetting["table"]['name']));
?>
<div class='section'>
<?php
	// навигация по справочнику
	if($TblSetting["table"]['is_directory'] == "1"){
		$NavTmp = spr_navigation_show($sql,
			$TblSetting, 
			"?tbl=".$TblSetting["table"]['name']."&event=".$_GET['event']."", 
			array(),
			0,
			$qChange[$TblSetting["table"]['PrimaryKey']],
			"nowindow"
		);
		echo Message($NavTmp["NavShow"]);
	}
?>
	
	<div class='form_show' style='display:none;'><?php echo Message("<a href='javascript:void(0);' class='form_sh'>Показать данные раздела</a>");?></div>
	<div class='form_hide' style='display:none;'><?php echo Message("<a href='javascript:void(0);' class='form_hd'>Скрыть данные раздела</a>");?></div>
	<div class='form_edit'>
	
	<ul class='tabs'>
		<li <?php echo $arrTabThisPage[0]['name'];?>>Основная</li>
		<?php if($TblSetting["table"]['UseTableFileList'] == "1") { ?><li <?php echo $arrTabThisPage[1]['name'];?>><?php echo $TblSetting["table"]["NameTabFileList"]; ?></li><?php } ?>
		<?php if($TblSetting["table"]['UseTableList'] == "1") { ?><li <?php echo $arrTabThisPage[2]['name'];?>><?php echo $TblSetting["table"]["NameTabTableList"]; ?></li><?php } ?>
		<?php if($TblSetting["table"]['UseTableUser'] == "1") { ?><li <?php echo $arrTabThisPage[3]['name'];?>><?php echo $TblSetting["table"]["NameTabTableUser"]; ?></li><?php } ?>
		<?php
			for ($form_edit_num_tab = 4; $form_edit_num_tab <= $arrSetting['Other']['TabCount']; $form_edit_num_tab++){
				if($TblSetting["FormEditTab_".$form_edit_num_tab]["TabUse"]){
					echo "<li ".$arrTabThisPage[$form_edit_num_tab]['name'].">".$TblSetting["FormEditTab_".$form_edit_num_tab]["TabName"]."</li>";
				}
			}
		?>
	</ul>
	
	<!-- Основная -->
	<div <?php echo $arrTabThisPage[0]['page'];?>>
		<div id='inputArea'>
		<form name='form_edit_<?php echo $TblSetting["table"]['name']; ?>' id='form_edit_<?php echo $TblSetting["table"]['name']; ?>' method='post' action='<?php echo $EditActionLink; ?>' enctype='multipart/form-data' onsubmit="return validate_form_<?php echo $TblSetting["table"]['name']; ?>();">
		<?php include(GetIncFile($arrSetting,"inc.form.edit.fields.php", $TblSetting["table"]['name'])); ?>
		</form>
		</div>
	</div>	
	
	<!-- Файлы -->
<?php if($TblSetting["table"]['UseTableFileList'] == "1") { ?>
	<div <?php echo $arrTabThisPage[1]['page'];?>>
	<?php
		// /* прикрепить файл к записи */
		if($_GET[$TblFieldPrimaryKey] != 0){
			include(GetIncFile($arrSetting,"inc.form.edit.files.php", $TblSetting["table"]['name']));
		}
		else{
			echo Message("Редактирование списка файлов, возможно только после сохранения записи","error");
		}
	?>
	</div>
<?php } ?>
	
	<!-- Список -->
<?php if($TblSetting["table"]['UseTableList'] == "1") { ?>
	<div <?php echo $arrTabThisPage[2]['page'];?>>
	<?php
		// /* прирепить список */
		if($_GET[$TblFieldPrimaryKey] != 0){ 
			include(GetIncFile($arrSetting,"inc.form.edit.list.php", $TblSetting["table"]['name']));
		}
		else{
			echo Message("Редактирование списка, возможно только после сохранения записи","error");
		}
	?>
	</div>
<?php } ?>	
	
	<!-- таблицы пользователя -->
<?php if($TblSetting["table"]['UseTableUser'] == "1") { ?>
	<div <?php echo $arrTabThisPage[3]['page'];?>>
	<?php
		// /* прирепить список таблиц пользователя */
		if($_GET[$TblFieldPrimaryKey] != 0){ 
			include(GetIncFile($arrSetting,"inc.form.edit.user_table.php", $TblSetting["table"]['name']));
		}
		else{
			echo Message("Редактирование списка таблиц пользователя, возможно только после сохранения записи","error");
		}
	?>
	</div>
<?php } ?>	
	
	<?php
		for ($form_edit_num_tab = 4; $form_edit_num_tab <= $arrSetting['Other']['TabCount']; $form_edit_num_tab++){
			if($TblSetting["FormEditTab_".$form_edit_num_tab]["TabUse"]){
				echo "<div ".$arrTabThisPage[$form_edit_num_tab]['page'].">";
				if($_GET[$TblFieldPrimaryKey] != 0){ 
					$func_file = $arrSetting["Path"]["tbldata"]."/".$TblSetting["table"]["name"]."/tFunction/".$TblSetting["FormEditTab_".$form_edit_num_tab]["TabFunction"];
					if(file_exists($func_file) && is_file($func_file)){include($func_file);}
					else{
						echo Message("Обработчик вкладки < ".$TblSetting["FormEditTab_".$form_edit_num_tab]["TabName"]." >, не найден","error");
					}
					$func_file = "";
				}
				else{
					echo Message("Редактирование вкладки < ".$TblSetting["FormEditTab_".$form_edit_num_tab]["TabName"]." >, возможно только после сохранения записи","error");
				}				
				echo "</div>";
			}
		}
	?>
	
	</div>
	
	<div class='form_spr_list'>
	<?php include(GetIncFile($arrSetting,"inc.form.edit.spr_nav.php", $TblSetting["table"]['name'])); ?>	
	</div>
</div>