<?php
$ShowRow = ($query[$TblSetting[$key]['name']] == 0)?"-":$ut->utGetDate($TblSetting[$key]['dateformat'],$query[$TblSetting[$key]['name']]);
?>