<?php

class MyConn {
	
	var $dbServer;
	var $dbName;
	
	var $countRecords;
	var $arrResults;
	var $intCurrentAutoID;
	
	function MyConn($query='', $host=DB_HOST, $user=DB_USER, $pass=DB_PASS, $base=DB_NAME) {
		$this->dbServer = @mysql_connect($host, $user, $pass) 
			or die('Server connection not possible.');
			
		$this->dbName = @mysql_select_db($base, $this->dbServer)
			or die('Database connection not possible.');
			
		if( $query ) {
			$this->setQuery($query);
		}
	}
	
	function setQuery($query) {
		
		$myData = @mysql_query($query, $this->dbServer)
			or die('MySQL Error: '.mysql_error().'<pre>'.$query.'</pre><br />');
		
		if(DEBUG == true) {
			echo '<pre style="background: #FFFFFF; padding: 2px;">';
			echo '--MySQL---------------------------------<br />';
			echo $query.'<br />';
			echo '----------------------------------------</pre>';
		}
		
		$this->setCurrentAutoID(mysql_insert_id($this->dbServer));
		
		if($myData) {
			$intRows = mysql_num_rows($myData) or 0;
			$countArr = 0;
			if($intRows > 0) {
				for($i=0; $i<$intRows; $i++) {
					$arrData[$countArr] = mysql_fetch_assoc($myData);
					++$countArr;
				}
			}
		}
		
		$this->arrResults = $arrData;
	}
	
	function getResults() {
		return $this->arrResults;
	}
	function getNumRecords() {
		return $this->countRecords;
	}
	
	function setCurrentAutoID($intID){
		$this->intCurrentAutoID = $intID;
	}
	function getCurrentAutoID(){
		return $this->intCurrentAutoID;
	}
	
	function setHashIt($strID){
		
		$arrResults = $this->arrResults;
		
		$arrData = array();
		foreach($arrResults as $intID => $arrResult){
			$arrData[$arrResult[$strID]] = $arrResult;
		}
		
		$this->arrResults = $arrData;
	}
	
	function makeEntrySafe($strData) {
		
		#$strData = addslashes($strData);
		$strData = mysql_real_escape_string($strData);
		return $strData; //addslashes()
	}
}