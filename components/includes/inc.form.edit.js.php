<link rel="stylesheet" type="text/css" href="<?php echo $TblDefTplPath; ?>/js/autocomplete/content/styles.css" />
<script type="text/javascript" src="<?php echo $TblDefTplPath; ?>/js/autocomplete/src/jquery.autocomplete.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
<?php
	foreach($TblSetting["sortfieldform"] as $key => $val){
		if($TblSetting[$key]["editable"] == 1 && $TblSetting[$key]["type"] != "support"){
			if($TblSetting[$key]['type']=='directory_id'){
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
		$('#<?php echo $TblSetting[$key]["name"]; ?>').autocomplete({
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
	$(".form_edit").show();
	$(".form_show").hide();
	$(".form_hide").hide();
<?php
if($TblSetting["table"]["is_directory"] == "1" && $qChange[$TblSetting[$TblSetting["table"]["directory_type"]]["name"]] == "1"){
?>
	$(".form_edit").hide();
	$(".form_show").show();
	$(".form_hide").hide();
	
	$('.form_sh').click(function(){
		$(".form_edit").show();
		$(".form_hide").show();
		$(".form_show").hide();
		$(".form_spr_list").hide();
	});
	$('.form_hd').click(function(){
		$(".form_edit").hide();
		$(".form_hide").hide();
		$(".form_show").show();
		$(".form_spr_list").show();
	});
	
<?php
}
?>
	});
	function increase(elId,val){ document.getElementById(elId).value = parseInt(document.getElementById(elId).value) + val;}
	
	function validate_form_<?php echo $TblSetting["table"]['name']; ?>(){
		var valid = true;
		var str_field = "";
		
<?php
	// текстовые поля
	$v_txt = array("text","textarea","link","directory_name","file","image","password","list_string","list_link");
	// поля с числовыми значениями
	$v_num = array("number","date","directory_id");
?>


<?php
	foreach($TblSetting["sortfieldform"] as $key => $val){
		if($TblSetting[$key]["required"]){
			if(in_array($TblSetting[$key]["type"],$v_txt)){
				//echo "// ".$key."-".$TblSetting[$key]["required"].";";	
?>
        if (document.getElementById('<?php echo $TblSetting[$key]['name']; ?>').value == '' ){
			str_field = str_field + '<?php echo $TblSetting[$key]['description']; ?>' + ', ';
			valid = false;
        }
<?php			
			}
			
			if(in_array($TblSetting[$key]["type"],$v_num)){
?>
        if (document.getElementById('<?php echo $TblSetting[$key]['name']; ?>').value == '0'){
			str_field = str_field + '<?php echo $TblSetting[$key]['description']; ?>' + ', ';
			valid = false;
        }
<?php			
			}
		}
	}
// \\r\\n
?>
		if(!valid){
			alert('Необходимо заполнить поля: ' + str_field);
		}

        return valid;
	}
	
</script>
