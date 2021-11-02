<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// CMS: BLD - 2011
// файл: file.php
// класс: class_file
// автор: Иванцов Денис Владимирович 
// Класс для работы с файловой системой и файлами
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
//defined('_BLDEXEC') or die('Restricted access');
class class_file{
	//функция переписывает файл новыми данными не меняя имени файла
	//если файл отсутсвует пытается его создать
	// $f_name - путь с именем файла на сервере, путь необходимо указывать относительно сервера полностью
	// $f_content - контентент которым будет переписан файл
	function fRewrite($f_name='',$f_content=''){
		if($fp = fopen($f_name,"a+")){
			flock($fp,LOCK_EX);//блокировка файла
			ftruncate($fp,0);//УДАЛЯЕМ СОДЕРЖИМОЕ ФАЙЛА
			fputs($fp,$f_content);
			fflush($fp);//очищение файлового буфера и запись в файл
			flock($fp,LOCK_UN);//снятие блокировки
			fclose($fp);	
			return(true);
		}else{
			return(false);
		}
	}

	//функция дописывания в файл
	// если файла не существует будет предпринята попытка создать его
	// $f_name - путь с именем файла на сервере, путь необходимо указывать относительно сервера полностью
	// $f_string - строка которая добавляется в конец файла
	function fAddLine($f_name='',$f_string=''){
		if($fp = fopen($f_name,"a+")){
			flock($fp,LOCK_EX);
			fputs($fp,$f_string);
			fflush($fp);
			flock($fp,LOCK_UN);
			fclose($fp);
			return(true);
		}else{
			return(false);
		}
	}

	//функция получает содержимое файла ввиде строки
	// $f_name - путь с именем файла на сервере, путь необходимо указывать относительно сервера полностью
	// $f_compression - если указать этот парметр будет предпринята попытка извлечь информацию из сжатого файла
	// $buffer - возвращает контент ввиде строки или пустое значение
	function fGetContent($f_name,$f_compression=""){
		$buffer			 = "";
		$f_compression	 = strtolower(trim($f_compression));
		if(file_exists($f_name)){
			if($f_compression==""){
				$fd = fopen ($f_name, "r");
				while (!feof ($fd)){
					$buffer.= fgets($fd, 4096);
				}
				fclose ($fd);
			}
			elseif($f_compression=="zlib"){
				$gzfd = gzopen($f_name, 'r');
				while (!gzeof($gzfd)){
				   $buffer.= gzgets($gzfd, 4096);
				}
				gzclose($gzfd);
			}
		}
		return($buffer);
	}

	// функция удаления файла
	// в качестве парметра передается полный путь до файла + имя файла
	function fDelFile($f_name){
		if(file_exists($f_name)){
			@chmod($f_name, 0777);  //пытаемся установить полные права доступа на файл перед удалением
			if(!@unlink($f_name)){return(false);}else {return(true);}
		}else{return(true);}
	}
	
	// функция удаления пустой директории
	// в качестве парметра передается полный путь до директории
	function fDelDir($f_name){
		if(is_dir($f_name)){
			@chmod($f_name, 0777);  //пытемся установить полные права доступа на файл перед удалением
			if(!rmdir($f_name)){return(false);}else{return(true);}
		}else{return(true);}
	}
	
