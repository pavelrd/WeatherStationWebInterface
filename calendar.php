<?php

require_once("fixString.php");

if( isset($_GET['getCalendarColors']) ) {
	
	$file = fopen( "calendarColors.config", "r" );
	
	$data['state']              = 'ok';
	$data['freeDayBackground']  = fgets($file);
	$data['freeDayBorder']      = fgets($file);
	$data['birthDayBackground'] = fgets($file);
	$data['birthDayBorder']     = fgets($file);
	$data['goodDayBackground']  = fgets($file);
	$data['goodDayBorder']      = fgets($file);
	
	fclose( $file );
	
	echo json_encode($data);
	
	return;

} else {

echo <<<_HTMLCODE

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Календарь дней рождений</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.structure.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.theme.css" />
<link rel="stylesheet" type="text/css" href="css/calendar.css" />
<link rel="stylesheet" type="text/css" href="css/colors.css" />

<script src="js/lib/jquery.js"></script>
<script src="js/lib/jquery-ui.js"></script>
<script src="js/makeCalendar.js"></script>
<script src="js/calendar.js"></script>

</head>
<body>

	<div class="wrapper">
		
		<div id="boxBlock">
			<center><font size="14px"><div id="divCurrentYear"></div></font></center>
			<center>
				<button id="buttonPrevYear" >Предыдущий год</button>
				<button id="buttonSettings" >Настройки</button>
				<button id="buttonNextYear" >Следующий год</button>
			</center>
		<center>
		<p style=" margin-top: 0.4em; margin-bottom: 0em; font-size: 120%">
				выходные дни <input id="inputShowFreeDays" type="checkbox" checked>|
				дни рождений <input id="inputShowBirthdays" type="checkbox" checked>|
				сегодня <input id="inputShowNow" type="checkbox" checked>|
				количество выходных дней: <label id="labelFreeDays"> </label>
		</p>
		</center>		
		<div class="box hidden">
			<div class="calendar" id="divMoth1"></div>
		</div>
		<div class="box hidden"><div class="calendar" id="divMoth2"></div></div>
		<div class="box hidden"><div class="calendar" id="divMoth3"></div></div>
		<div class="box hidden"><div class="calendar" id="divMoth4"></div></div>
		<div class="clear"></div>
		<div class="box hidden"><div class="calendar" id="divMoth5"></div></div>
		<div class="box hidden"><div class="calendar" id="divMoth6"></div></div>
		<div class="box hidden"><div class="calendar" id="divMoth7"></div></div>
		<div class="box hidden"><div class="calendar" id="divMoth8"></div></div>
		<div class="clear"></div>		
		<div class="box hidden"><div class="calendar" id="divMoth9"></div></div>
		<div class="box hidden"><div class="calendar" id="divMoth10"></div></div>
		<div class="box hidden"><div class="calendar" id="divMoth11"></div></div>		
		<div class="box hidden"><div class="calendar" id="divMoth12"></div></div>
		</div>

	</div>

<input type="hidden" id="inputServerAddress" value="$_SERVER[SERVER_ADDR]" />
</body>
</html>
_HTMLCODE;
}

?>