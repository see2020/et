<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
// 
// CMS: BLD - 2011
// ����: SQLParser.class 
// ����: /req/
// �����: SQLParser
// �����: 
// ������ SQL-��������
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

class SQLParser{
	//������� SQL-�������� �� �����
	//@param string $file
	//@return array
	function getQueriesFromFile($file){return $this->getQueries(file_get_contents($file));}
	    
	//������ SQL-������ �� �������
	//@param string $sql
	//@return array
	function getQueries($sql){
		$queries  = array();
		$strlen   = strlen($sql);
		$query    = '';

		for($position = 0; $position < $strlen; ++$position){
			$char = $sql{$position};
			switch($char){
				case '-':
					if(substr($sql, $position, 3)!=='-- '){
						$query.=$char;
						break;
					}
				case '#':
					while($char!=="\r" && $char !== "\n" && $position<$strlen - 1){
						$char=$sql{++$position};
					}
					break;
				case '`':
				case '\'':
				case '"':
					$quote  = $char;
					$query .= $quote;
					while($position < $strlen - 1){
						$char=$sql{++$position};
						if ( $char === '\\' ){
							$query.=$char;
							if($position<$strlen-1){
								$char = $sql{++$position};
								$query.= $char;
								if($position < $strlen - 1){$char = $sql{++$position};}
							}
							else{break;}
						}
						if($char===$quote){break;}
						$query.=$char;
					}
					$query.=$quote;
					break;
				case ';':
					$query = trim($query);
					if($query){$queries[] = $query;}
					$query = '';
					break;

				default:
					$query .= $char;
					break;
			}
		}

		$query = trim($query);
		if($query){$queries[] = $query;}
		return $queries;
	}
} //SQLParser

?>