<?php
//@TODO Check against all possible failures and security risks.
class webdocs {
	var $server = 'carischsql';
	var $username = 'uname';
	var $passwd = 'passwd';
	var $db_name = 'webdocs';
	var $doc_trail = '_0000.pdf';
	var $webdocs_dir = 'webdocs';

	var $mssql;
	
	function __construct() {
		$this->mssql = mssql_connect($server,$username,$passwd);
		mssql_select_db($db_name,$this->mssql);
	}

	function get_document($id,$type) { //Don't forget to header("Content-Type: pdf");
		$value = false;
		$query_getall = "SELECT `DocumentKey`,`DocumentTitle` FROM `dmDocument` WHERE `UserDefined9` = $id AND `UserDefined8` = '$type'";
		$result = mssql_query($query_getall,$this->mssql);
		if ($result->num_rows() > 0) {
			while($row = mssql_fetch_assoc($result)) {
				$document_key = $row['DocumentKey'];
			}
			$handle = fopen($webddocs_dir . '/' . $document_key . $doc_trail,'rb');
			$value = stream_get_contents($handle);
			fclose($handle);
		}
		return $value;
	}
}
?>