	// Удаляем все дерево в заданой папке
	var $fDelDirTree_f = array();
	var $fDelDirTree_d = array();
	function fDelDirTree($var_temp_dir){
		$spp = $sp;
		if(is_dir($var_temp_dir)){
			$dh0=opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if(is_dir($var_temp_dir."/".$filename0) && $filename0 != "." && $filename0 != ".." && $filename0 != ""){
					//выводим файлы
					$dh1 = opendir($var_temp_dir."/".$filename0);
					while(false!==($filename1 = readdir($dh1))){
						if(is_file($var_temp_dir."/".$filename0."/".$filename1) && $filename1 != "." && $filename1 != ".." && $filename1 != ""){
							$this->fDelDirTree_f[] = $var_temp_dir."/".$filename0."/".$filename1;
						}
					}
					$this->fDelDirTree($var_temp_dir."/".$filename0);//выводим подпапки
					$this->fDelDirTree_d[] = $var_temp_dir."/".$filename0;
				}
			}
		}
		return($spp);
	}
	
	function fDelDirTreeShow($var_temp_dir){
		if(is_dir($var_temp_dir)){
			$dh0 = opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if(is_file($var_temp_dir."/".$filename0) && $filename0 != "." && $filename0 != ".." && $filename0 != ""){
					$this->fDelDirTree_f[] = $var_temp_dir."/".$filename0;
				}
			}
			$this->fDelDirTree($var_temp_dir);
		}
	}	
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Списки файлов и директорий
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// права доступа к файлам и папкам
	// в качестве параметра передается путь дофайла на сервере /home/b2xx/www/text.txt
	// возвращает строку вида -rwxrwxrwx
	function fFolderFileAccess($ff_name=""){
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//смотрим права доступа к файлу
		$perms = fileperms($ff_name);

		// Сокет
		if (($perms & 0xC000) == 0xC000) {$AccessRight = 's';} 
		// Символическая ссылка
		elseif (($perms & 0xA000) == 0xA000) {$AccessRight = 'l';} 
		// Обычный
		elseif (($perms & 0x8000) == 0x8000) {$AccessRight = '-';} 
		// Специальный блок
		elseif (($perms & 0x6000) == 0x6000) {$AccessRight = 'b';} 
		// Директория
		elseif (($perms & 0x4000) == 0x4000) {$AccessRight = 'd';} 
		// Специальный символ
		elseif (($perms & 0x2000) == 0x2000) {$AccessRight = 'c';} 
		// Поток FIFO
		elseif (($perms & 0x1000) == 0x1000) {$AccessRight = 'p';} 
		// Неизвестный
		else {$AccessRight = 'u';}
		
		// Владелец
		$AccessRight .= (($perms & 0x0100) ? 'r' : '-'); // чтение 
		$AccessRight .= (($perms & 0x0080) ? 'w' : '-'); // запись 
		$AccessRight .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-')); // не помню :)
		// Группа
		$AccessRight .= (($perms & 0x0020) ? 'r' : '-');
		$AccessRight .= (($perms & 0x0010) ? 'w' : '-');
		$AccessRight .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
		// прочие
		$AccessRight .= (($perms & 0x0004) ? 'r' : '-');
		$AccessRight .= (($perms & 0x0002) ? 'w' : '-');
		$AccessRight .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return($AccessRight);
	}
	
	// получаем размер файла
	//$f_path - путь+имя файла
	//$accuracy - сколько знаков в дробной части
	//$type - в каком виде показывать в байта(b), килобайтах(kb), мегабайтах(mb), гигабайтах(gb)
	function fGetFileSize($f_path,$accuracy=3,$type="kb"){
		$size['size'] = 0;
		$size['type'] = "";
		if(file_exists($f_path)){
			$OsName = strtolower(php_uname());
			if(strrpos($OsName, 'windows') !== false){
				exec('FOR %A IN ("'.str_replace('/','\\',$f_path).'") DO @ECHO %~zA',$r);
				$fSize = $r[count($r)-1];
			}elseif(strrpos($OsName, 'freebsd') !== false){
				exec('ls -la '.$f_path,$r);
				$r1 =  explode(" ",$r[count($r)-1]);
				$fSize = $r1[7]; // здесь получается размер файла в байтах
			}
			else{
				$fSize = filesize($f_path); // получает размер до 2х Гб
			}
			
// exec('dir "'.str_replace('/','\\',$fname).'"',$r);
// $r = implode("\r\n",$r);
// $r = str_replace("\xFF",' ',$r);
// preg_match('/[\d]{2,2}\.[\d]{2,2}\.[\d]{2,4}[\s]+[\d]{2,2}\:[\d]{2,2}[\s]+([\d\s]+)/im',$r,$m);
// if (!isset($m[1])) return false;
// $size = str_replace(' ','',$m[1]);
			
			$accuracy = (int)$accuracy;
			$type = strtolower($type);
			if($type == "kb"){
				//$sz = ; // размер файла в Kb
				$size['size'] = round($fSize/1024,$accuracy);
				$size['type'] = "Kb";
			}
			elseif($type == "mb"){
				//$sz = ; // размер файла в mb
				$size['size'] = round($fSize/1024/1024,$accuracy);
				$size['type'] = "Mb";
			}
			elseif($type == "gb"){
				//$sz = $fSize/1024/1024/1024; // размер файла в gb
				$size['size'] = round($fSize/1024/1024/1024,$accuracy);
				$size['type'] = "Gb";
			}
			else{
				//$sz = $fSize; // размер файла в b
				$size['size'] = $fSize;
				$size['type'] = "b";
			}
		}else{
			$size['size'] = 0;
			$size['type'] = "b";
		}
		return ($size);
	}
	
	var $fNotShowFile = 1; // дополнительные файлы которые не надо паказывать в списке 1 - надо, 0 - не надо
	
	//выводим все файлы в поддиректориях выбранной директории. возвращает массив fListFiles[индекс элемента][название парметра]= путь или имя файла
	var $fListFiles	 = array();
	var $fLFCount	 = 0;
	function fFolderListFiles($var_temp_dir,$var_temp_dir_http){
		$NotShowFileListArray[]	 = "";
		$NotShowFileListArray[]	 = ".";
		$NotShowFileListArray[]	 = "..";
		$NotShowFileListArray1	 = array();
		if($this->fNotShowFile == 0){
			$NotShowFileListArray1[] = "index.php";
			$NotShowFileListArray1[] = "index.html";
			$NotShowFileListArray1[] = "index.htm";
		}
		if(is_dir($var_temp_dir)){
			$dh0 = opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if(is_dir($var_temp_dir."/".$filename0) && !in_array($filename0,$NotShowFileListArray) && !in_array($filename0,$NotShowFileListArray1)){
					//выводим файлы
					$dh1 = opendir($var_temp_dir."/".$filename0);
					while(false!==($filename1 = readdir($dh1))){
						if(is_file($var_temp_dir."/".$filename0."/".$filename1) && !in_array($filename1,$NotShowFileListArray) && !in_array($filename1,$NotShowFileListArray1)){
							$this->fListFiles[$this->fLFCount]["path"]	 = $var_temp_dir_http."/".$filename0;
							$this->fListFiles[$this->fLFCount]["server"] = $var_temp_dir."/".$filename0;
							$this->fListFiles[$this->fLFCount]["file"]	 = $filename1;
							//получаем размер файла, знаки после точки ОБРЕЗАЕМ до 3-х
							$size = $this->fGetFileSize($var_temp_dir."/".$filename0."/".$filename1,3,"kb");
							$this->fListFiles[$this->fLFCount]["size"]	 = $size['size']; //размер файла в Kb, точность посл точки 3
							// получаем права к файлу или директории
							$this->fListFiles[$this->fLFCount]["right"]	 = $this->fFolderFileAccess($var_temp_dir."/".$filename0."/".$filename1);
							$this->fListFiles[$this->fLFCount]["time"]	 = filemtime($var_temp_dir."/".$filename0."/".$filename1);
							$this->fLFCount++; // накручиваем счетчик
						}
					}		
					$this->fFolderListFiles($var_temp_dir."/".$filename0,$var_temp_dir_http."/".$filename0);//выводим подпапки
				}
			}		
		}else{return(false);}
		return(true);
	}
	
	//выводим все файлы в директории и дочерних поддиректориях. возвращает массив fListFiles[индекс элемента][название парметра]= путь или имя файла
	// $OneLevel если поставить значение true, то не выводит файлы в дочерних папках
	function fListFiles($var_temp_dir,$var_temp_dir_http,$OneLevel=false){
		$NotShowFileListArray[]	 = "";
		$NotShowFileListArray[]	 = ".";
		$NotShowFileListArray[]	 = "..";
		$NotShowFileListArray1	 = array();
		if($this->fNotShowFile == 0){
			$NotShowFileListArray1[] = "index.php";
			$NotShowFileListArray1[] = "index.html";
			$NotShowFileListArray1[] = "index.htm";
		}
		unset($this->fListFiles);//чистим массив
		if(is_dir($var_temp_dir)){
			$dh0 = opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if (is_file($var_temp_dir."/".$filename0) && !in_array($filename0,$NotShowFileListArray) && !in_array($filename0,$NotShowFileListArray1)){
					$this->fListFiles[$this->fLFCount]["path"]	 = $var_temp_dir_http;
					$this->fListFiles[$this->fLFCount]["server"] = $var_temp_dir;
					$this->fListFiles[$this->fLFCount]["file"]	 = $filename0;
					//получаем размер файла, знаки после точки ОБРЕЗАЕМ до 3-х
					$size = $this->fGetFileSize($var_temp_dir."/".$filename0,3,"kb");
					$this->fListFiles[$this->fLFCount]["size"]	 = $size['size']; //размер файла в kb, точность посл точки 3
					// получаем права к файлу или директории
					$this->fListFiles[$this->fLFCount]["right"]	 = $this->fFolderFileAccess($var_temp_dir."/".$filename0);
					//время последнего изменения
					$this->fListFiles[$this->fLFCount]["time"]	 = filemtime($var_temp_dir."/".$filename0);
					$this->fLFCount++; // накручиваем счетчик
				}
			}	
			if(!$OneLevel){$this->fFolderListFiles($var_temp_dir,$var_temp_dir_http);}
			if(isset($this->fListFiles)){
				asort($this->fListFiles);
				reset($this->fListFiles);
			}
		}else{return(false);}
		return(true);
	}	

	//Получаем под директории
	var $fListFolders=array();
	var $fLFlCount=0;
	function fListSubFolders($var_temp_dir,$var_temp_dir_http){
		$NotShowFileListArray[]	 = "";
		$NotShowFileListArray[]	 = ".";
		$NotShowFileListArray[]	 = "..";
		$NotShowFileListArray1	 = array();
		if($this->fNotShowFile == 0){
			$NotShowFileListArray1[] = "index.php";
			$NotShowFileListArray1[] = "index.html";
			$NotShowFileListArray1[] = "index.htm";
		}
		if(is_dir($var_temp_dir)){
			$dh0 = opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if(is_dir($var_temp_dir."/".$filename0) && !in_array($filename0,$NotShowFileListArray) && !in_array($filename0,$NotShowFileListArray1)){
					$this->fListFolders[$this->fLFlCount]["path"]	 = $var_temp_dir_http."/".$filename0;
					$this->fListFolders[$this->fLFlCount]["server"]	 = $var_temp_dir."/".$filename0;
					$this->fListFolders[$this->fLFlCount]["file"]	 = $filename1;
					// получаем права к файлу или директории
					$this->fListFolders[$this->fLFlCount]["right"]	 = $this->fFolderFileAccess($var_temp_dir."/".$filename0);
					$this->fListFolders[$this->fLFlCount]["size"]	 = "";
					$this->fListFolders[$this->fLFlCount]["time"]	 = filemtime($var_temp_dir."/".$filename0);
					$this->fLFlCount++; // накручиваем счетчик
					$this->fListSubFolders($var_temp_dir."/".$filename0,$var_temp_dir_http."/".$filename0);//выводим подпапки
				}
			}		
		}else{return(false);}
		return(true);
	}	
	//выводим все поддиректороии в заднной
	// $OneLevel если поставить значение true, то выводит только первый уровень вложенности
	function fListFolders($var_temp_dir,$var_temp_dir_http,$OneLevel=false){
		$NotShowFileListArray[]	 = "";
		$NotShowFileListArray[]	 = ".";
		$NotShowFileListArray[]	 = "..";
		$NotShowFileListArray1	 = array();
		if($this->fNotShowFile == 0){
			$NotShowFileListArray1[] = "index.php";
			$NotShowFileListArray1[] = "index.html";
			$NotShowFileListArray1[] = "index.htm";
		}
		unset($this->fListFolders);//чистим массив
		if(is_dir($var_temp_dir)){
			$dh0 = opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if (is_dir($var_temp_dir."/".$filename0) && !in_array($filename0,$NotShowFileListArray) && !in_array($filename0,$NotShowFileListArray1)){
					$this->fListFolders[$this->fLFlCount]["path"]	 = $var_temp_dir_http;
					$this->fListFolders[$this->fLFlCount]["server"]	 = $var_temp_dir;
					$this->fListFolders[$this->fLFlCount]["file"]	 = $filename0;
					// получаем права к файлу или директории
					$this->fListFolders[$this->fLFlCount]["right"]	 = $this->fFolderFileAccess($var_temp_dir."/".$filename0);
					$this->fListFolders[$this->fLFlCount]["size"]	 = 0;
					$this->fListFolders[$this->fLFlCount]["time"]	 = filemtime($var_temp_dir."/".$filename0);
					$this->fLFlCount++; // накручиваем счетчик
				}
			}	
			if(!$OneLevel){$this->fListSubFolders($var_temp_dir,$var_temp_dir_http);}
			if(isset($this->fListFolders)){
				asort($this->fListFolders);
				reset($this->fListFolders);
			}
		}else{return(false);}
		return(true);
	}	

	//считаем сколько папок и файлов в директории
	// возвращаем массив
	//var $fCountObjectByFolder=array();
	function fCountObjectByFolder($var_temp_dir)
	{
		$fCountObjectByFolder['files']	 = 0;
		$fCountObjectByFolder['folders'] = 0;
		
		$NotShowFileListArray[] = "";
		$NotShowFileListArray[] = ".";
		$NotShowFileListArray[] = "..";
		if(is_dir($var_temp_dir)){
			$dh0 = opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if(is_file($var_temp_dir."/".$filename0) && !in_array($filename0,$NotShowFileListArray)){
					$fCountObjectByFolder['files']++;
				}
				if(is_dir($var_temp_dir."/".$filename0) && !in_array($filename0,$NotShowFileListArray)){
					$fCountObjectByFolder['folders']++;
				}
			}
		}
		return($fCountObjectByFolder);
	}	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// выводим дерево каталогов 
	
	// Разделитель
	// $sp - сколько раз повторять разделитель
	var $fTreeSep = " - "; // вид разделителя
	function fTreeSep($sp=1){$sp_count="";for($i=0; $i < $sp; $i++){$sp_count.=$this->fTreeSep;}return($sp_count);}

	//выводим дерево каталога
	var $fTreeShow = "";
	function fTree($var_temp_dir,$sp=1,$level=0){
		$spp = $sp;
		if(is_dir($var_temp_dir)){
			$dh0=opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if(is_dir($var_temp_dir."/".$filename0) && $filename0 != "." && $filename0 != ".." && $filename0 != ""){
					// считаме сколько файлов и папок в текущей директории
					$fCountObjectByFolder = $this->fCountObjectByFolder($var_temp_dir."/".$filename0);
					//выводим папку
					$this->fTreeShow.= "<br>";
					$this->fTreeShow.= "<span style='color:blue'>";
					$this->fTreeShow.= $this->fTreeSep($sp)."[ <a href='".$_SERVER['PHP_SELF']."?action=files&d=".$var_temp_dir."/".$filename0."' style='color:blue'>
					<b>".$filename0."</b></a> ]
					&nbsp;<i>info: d ".$fCountObjectByFolder['folders'].", f ".$fCountObjectByFolder['files']."</i>";
					$this->fTreeShow.= "</span><br>";
					
					//пытаемся создать файл index.htm
					if(!file_exists($var_temp_dir."/".$filename0."/index.htm")){$this->fAddLine($var_temp_dir."/".$filename0."/index.htm"," ");}
					
					//выводим файлы
					$dh1 = opendir($var_temp_dir."/".$filename0);
					while(false!==($filename1 = readdir($dh1))){
						if(is_file($var_temp_dir."/".$filename0."/".$filename1) && $filename1 != "." && $filename1 != ".." && $filename1 != ""){
							//получаем размер файла, знаки после точки ОБРЕЗАЕМ до 3-х
							$fsize = $this->fGetFileSize($var_temp_dir."/".$filename0."/".$filename1);					
							$this->fTreeShow.= "<span style='color:green'>";
							$this->fTreeShow.= $this->fTreeSep($sp+1);
							$this->fTreeShow.= " [ <a href='".$_SERVER['PHP_SELF']."?action=files&d=".$var_temp_dir."/".$filename0."&f=".$filename1."&a=edit' style='color:green'>".$filename1."</a> ]";
							$this->fTreeShow.= " [ ".(($fsize['size']>1024)?round($fsize['size']/1024,3)."&nbsp;Mb":$fsize['size']."&nbsp;Kb")." ] ";
							$this->fTreeShow.= " [ ".date("Y-m-d H:i:s",filemtime($var_temp_dir."/".$filename0))." ] ";
							$this->fTreeShow.= "</span><br>";
						}
					}
					$spp = $sp+1;//отступ
					$spp = $this->fTree($var_temp_dir."/".$filename0,$spp);//выводим подпапки
				}
			}
		}
		return($spp);
	}
	function fTreeShow($var_temp_dir){
		$this->fTreeShow = "";
		if(is_dir($var_temp_dir)){
			//пытаемся создать файл index.htm
			if(!file_exists($var_temp_dir."/index.htm")){$this->fAddLine($var_temp_dir."/index.htm"," ");}
			
			$dh0 = opendir($var_temp_dir);
			while(false!==($filename0 = readdir($dh0))){
				if(is_file($var_temp_dir."/".$filename0) && $filename0 != "." && $filename0 != ".." && $filename0 != ""){
					//получаем размер файла,
					$fsize = $this->fGetFileSize($var_temp_dir."/".$filename0);
					$this->fTreeShow.= "<span style='color:green'>";
					$this->fTreeShow.= $this->fTreeSep(1);
					$this->fTreeShow.= " [ <a href='".$_SERVER['PHP_SELF']."?action=files&d=".$var_temp_dir."&f=".$filename0."&a=edit' style='color:green'>".$filename0."</a> ]";
					$this->fTreeShow.= " [ ".(($fsize['size']>1024)?round($fsize['size']/1024,3)."&nbsp;Mb":$fsize['size']."&nbsp;Kb")." ] ";
					$this->fTreeShow.= " [ ".date("Y-m-d H:i:s",filemtime($var_temp_dir."/".$filename0))." ]";
					$this->fTreeShow.= "</span>";
					//$this->fTreeShow.= "</span>";
					$this->fTreeShow.= "<br>";
					//$this->fTreeShow.= "<br>";
				}
			}
			$this->fTree($var_temp_dir);
		}
	}	

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// GПрименяем сжатие к фйлам
	// f_path_in - файл который надо сжать
	// f_path_out - что получится после сжатия
	// f_content - если нет исходного файл и мы сжимаем только контенти ложим его в файл
	// f_type_Compression - метот сжатия zlib, zip
	function fFileCompression($f_path_in,$f_path_out,$f_content="",$f_type_Compression="zlib"){
		if($f_path_out==""){return false;}
		
		$f_content			 = trim($f_content);
		$f_type_Compression	 = strtolower($f_type_Compression);
		
		if($f_type_Compression=="zlib"){
			//делаем проверку на наличие модуля php zlib
			$ArrPHPExtensions = get_loaded_extensions();
			//сжимаем файл если есть zlib
			if(in_array("zlib",$ArrPHPExtensions)){
				if($f_content==""){
					$TempFileContent = $this->fGetContent($f_path_in);
					if($TempFileContent != ""){
						$gz_string_fname = gzopen($f_path_out.".gz", "w9"); // w9 максимальное сжатие
						gzwrite($gz_string_fname, $TempFileContent);
						gzclose($gz_string_fname); // закрыть файл 
						return true;
					}else{return false;}
				}
				else{
					$gz_string_fname = gzopen($f_path_out.".gz", "w9"); // w9 максимальное сжатие
					gzwrite($gz_string_fname, $f_content);
					gzclose($gz_string_fname); // закрыть файл 
					return true;
				}
			}else{return false;}
		}
		else{return false;}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//получаем MIME тип файла
	function fGetMime($f_name){
		if(!function_exists('mime_content_type')) {
			$mime_types = array(
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript', //"application/x-javascript"
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',

				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',

				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'tar' => 'application/x-tar',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',

				// audio/video
				'mp3' => 'audio/mpeg',
				'wav' => 'audio/wav',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',
				'avi'  =>  'video/msvideo',
				'wmv'  =>  'video/x-ms-wmv',
				'mpeg' => 'video/mpeg',
				'mpg' => 'video/mpeg',
				'mpe' => 'video/mpeg',
				
				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',

				// ms office
				'doc' => 'application/msword',
				'docx' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				// 'xlt' => 'application/vnd.ms-excel',
				// 'xlm' => 'application/vnd.ms-excel',
				// 'xld' => 'application/vnd.ms-excel',
				// 'xla' => 'application/vnd.ms-excel',
				// 'xlc' => 'application/vnd.ms-excel',
				// 'xlw' => 'application/vnd.ms-excel',
				// 'xll' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
				'pps' => 'application/vnd.ms-powerpoint',
				
				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);

			$ext = strtolower(array_pop(explode('.',$f_name)));
			if (array_key_exists($ext, $mime_types)) {
				return $mime_types[$ext];
			}
			elseif (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME);
				$mimetype = finfo_file($finfo, $f_name);
				finfo_close($finfo);
				return $mimetype;
			}
			else {
				return ('application/octet-stream');
			}
		}else{
			return(mime_content_type($f_name));
		}
	}
	
	
	//функция закачики файла клиенту fGetMime($f_name)
	function fDownload($f_name, $mimetype = 'application/octet-stream'){
		if(file_exists($f_name)){
			// Отправляем требуемые заголовки
			header($_SERVER["SERVER_PROTOCOL"].' 200 OK');
			// Тип содержимого. Может быть взят из заголовков полученных от клиента
			// при закачке файла на сервер. Может быть получен при помощи расширения PHP Fileinfo.
			header('Content-Type: '.$mimetype);
			// Дата последней модификации файла 
			header('Last-Modified: '.gmdate('r', filemtime($f_name)));
			// Отправляем уникальный идентификатор документа, 
			// значение которого меняется при его изменении. 
			// В нижеприведенном коде вычисление этого заголовка производится так же,
			// как и в программном обеспечении сервера Apache
			header('ETag: '.sprintf('%x-%x-%x', fileinode($f_name), filesize($f_name), filemtime($f_name)));
			//$FSize = $this->fGetFileSize($f_name,20,"b")
			//header('ETag: '.sprintf('%x-%x-%x', fileinode($f_name), $FSize['size'], filemtime($f_name)));
			// Размер файла
			header('Content-Length: '.(filesize($f_name)));
			//header('Content-Length: '.$FSize['size']);
			header('Connection: close');
			// Имя файла, как он будет сохранен в браузере или в программе закачки.
			// Без этого заголовка будет использоваться базовое имя скрипта PHP.
			// Но этот заголовок не нужен, если вы используете mod_rewrite для
			// перенаправления запросов к серверу на PHP-скрипт
			header('Content-Disposition: attachment; filename="'.basename($f_name).'";');
			// Открываем искомый файл
			$f = fopen($f_name, 'r');
			while(!feof($f)){
				// Читаем килобайтный блок, отдаем его в вывод и сбрасываем в буфер
				echo fread($f, 1024);
				flush();
			}
			// Закрываем файл
			fclose($f);
			return(true);
		} 
		else {
			header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
			header('Status: 404 Not Found');
			return(false);
		}
		return(false);
	}
	

	// новое имя файла если заданое уже существует
	function fFileName($Path,$FName){
	
		$FArr = array();
		$ext = strrpos($FName, '.');
		$FArr['name_a'] = substr($FName, 0, $ext);
		$FArr['name_a'] = strtr($FArr['name_a'],array(" "=>"_"));
		
		$FArr['name_b'] = strtolower(substr($FName, $ext));
		if(file_exists($Path."/".$FArr['name_a'].$FArr['name_b'])){
			$count = 1;
			while (file_exists($Path."/".$FArr['name_a'].'('.$count.')'.$FArr['name_b'])){
				$count++;
			}
			$FArr['name'] = $FArr['name_a'].'('.$count.')'.$FArr['name_b'];
		}else{
			$FArr['name'] = $FArr['name_a'].$FArr['name_b'];
		}
		$FArr['path'] = $Path;
		return($FArr);
	
	}
	// новое имя папки если заданое уже существует
	function fDirName($Path,$FName){
		$FArr = array();
		
		$FName = strtolower($FName);
		$FName = strtr($FName,array(" "=>"_"));
		
		if(is_dir($Path."/".$FName.'('.$count.')')){
			$count = 1;
			while (is_dir($Path."/".$FName.'('.$count.')')){
				$count++;
			}
			$FArr['name'] = $FName.'('.$count.')';
		}else{
			$FArr['name'] = $FName;
		}
		
		$FArr['path'] = $Path;
		return($FArr);
	}
	
}//class_file

