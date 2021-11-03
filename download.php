<?php
//qweqweqeqweqqqeweq
	session_start();
	
	$cmsPathRelative = ".";
	include($cmsPathRelative."/config.php");

	class downloadClass {
		var $properties = array ('old_name' => "", 'new_name' => "", 'type' => "", 'size' => "", 'resume' => "", 'max_speed' => "" );
		var $range = 0;
		
		function downloadClass($path, $name = "", $resume = 1, $max_speed = 0) {
			$name = ($name == "") ? substr( strrchr( "/" . $path, "/" ), 1 ) : $name;
			$name = end( explode( "/", $name ) );
			
			$file_size = @filesize( $path );
			$this->properties = array ('old_name' => $path, 'new_name' => $name, 'type' => "application/force-download", 'size' => $file_size, 'resume' => $resume, 'max_speed' => $max_speed );
			
			if( $this->properties['resume'] ) {
				if( isset( $_SERVER['HTTP_RANGE'] ) ) {
					$this->range = $_SERVER['HTTP_RANGE'];
					$this->range = str_replace( "bytes=", "", $this->range );
					$this->range = str_replace( "-", "", $this->range );
				} else {
					$this->range = 0;
				}
				if( $this->range > $this->properties['size'] ) $this->range = 0;
			} else {
				$this->range = 0;
			}
		
		}
		
		function download_file($limits, $uid = 1) {
			if( $this->range ) {
				header( $_SERVER['SERVER_PROTOCOL'] . " 206 Partial Content" );
			} else {
				header( $_SERVER['SERVER_PROTOCOL'] . " 200 OK" );
			}
			
			header( "Pragma: public" );
			header( "Expires: 0" );
			header( "Cache-Control:" );
			header( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
			header( "Content-Description: File Transfer" );
			header( "Content-Type: " . $this->properties["type"] );
			header( "Connection: close" );
			header( 'Content-Disposition: attachment; filename="' . $this->properties['new_name'] . '";' );
			header( "Content-Transfer-Encoding: binary" );
			
			if( $this->properties['resume'] ) header( "Accept-Ranges: bytes" );
			if( $this->range ) {
				header( "Content-Range: bytes {$this->range}-" . ($this->properties['size'] - 1) . "/" . $this->properties['size'] );
				header( "Content-Length: " . ($this->properties['size'] - $this->range) );
			} else {
				header( "Content-Length: " . $this->properties['size'] );
			}
			
			@ini_set( 'max_execution_time', 0 );
			@set_time_limit(120);
			
			$this->_download( $limits, $this->properties['old_name'], $this->range, $uid );
		}
		
		function _download($limits, $filename, $range = 0, $uid = 1) {
			@ob_end_clean();
			
			if( ($speed = $this->properties['max_speed']) > 0 ) $sleep_time = (8 / $speed) * 1e6; else $sleep_time = 0;
			
			$handle = fopen( $filename, 'rb' );
			fseek( $handle, $range );
			
			if( $handle === false ) return false;
			
			if ( $limits['maxsessions'] != 0 ) {
				if ( $this->properties['max_speed'] > 0 ) {
					$expires = ( $this->properties['size'] / 1024 ) / $this->properties['max_speed'];
					set_cookie( 'mfb_dl' . time( ), $uid, intval( time( ) + $expires ) );
				}
			}
			
			while ( ! feof( $handle ) ) {
				print( fread( $handle, 1024 * 8 ) );
				ob_flush();
				flush();
				usleep( $sleep_time );
			}
			
			fclose( $handle );
			return true;
		}

	}

	$file_id = (int)$_GET['fl'];

	$sql->sql_connect();
	$resultCF = $sql->sql_query("select * from ".$sql->prefix_db."tblfiles where id='".$file_id."'");
	if($sql->sql_rows($resultCF)){
		$queryCF = $sql->sql_array($resultCF);
	}else{die();}
	$sql->sql_close();

	$real = $queryCF['f_path'].'/'.$queryCF['f_name'];

	// echo $real;
	
	$file = new downloadClass($real, $queryCF['f_descr'], 1, 0 );
	
	$file->download_file(0, $file_id );
	die( );
	

?>