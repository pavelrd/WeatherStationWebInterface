<?php

require_once("fixString.php");

session_start();

$db = mysql_connect( "127.0.0.1", "root", "71425319" );
	
mysql_select_db( "main", $db );

$jdata = array();

if( isset($_SESSION['logged']) && ( $_SESSION['logged'] == 1 ) && isset($_POST['year']) ) {
	
	$jdata['state'] = 'ok';
	
	$year = mysql_fix_string($_POST['year']);
	
	$query = "DELETE FROM dates WHERE date >= '".$year."-01-01' AND date <= '".$year."-31-12' ";
		
	mysql_query($query);
	
	$queries = "INSERT INTO dates(date) VALUES ";
	
	for($i=0; $i<sizeof($_POST['dates']); $i++){
		
		$date = mysql_fix_string($_POST['dates'][$i]);
		
		$dsp = split('/', $date);
		
		$queries .= "('".$year."-".$dsp[1]."-".$dsp[0]."'),";
		
	}
	
	if( $i != 0 ){
		
		mysql_query(substr($queries, 0, -1));
		
	}
	
}

echo json_encode($jdata);

exit();	

?>