class class_ini{
	//INI файлы
	var $fINIFileName	 = ""; // файл с настройками. полный путь до файла
	var $fINIArray		 = array(); // в это массив будем считвать содержимое ini файла
	// читаем ini файл в массив
	function fINIInitArray(){
		if(file_exists($this->fINIFileName) && is_readable($this->fINIFileName)){
			$this->fINIArray = parse_ini_file($this->fINIFileName, true);
			return(true);
		}else{return(false);}
	}
	// считываем настройки из файла
	function fINIRead($section, $key, $def = ''){
		if (isset($this->fINIArray[$section][$key])){
			return $this->fINIArray[$section][$key];
		}else{
			return $def;
		}
	}	
	//получаем массив с парами [ключ]=значение
	function fINIReadSection($section){
		$array = $this->fINIArray[$section];
		if(is_array($array)){
			return $array;
		}else{
			return array();
		}
	}
	// обновляем значения массива
	function fINIWrite($section, $key, $value){
		if (is_bool($value)){
			$value = $value ? 1 : 0;
		}
		// т.к. занчения парметров в ини файле обрамлены двойными кавычками
		$value = strtr($value,array("\""=>"&quot;"));
		$this->fINIArray[$section][$key] = $value;
	}	
	// обноляем значения массива . добавляем или переписываем секцию
	function fINIWriteSection($section,$arrvalue=array()){
		if(count($arrvalue) > 0){
			reset($arrvalue);
			while(list($key,$val) = each($arrvalue)){
				$this->fINIWrite($section, $key, $val);
			}
		}
	}	
	// удаляем секцию
	function fINIEraseSection($section){
		if (isset($this->fINIArray[$section])){unset($this->fINIArray[$section]);}
	}	
	// удаляем значение ключа
	function fINIEraseKey($section, $key){
		if (isset($this->fINIArray[$section][$key])){unset($this->fINIArray[$section][$key]);}
	}
	// обновляем ini файл
    function fINIUpdate(){
		$_BR_ = chr(13).chr(10);
		$result = '';
		foreach ($this->fINIArray as $sname=>$section){
			$result .= '[' . $sname . ']' . $_BR_;
			foreach ($section as $key=>$value){
				// $result .= $key .'='.$value . $_BR_;
				$result .= $key .'="'.$value .'"'. $_BR_;
			}
			$result .= $_BR_;
		}
		//class_file::fRewrite($this->fINIFileName,$result);
		$this->iniRewrite($this->fINIFileName,$result);
		return (true);
    }	
	
