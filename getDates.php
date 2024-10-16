<?php

require_once("fixString.php");

$db = mysql_connect( "127.0.0.1", "root", "71425319" );
	
mysql_select_db( "main", $db );

$jdata = array();

if( isset($_GET['freeDays']) && isset($_GET['year']) ) {
	
	$jdata['state'] = 'ok';
	
	$jdata['freedays'] = getFreeDays( mysql_fix_string($_GET['year']) );
	
	
} else if( isset($_GET['birthDays']) ){
	
	$jdata['state'] = 'ok';
	
	$jdata['birthdays'] = getBirthdays();
	
} else if( isset($_GET['goodDays']) && isset($_GET['year']) ){
	
	$jdata['state']     = 'ok';
	$jdata['freedays']  = getFreeDays( mysql_fix_string($_GET['year']) );
	$jdata['birthdays'] = getBirthdays();

}

echo json_encode($jdata);

exit();	

function getFreeDays($year){

	$query = "SELECT * FROM dates WHERE date >='".$year."-01-01' AND date <= '".$year."-12-31'";
	
	$result = mysql_query($query);
	
	$rows = mysql_num_rows($result);	
	
	// Список дат
	
	$data = array();
	 
	for( $j = 0; $j < $rows ; ++$j ){
		
		$dstr = split('-', mysql_result( $result, $j, 'date' ) );
		
		$data[$j] = ((int)$dstr[2]).'/'.((int)$dstr[1]);
		
	}

	return $data;
	
}

function getBirthdays(){

	$query = "SELECT * FROM users";
	
	$result = mysql_query($query);
	
	$rows = mysql_num_rows($result);

	$userList = "";
	
	$data = array();
		
	for( $j = 0; $j < $rows ; ++$j ){
	
		$data[$j]['lastname'] = mysql_result($result, $j, 'lastname');
		$data[$j]['name']     =	mysql_result($result, $j, 'name');
		$data[$j]['date']     = mysql_result($result, $j, 'birthday').'/'.mysql_result($result, $j, 'birthmonth');
		
	}

	return $data;
	
}

?>