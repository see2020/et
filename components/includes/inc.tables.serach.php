<?php
// обработка формы поиска
		// поиск по текущей таблице
		if($_GET['event']=="serach"){
			if(isset($_POST['Search'])){
				$SrchLink		 = "&srch=1";
				$AllFieldSrch	 = "";
				if(isset($_POST['allfield'])){
					if($_POST['allfield'] != ""){
						$AllFieldSrch = trim($_POST['allfield']);
					}
				}
//?tbl=tst&srch=1&field_2_tp=0&field_2=0&field_dt_tp=10&field_dt_start=1970-01-01%2000:00:00&field_dt_end=2016-09-14%2023:59:59
				if($AllFieldSrch == ""){
					foreach($TblSetting["sortfieldsearch"] as $key => $val){
						if($TblSetting[$key]["type"] != "support" && $TblSetting[$key]["for_search"] == "1"){
							//if(isset($_POST[$TblSetting[$key]["name"]]) && $_POST[$TblSetting[$key]["name"]] != "" || ($_POST[$TblSetting[$key]["name"]."_start"] != "" && $_POST[$TblSetting[$key]["name"]."_end"] != "")){
							if($_POST[$TblSetting[$key]["name"]."_tp"] != "" && $_POST[$TblSetting[$key]["name"]."_tp"] != 0){
								if($TblSetting[$key]["type"] == "date"){
									$SrchLink.= "&".$TblSetting[$key]["name"]."_tp="	.$_POST[$TblSetting[$key]["name"]."_tp"];
									$SrchLink.= "&".$TblSetting[$key]["name"]."_start="	.$_POST[$TblSetting[$key]["name"]."_start"];
									$SrchLink.= "&".$TblSetting[$key]["name"]."_end="	.$_POST[$TblSetting[$key]["name"]."_end"];
								}
								else{
									$SrchLink.= "&".$TblSetting[$key]["name"]."_tp=".$_POST[$TblSetting[$key]["name"]."_tp"];
									$SrchLink.= "&".$TblSetting[$key]["name"]."="	.$_POST[$TblSetting[$key]["name"]];
								}
							}
						}
					}
				}
				else{
					$SrchLink.= "&allfield=".$AllFieldSrch;
				}
				Redirect("?tbl=".$TblSetting["table"]["name"].$SrchLink."",0);
			}
			if(isset($_POST['NoSearch'])){Redirect("?tbl=".$TblSetting["table"]["name"]."",0);}

			//include(GetIncFile($arrSetting,"inc.form.search.php", $TblSetting["table"]["name"]));
			include(GetIncFile($arrSetting,"inc.tables.serach.form.php", $TblSetting["table"]["name"]));

		}
		


?>