	//функция переписывает файл новыми данными не меняя имени файла
	function iniRewrite($f_name='',$f_content=''){
		if($fp = fopen($f_name,"a+")){
			flock($fp,LOCK_EX);//блокировка файла
			ftruncate($fp,0);//УДАЛЯЕМ СОДЕРЖИМОЕ ФАЙЛА
			fputs($fp,$f_content);
			fflush($fp);//очищение файлового буфера и запись в файл
			flock($fp,LOCK_UN);//снятие блокировки
			fclose($fp);	
			return(true);
		}else{
			return(false);
		}
	}

}//class_ini


// class Class1
// {
	// static private $instance = null;
	// private $var1 = 'f kdh df,ghkdf.kh d.fgh';

	// static public function getInstance()
	// {
		// if (self::$instance == null) 
		// {
			// self::$instance = new Class1();
		// }
		// return self::$instance;
	// }
	// public function view1()
	// {
		// echo $this->var1;
	// }
// }

// class Class2
// {
	// public function view2()
	// {
		// Class1::getInstance()->view1();
	// }
// }

// Вот небольшой PHP-код, позволяющий шифровать данные алгоритмом XXTEA:

// /* XXTEA encryption arithmetic library.
// *
// * Copyright (C) 2006 Ma Bingyao <andot@ujn.edu.cn>
// * Version:      1.5
// * LastModified: Dec 5, 2006
// * This library is free.  You can redistribute it and/or modify it.
// */

