<?php

if( isset( $_GET['getWeatherData']) ){
	
	$link = mysqli_connect("127.0.0.1","root","71425319","main");
	
	$data = array();
	
	if (!$link) {
		$data['state']        = 'error';
		$data['errorMessage'] = "Error: Unable to connect to MySQL." ;
		echo json_encode($data);
		exit;
	}

	$sensorName = mysqli_real_escape_string($link,$_GET['sensorName']);
	$startDate  = mysqli_real_escape_string($link,$_GET['startDate']);
	$endDate    = mysqli_real_escape_string($link,$_GET['endDate']);
	
	switch($sensorName){
		case "humiduty"                 : $sensorName = "humiduty"; break;
		case "indoorTemperature"        : $sensorName = "indoorTemperature"; break;
		case "atmospherePressure"       : $sensorName = "atmospherePressure"; break;
		case "volatileOrganicCompounds" : $sensorName = "volatileOrganicCompounds"; break;
		case "outdoorTemperature"       : $sensorName = "outdoorTemperature"; break;
		case "carbonDioxide"            : $sensorName = "carbonDioxide"; break;
		default : exit(); //error
	}
	
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	
	$startDate .= " 00:00:01";
	$endDate   .= " 23:59:59";
	
	$query = "SELECT timestamp,$sensorName FROM sensors WHERE timestamp BETWEEN '$startDate' AND '$endDate' ORDER BY `sensors`.`timestamp` ASC ";
	
	$result = mysqli_query( $link, $query );
	
	if( !$result ){
		$data['state']        = 'error';
		$data['errorMessage'] = "Error: Unable to query database!";
		echo json_encode($data);
		exit;	
	}
	
	$data['sensorTimestamps'] = array();
	$data['sensorValues']     = array();
	
	while ( $row = mysqli_fetch_row($result) ) {
        
		array_push( $data['sensorTimestamps'], strtotime($row[0]) + 10800 );
		array_push( $data['sensorValues'], $row[1] );

		// printf ("%s (%s)\n", $row[0], $row[1]);
		
    }
	
	$data['state'] = 'success';

	echo json_encode($data);
	
	
} else {

echo <<<_HTMLCODE
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Графики погоды</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.structure.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.theme.css" />
<link rel="stylesheet" type="text/css" href="css/lib/pickmeup.css"  />
<link rel="stylesheet" type="text/css" href="css/stylesGenerator.css" />
<link rel="stylesheet" type="text/css" href="css/climateGraphs.css" />

<script src="js/lib/jquery.js"></script>
<script src="js/lib/jquery-ui.js"></script>
<script language="javascript" type="text/javascript" src="js/lib/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="js/lib/jquery.flot.time.min.js"></script>
<script language="javascript" type="text/javascript" src="js/lib/jquery.flot.selection.min.js"></script>
<script type="text/javascript" src="js/lib/pickmeup.js"></script>
<script src="js/climateGraphs.js"></script>

</head>
<body>
<!-- Begin Wrapper -->
<div id="wrapper">
  <!-- Begin Header -->
  <div id="header">
	<div style="width:20%;float:left;">.</div>
	<div style="width:60%;float:left;"><h1 align="center">Сервер Rapberry Pi, графики погоды</h1> </div>
	<div style="width:20%;height:100%;float:left;"></div>
  
  </div>
  <!-- End Header -->
  <!-- Begin Navigation -->
  <div id="navigation">  </div>
  <!-- End Navigation -->
  <!-- Begin Faux Columns -->
  <div id="faux">
    <!-- Begin Left Column -->
    <div id="leftcolumn">
	  <br>
	  <hr>
	  	Выберите временной интервал: <input id="inputCalendarChoise" size="22" readonly >
		<button id="buttonWeatherMonth">за месяц</button><button id="buttonWeatherWeek">за неделю</button> <button id="buttonWeatherYesterday">вчера</button> <button id="buttonWeatherToday">сегодня</button>
	  <hr>
	  <br>
	  Показать на графике 
			<select id="selectGraph1" name="selectGraph1" >
				<option value="humiduty">Влажность</option>
				<option selected value="indoorTemperature">Температура в комнате</option>
				<option value="atmospherePressure">Атмосферное давление</option>
				<option value="volatileOrganicCompounds">Органические вещества</option>
				<option value="outdoorTemperature">Температура на улице</option>
				<option value="carbonDioxide">Углекислый газ</option>
			</select>
	  <br>
	  <br>
	  
	  <hr>
	  <div id="weatherGraph1" style="width:100%; height:350px;">
	  </div>
	  <hr>
	  <br>
	  Показать на графике 
			<select id="selectGraph2" name="selectGraph2">
				<option  value="humiduty">Влажность</option>
				<option  value="indoorTemperature">Температура в комнате</option>
				<option  value="atmospherePressure">Атмосферное давление</option>
				<option  value="volatileOrganicCompounds">Органические вещества</option>
				<option  selected value="outdoorTemperature">Температура на улице</option>
				<option  value="carbonDioxide">Углекислый газ</option>
			</select>
	  <br>
	  <br>
	  <hr>
	  <div id="weatherGraph2" style="width:100%; height:350px;">
	  </div>
	  <hr>
	  
	  <hr>
	  <br>
	  	  <button class="goButton" id="buttonBack" >Выбрать другой сервис</button>
	  <br>
	  <br>
	  <hr>
	  <br>
      <div class="clear"> </div>
    </div>
    <!-- End Left Column -->
    <!-- Begin Right Column -->
   
    <!-- End Right Column -->
  </div>
  <!-- End Faux Columns -->
 </div>
   
<!-- End Wrapper -->
</body>
<input type="hidden" id="inputServerAddress" value="$_SERVER[SERVER_ADDR]">
</html>
_HTMLCODE;
}

/*
		<div id="dateSelect" style="position:relative; left:32%; width: 60%;" >
			
			<div id="dateSelectLeft" style="position:relative; float:left" >
			</div>
			<div style="float:left">
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			 ------- 
			</div>
			<div id="dateSelectRight" style="position:relative; float:left" >
				
			</div>
			
			<div style="clear:both"></div>
			
		</div>	
*/

?>
