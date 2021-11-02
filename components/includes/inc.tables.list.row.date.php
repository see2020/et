<?php
// $ShowRow = $ut->utGetDate($TblSetting[$key]['dateformat'],$query[$TblSetting[$key]['name']]);
$ShowRow = ($query[$TblSetting[$key]['name']] == 0)?"-":$ut->utGetDate($TblSetting[$key]['dateformat'],$query[$TblSetting[$key]['name']]);
?>