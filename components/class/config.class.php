<?php

	class config{
		private $cfgFile	 = "";
		private $cfgArray	 = array();
		private $cfgContent	 = "";
		public function __construct($config_file = ""){
			//parent::__construct();
			$this->cfgFile = $config_file;
		}
		
		public function init(){
			if(file_exists($this->cfgFile) && is_readable($this->cfgFile)){
				include($this->cfgFile);
				$this->cfgArray = $arrConfig;
			}
			else{
				$this->create();
			}
		}
		private function create(){
			$this->cfgArray	 = array("default"=>0);
			$this->upd();
		}
		public function upd(){
			$this->createContent();
			if($fp = fopen($this->cfgFile,"a+")){
				flock($fp,LOCK_EX);
				ftruncate($fp,0);
				fputs($fp,$this->cfgContent);
				fflush($fp);
				flock($fp,LOCK_UN);
				fclose($fp);	
				return(TRUE);
			}else{
				return(FALSE);
			}
		}
		private function createContent(){
			$this->cfgContent = "";
			$this->cfgContent.= "<?php\n";
			$this->cfgContent.= "";
			foreach ($this->cfgArray as $key => $val){
				if(!is_array($val)){
					$this->cfgContent.= "\$arrConfig['".$key."'] = '".$val."';\n";
				}
				else{
					foreach ($val as $key1 => $val1){
						//if(!is_array($val1)){
							$this->cfgContent.= "\$arrConfig['".$key."']['".$key1."'] = '".$val1."';\n";
						// }
						// else{
							// foreach ($val1 as $key2 => $val2){
								// $this->cfgContent.= "\$arrConfig['".$key."']['".$key1."']['".$key2."'] = '".$val2."';\n";
							// }
						// }
					}
				}
			}
			$this->cfgContent.= "\n?>";
		}
		
		public function setSection($section = "", $arr = array()){
			if(count($arr) > 0){
				reset($arr);
				foreach ($arr as $key => $val){
					$this->set($section, $key, $val);
				}
			}
		}
		public function getSection($section = ""){
			if($section != ""){
				return($this->cfgArray[$section]);
			}
			else{
				return($this->cfgArray);
			}
		}
		public function eraseSection($section = ""){
			if(isset($this->cfgArray[$section])){unset($this->cfgArray[$section]);}
		}
		
		public function set($section = "",$key = "", $val = ""){
			$val = strtr($val,array("'"=>"&prime;","\""=>"&quot;",));
			if($section != ""){
				$this->cfgArray[$section][$key] = $val;
			}
			else{
				$this->cfgArray[$key] = $val;
			}
		}
		public function get($section = "",$key = ""){
			if($section != ""){
				return($this->cfgArray[$section][$key]);
			}
			else{
				return($this->cfgArray[$key]);
			}
		}
		public function erase($section = "", $key = ""){
			unset($this->cfgArray[$section][$key]);
		}

		public function ElementExist($section = "", $key = ""){
			if($section == ""){return(FALSE);}
			elseif($section == "" && $key == ""){return(FALSE);}
			else{
				if($section != "" && $key == ""){
					if(isset($this->cfgArray[$section])){return(TRUE);}else{return(FALSE);}
				}
				elseif($section != "" && $key != ""){
					if(isset($this->cfgArray[$section][$key])){return(TRUE);}else{return(FALSE);}
				}
				else{return(FALSE);}
			}
		}
		
	}
?>