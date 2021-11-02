<link rel="stylesheet" type="text/css" href="<?php echo $TblDefTplPath; ?>/js/autocomplete/content/styles.css" />
<script type="text/javascript" src="<?php echo $TblDefTplPath; ?>/js/autocomplete/src/jquery.autocomplete.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
<?php
	foreach($TblSetting["sortfieldsearch"] as $key => $val){
		if($TblSetting[$key]["for_search"] == 1 && $TblSetting[$key]["type"] != "support"){
			if($TblSetting[$key]["type"]=="directory_id"){
?>
		$('#<?php echo $TblSetting[$key]["name"]; ?>_show').autocomplete({
			serviceUrl: './aj.php?af=spr.autocomplete&tbl_spr=<?php echo $TblSetting[$key]["directory_table"];?>&field_type=<?php echo $TblSetting[$key]["type"];?>',
			paramName: 'query',
			onSelect: function (suggestion) {
				jsAddField("<?php echo $TblSetting[$key]["name"]; ?>",suggestion.primarykey);
			},
			minChars: 2,
			maxHeight: 250,
			width: 500,
			noCache: true
		});
<?php
			}
			if($TblSetting[$key]["type"]=="directory_name"){
?>
		$('#<?php echo $TblSetting[$key]['name']; ?>').autocomplete({
			serviceUrl: './aj.php?af=spr.autocomplete&tbl_spr=<?php echo $TblSetting[$key]["directory_table"];?>&field_type=<?php echo $TblSetting[$key]["type"];?>',
			paramName: 'query',
			minChars: 2,
			maxHeight: 250,
			width: 500,
			noCache: true
		});
<?php
			}
		}
	}
?>
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {
	<?php
		foreach($TblSetting["sortfieldsearch"] as $key => $val){
			if($TblSetting[$key]["for_search"] == 1 && $TblSetting[$key]["type"] != "support"){
				if($TblSetting[$key]["type"]=="date"){
?>
		jQuery("#<?php echo $TblSetting[$key]["name"];?>_start").dynDateTime({
			showsTime: false,
			//ifFormat: "%Y-%m-%d %H:%M:00", 
			ifFormat: "%Y-%m-%d 00:00:00", 
			align: "BL",
			electric: false,
			singleClick: true,
			firstDay: 1,
			button: ".next()" //next sibling
		});
		jQuery("#<?php echo $TblSetting[$key]["name"];?>_end").dynDateTime({
			showsTime: false,
			//ifFormat: "%Y-%m-%d %H:%M:00", 
			ifFormat: "%Y-%m-%d 23:59:59", 
			align: "DL",
			electric: false,
			singleClick: true,
			firstDay: 1,
			button: ".next()" //next sibling
		});
<?php
				}
			}
		}
	?>
	});
</script>
<script language="javascript">function increase(elId,val){ document.getElementById(elId).value = parseInt(document.getElementById(elId).value) + val;}</script>
