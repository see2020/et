<?php
/**
 * data_crop.php - обрезаем содержимое поля в списке
 */
	// $ShowRow = "<div style='height: 50px;width: 100%;overflow: hidden;margin: 0;padding: 0;'>".str_replace("\r\n", "<br>", $query['a_descr'])."</div>";
	$ShowRow = "<div style='height: 120px;width: 100%;overflow-y: scroll;margin: 0;padding: 0;'>".str_replace("\r\n", "<br>", $query[$TblSetting[$key]['name']])."</div>";
?>

