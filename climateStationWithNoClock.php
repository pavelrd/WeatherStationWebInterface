<?php
	
	$db = mysql_connect( "127.0.0.1", "root", "71425319" );
	
	mysql_select_db( "main", $db );
	
	
class Sensor {
		public $ready;
		public $state;
		public $preheatSeconds;
		public $value;
}

if( isset($_GET['getTime']) ) {
	
	$data = array();
			
	$data["time"] = time();
		
	echo json_encode($data);
			
} else if( isset($_GET['getClimateData'])){
	
	$data = array();
	
	$sensorPath = '/tmp/climateStation/';
	
	$data["carbonDioxide"]       = makeSensorInfoObjectFromPath( $sensorPath.'mhz14a/1/',             'concentrationPPM' );
	$data["temperature"]         = makeSensorInfoObjectFromPath( $sensorPath.'ds18b20/1/',            'temperature' );
	$data["pressure"]            = makeSensorInfoObjectFromPath( $sensorPath.'bmp180/1/',             'pressureMmhg' );
	$data["humiduty"]            = makeSensorInfoObjectFromPath( $sensorPath.'multisensor/dht22/1/',  'humiduty' );
	$data['externalTemperature'] = makeSensorInfoObjectFromPath( $sensorPath.'multisensor/dht22/2/',  'temperature' );
	$data['voc']                 = makeSensorInfoObjectFromPath( $sensorPath.'multisensor/ms1100/1/', 'concentration' );
	$data['dust']                = makeSensorInfoObjectFromPath( $sensorPath.'multisensor/ppd42/1/',  'concentration' );

	echo json_encode($data);
	
} else {

echo <<<_HTMLCODE

<html>
<head>
<meta charset="utf-8">
	<title>Station</title>

	
	<link rel="stylesheet" href="css/lib/jquery.circliful.css" type="text/css" />
	<script src="js/lib/jquery-3.2.1.min.js"></script>
	<script src="js/lib/jquery.knob.js"></script>
	<script language="javascript" type="text/javascript" src="js/lib/jquery.flot.js"></script>
	<link rel="stylesheet" href="css/lib/jquery.circliful.css" type="text/css" />
	
	<link rel="stylesheet" href="css/climateStation.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="js/climateStation.js"></script>
	<script type="text/javascript" src="js/jquery.fireworks.js"></script>
	
</head>
<body>

	<div id="bg"></div>

	<div id="blockHeaderInfo" >
		
		<div id="blockSensorsData">
			
			<div class="boxfont" id="box1" >
				<div class="sensorHeader">
					<p class="sensorHeaderText" >Относительная влажность, %</p>
				</div>
				<div class="sensorCircle">
				<input id="circleHumiduty" type="text" value="0" >
				</div>
				<div class="sensorError"></div>
				<div class="sensorFooter">
					<div id="box1Min" class="sensorFooterLeft">
						---
					</div>
					<div id="box1Max" class="sensorFooterRight">
						---
					</div>					
				</div>
			</div>
			
			<div class="boxfont" id="box2" >
				<div class="sensorHeader">
					<p class="sensorHeaderText" >Температура в комнате, град С</p> 
				</div>
				<div class="sensorCircle">
				<input id="circleTemperature" type="text" value="10" >
				</div>
				<div class="sensorError"></div>
				<div class="sensorFooter">
					<div id="box2Min" class="sensorFooterLeft">
						---
					</div>
					<div id="box2Max" class="sensorFooterRight">
						---
					</div>					
				</div>
			</div>
			
			<div class="boxfont" id="box3" >
				<div class="sensorHeader">
					<p class="sensorHeaderText" >Атмосферное давление мм.рт.ст</p>
				</div>
				<div class="sensorCircle">
				<input id="circlePressure" type="text" value="704" >
				</div>
				<div class="sensorError"></div>
				<div class="sensorFooter">
					<div id="box3Min" class="sensorFooterLeft">
						---
					</div>
					<div id="box3Max" class="sensorFooterRight">
						---
					</div>					
				</div>
			</div>
			
			<div class="boxfont" id="box4" >
				<div class="sensorHeader">
					<p class="sensorHeaderText" >Органические вещества, ppm</p>
				</div>
				<div class="sensorCircle">
					<input id="circleVOC" type="text" value="0" >
				</div>
				<div class="sensorError"></div>
				<div class="sensorFooter">
					<div id="box4Min" class="sensorFooterLeft">
						---
					</div>
					<div id="box4Max" class="sensorFooterRight">
						---
					</div>					
				</div>
			</div>

			<div class="boxfont" id="box5" >
				<div class="sensorHeader">
					<p class="sensorHeaderText" >Температура на улице, град С </p>
				</div>
				<div class="sensorCircle">
				<input id="circleExternalTemperature" type="text" value="0" >
				</div>
				<div class="sensorError"></div>
				<div class="sensorFooter">
					<div id="box5Min" class="sensorFooterLeft">
						---
					</div>
					<div id="box5Max" class="sensorFooterRight">
						---
					</div>					
				</div>
			</div>
			
			<div class="boxfont" id="box6" >
				<div class="sensorHeader">
					<p class="sensorHeaderText" >Углекилый газ, CO2</p>
				</div>
				<div class="sensorCircle">
				<input id="circleCarbonDioxide" type="text" value="350" >
				</div>
				<div class="sensorError"></div>
				<div class="sensorFooter">
					<div id="box6Min" class="sensorFooterLeft">
						---
					</div>
					<div id="box6Max" class="sensorFooterRight">
						---
					</div>					
				</div>
			</div>
			

			
		</div>
		
	</div>
	


</body>
<input type="hidden" id="server_address" value="$_SERVER[SERVER_ADDR]">
</html>
_HTMLCODE;
}

	/*
		ready = true/false                       работает ли сервис
		state = connected/disconnected/preheat   подключен или прогревется ли датчик
	    preheatSeconds = сколько секунд осталось до окончания предварительного прогрева
	
	*/

	function makeSensorInfoObjectFromPath( $path, $value ){
		
		if( file_exists( $path.'readyProgram' ) ){
	
			$obj["ready"] = true; // Программа готова(работает)
			
			if( file_exists( $path.'preheatSensor' ) ){
			
				// Идет предварительный нагрев датчика
				
				$obj["state"] = "preheat";

				$preheatFile = fopen( $path.'preheatSensor', "r" );
			
				if( $preheatFile != FALSE ){
			
					$obj["preheatSeconds"] = fgets( $preheatFile, 32 ); // Количество секунд до окончания прогрева
					
					if( $obj["preheatSeconds"] == FALSE ){
						
						$obj["preheatSeconds"] = -1;
						
					} else {
						
						$obj["preheatSeconds"] = intval($obj["preheatSeconds"]);
						
					}
					
				} else {
				
					$obj["preheatSeconds"]  = -1;
				
				}
			
			} else {
			
				// Предварительный нагрев пройден или его не было
				
				$obj["state"] = "disconnected";
				$obj["value"] = 0;
					
				if( file_exists( $path.'readySensor' ) ){
					
					$valueFile = fopen( $path.$value, "r" );
					
					if ( FALSE != $valueFile ) {
						
						if( flock( $valueFile, LOCK_SH ) ) {
							$obj["state"] = "connected";
							$obj["value"] = fgets( $valueFile, 32 );
							
							if( FALSE == $obj["value"] ){
								$obj["value"] = 0;
							} else {
								$obj["value"] = floatval($obj["value"]);
							}
						}
						
						flock( $valueFile, LOCK_UN );
						
					}
					
				}
				
			}
		

		} else {
	
			$obj["ready"] = false; // Программа не готова - не работает
		
		}		
		
		return $obj;
		
	}
	
	/*
						<li id="point">:</li>
					<li id="sec">--</li>
	
	*/
	
?>