// function long2str($v, $w) {
    // $len = count($v);
    // $n = ($len - 1) << 2;
    // if ($w) {
        // $m = $v[$len - 1];
        // if (($m < $n - 3) || ($m > $n)) return false;
        // $n = $m;
    // }
    // $s = array();
    // for ($i = 0; $i < $len; $i++) {
        // $s[$i] = pack("V", $v[$i]);
    // }
    // if ($w) {
        // return substr(join('', $s), 0, $n);
    // } else {
        // return join('', $s);
    // }
// }

// function str2long($s, $w) {
    // $v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
    // $v = array_values($v);
    // if ($w) {
        // $v[count($v)] = strlen($s);
    // }
    // return $v;
// }

// function int32($n) {
    // while ($n >= 2147483648) $n -= 4294967296;
    // while ($n <= -2147483649) $n += 4294967296;
    // return (int)$n;
// }

// function xxtea_encrypt($str, $key) {
    // if ($str == "") {
        // return "";
    // }
    // $v = str2long($str, true);
    // $k = str2long($key, false);
    // if (count($k) < 4) {
        // for ($i = count($k); $i < 4; $i++) {
            // $k[$i] = 0;
        // }
    // }
    // $n = count($v) - 1;

    // $z = $v[$n];
    // $y = $v[0];
    // $delta = 0x9E3779B9;
    // $q = floor(6 + 52 / ($n + 1));
    // $sum = 0;
    // while (0 < $q--) {
        // $sum = int32($sum + $delta);
        // $e = $sum >> 2 & 3;
        // for ($p = 0; $p < $n; $p++) {
            // $y = $v[$p + 1];
            // $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            // $z = $v[$p] = int32($v[$p] + $mx);
        // }
        // $y = $v[0];
        // $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        // $z = $v[$n] = int32($v[$n] + $mx);
    // }
    // return long2str($v, false);
// }

// function xxtea_decrypt($str, $key) {
    // if ($str == "") {
        // return "";
    // }
    // $v = str2long($str, false);
    // $k = str2long($key, false);
    // if (count($k) < 4) {
        // for ($i = count($k); $i < 4; $i++) {
            // $k[$i] = 0;
        // }
    // }
    // $n = count($v) - 1;

    // $z = $v[$n];
    // $y = $v[0];
    // $delta = 0x9E3779B9;
    // $q = floor(6 + 52 / ($n + 1));
    // $sum = int32($q * $delta);
    // while ($sum != 0) {
        // $e = $sum >> 2 & 3;
        // for ($p = $n; $p > 0; $p--) {
            // $z = $v[$p - 1];
            // $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            // $y = $v[$p] = int32($v[$p] - $mx);
        // }
        // $z = $v[$n];
        // $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        // $y = $v[0] = int32($v[0] - $mx);
        // $sum = int32($sum - $delta);
    // }
    // return long2str($v, true);
// }

// Пример шифрования/дешифрования
// В представленном коде мы видим, что у нас есть в распоряжении две функции: xxtea_encrypt и xxtea_decrypt. Остальные функции носят вспомогательный характер. Конечно, можно было бы эту реализацию вынести в отдельный класс, но оставим все как есть.
// Пробуем зашифровать и сразу расшифровать текстовую строку:
// require_once 'xxtea.inc.php';
// $cipher = xxtea_encrypt('My test string', 'My test password');
// echo xxtea_decrypt($cipher, 'My test password');
// При выводе получаем: My test string

?>