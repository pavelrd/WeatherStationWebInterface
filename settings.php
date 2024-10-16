<?php

require_once("fixString.php");

session_start();

$db = 0;

$wifiLoadScript   = "/usr/bin/python3 /home/user/scripts/runWifi.py";
$wifiSettingsFile = "/home/user/scripts/wifiSettings.py";

if( isset($_GET['logout']) ){
	
	unset($_SESSION['logged']);
	
}

if( isset($_SESSION['logged']) && ( $_SESSION['logged'] == 1 ) && isset($_GET['powerOff']) ){

	echo "Power off device...";
	
	exec ("sleep 1; sudo /sbin/poweroff");
	
}

if( isset($_SESSION['logged']) && ( $_SESSION['logged'] == 1 ) ){
	
	// header('Content-Type: text/html; charset=utf-8');
	
	$db = mysql_connect( "127.0.0.1", "root", "71425319" );
	
	mysql_select_db( "main", $db );
	
} else if ( isset($_GET['password']) ){

	$requestPassword = "";
	
	$password = $_GET['password'];
	
	if( $password == "71425319" ){
		
		$_SESSION['logged'] = 1;
		
		$db = mysql_connect( "127.0.0.1", "root", "71425319" );
	
		mysql_select_db( "main", $db );
	
	} else {
		
		$requestPassword = "Неправильный пароль";
		
		unset($_SESSION['logged']);
		
	}
	
}

if( isset($_SESSION['logged']) && ( $_SESSION['logged'] == 1 ) && isset($_GET['ajaxDeleteUser']) ){
	
	$id = mysql_fix_string($_GET['ajaxDeleteUser']);
	
	$query = "DELETE FROM users WHERE id='".$id."'";
	
	mysql_query($query);
	
	$data['state'] = 'ok';
	
	echo json_encode($data);
	
	return;
	
} else if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) && isset($_GET['ajaxChangeUser']) ){
	
	$query = "UPDATE users SET ";
	
	$query .= "name='".mysql_fix_string($_GET['name'])."', ";
	$query .= "lastname='".mysql_fix_string($_GET['lastname'])."', ";
	$query .= "secondname='".mysql_fix_string($_GET['secondname'])."', ";
	$query .= "message='".mysql_fix_string($_GET['message'])."' WHERE id='";
	$query .= mysql_fix_string($_GET['ajaxChangeUser']);
	$query .= "'";
	
	mysql_query($query);
	
	echo json_encode($data);
	
	return;
	
} else if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) && isset($_GET['ajaxAddUser']) ){
	
	$data['state'] = 'ok';
	
	$query = "INSERT INTO users(name,lastname,secondname,message,birthmonth,birthday) VALUES(";
	
	$query .= "'";
	$query .= mysql_fix_string($_GET['name']);
	$query .= "',";
	
	$query .= "'";
	$query .= mysql_fix_string($_GET['lastname']);
	$query .= "',";
	
	$query .= "'";
	$query .= mysql_fix_string($_GET['secondname']);
	$query .= "',";	
	
	$query .= "'";
	$query .= mysql_fix_string($_GET['message']);
	$query .= "',";
	
	$birthDate =  mysql_fix_string($_GET['birthday']);
		
	list($day, $month) = split('-', $birthDate);
	
	$query .= "'";
	$query .= $month;
	$query .= "',";

	$query .= "'";
	$query .= $day;
	$query .= "')";
		
	mysql_query($query);
	
	$data['query'] = $query;
	
	echo json_encode($data);

	return;

} else if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) && isset($_GET['ajaxChangeWifiSettings']) ){
	
	$wifiConfigFile = fopen( $wifiSettingsFile, "w+");
	
	if ( FALSE == $wifiConfigFile ) return;
	
	
	$mode = "";
	
	if( $_GET['mode'] == "wifiAP" ){
			
		$mode = "accessPoint";
			
	} else if( $_GET['mode'] == "wifiStation" ){
		
		$mode = "station";
			
	} else {
		
		$mode = "disabled";
			
	}
	
	$ssid       = $_GET['StationSSID'];
	$password   = $_GET['StationPassword'];
	$APssid     = $_GET['APSSID'];
	$APpassword = $_GET['APPassword'];
	
	$configBody = "mode=\"".$mode."\"\n";
	$configBody .= "stationSSID=\"".$ssid."\"\n";
	$configBody .= "stationPassword=\"".$password."\"\n";
	$configBody .= "accessPointSSID=\"".$APssid."\"\n";
	$configBody .= "accessPointPassword=\"".$APpassword."\"";	
	
	fwrite( $wifiConfigFile, $configBody );
	
	fclose( $wifiConfigFile );	
	
	$data['state'] = 'ok';

	$data['cmd'] = $wifiLoadScript;
	
	echo json_encode($data);
	
	exec($wifiLoadScript);
	
	return;
	
}

$wifiDisabled        = ""; // selected
$wifiAP              = ""; // selected
$wifiStation         = ""; // selected

$wifiAPState         = "";
$wifiStationState    = ""; // disabled
$wifiAPSSID          = "";
$wifiAPPassword      = "";
$wifiStationSSID     = ""; 
$wifiStationPassword = "";

if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) ){
	
	$wifiConfigFile = fopen( $wifiSettingsFile, "r");
	
	if ( FALSE == $wifiConfigFile ) return;	
	
	$rv = fread( $wifiConfigFile, 2048 );
	
	fclose($wifiConfigFile);
	
	$arr = split("\n",$rv);
	
	$mode = substr(split("=",$arr[0])[1], 1, -1);
		
	if( $mode == "disabled" ){
		
		$wifiDisabled     = "selected";
		$wifiStationState = "disabled";
		$wifiAPState      = "disabled";

	} else if( $mode == "accessPoint" ){
		
		$wifiAP = "selected";
		$wifiStationState = "disabled";
		
	} else if( $mode == "station" ){
		
		$wifiStation = "selected"; 
		$wifiAPState = "disabled";
		
	} else {
		
		return;
		
	}
		
	$wifiStationSSID     = substr(split("=",$arr[1])[1], 1, -1); // stationSSID 
	$wifiStationPassword = substr(split("=",$arr[2])[1], 1, -1); // stationPassword
	$wifiAPSSID          = substr(split("=",$arr[3])[1], 1, -1); // apSSID 
	$wifiAPPassword      = substr(split("=",$arr[4])[1], 1, -1); // apPassword
	
}

echo <<<_HTMLCODE

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Настройки сервера Rapberry Pi, комната 400</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.structure.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.theme.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/settings.css" />

<script src="js/lib/jquery.js"></script>
<script src="js/lib/jquery-ui.js"></script>
<script src="js/settings.js"></script>

</head>
<body>
<!-- Begin Wrapper -->
<div id="wrapper">
  <!-- Begin Header -->
  <div id="header">
	<div style="width:20%;float:left;">.</div>
	<div style="width:60%;float:left;"><h1 align="center">Настройки сервера Rapberry Pi, комната 400 </h1> </div>
	<div style="width:20%;height:100%;float:left;"><img id="pi_image" src="images/pi.jpg" /></div>
  
  </div>
  <!-- End Header -->
  <!-- Begin Navigation -->
  <div id="navigation">  </div>
  <!-- End Navigation -->
  <!-- Begin Faux Columns -->
  <div id="faux">
    <!-- Begin Left Column -->
    <div style="text-align: center; margin: auto" id="leftcolumn">
	
_HTMLCODE;

if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) ){

echo <<<_HTMLCODE

 <h1>Подключение к сети</h1>
	  
	  <center>
	  
	  <table style="display:block" align="center" cellspacing="10" >
	  
		<form action="settings.php">
		<tr>
			<td><b>Wifi</b></td>
			<td>
			<select id="selectWifiType" >
				<option $wifiDisabled value="wifiDisabled">Отключен</option>
				<option $wifiAP value="wifiAP" >Точка доступа</option>
				<option $wifiStation value="wifiStation" >Клиент</option>
			</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		<tr>
			<td colspan="2">Настройки точки доступа</td>
		</tr>
		
		<tr>
		<td colspan="2"><img height="50" width="50" src="images/accessPoint.png" /></td>
		</tr>
		
		<tr> 
		<td>Идентификатор сети - SSID</td>
		<td><input id="inputAPSSID" $wifiAPState value="$wifiAPSSID" type="text" /></td>
		</tr>
		
		<tr> 
		<td>Пароль</td>
		<td><input id="inputAPPassword" $wifiAPState value="$wifiAPPassword" type="text" /></td>
		</tr>
		
		<tr> 
		<td>Повторите пароль</td>
		<td><input id="inputAPPasswordRepeat" $wifiAPState value="$wifiAPPassword" type="text" /></td>
		</tr>
		
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		<tr>
			<td colspan="2">Настройки клиента</td>
		</tr>
		
		<tr>
		<td colspan="2"><img height="50" width="50" src="images/station.png" /></td>
		</tr>
		
		<tr> 
		<td>Идентификатор сети - SSID</td>
		<td><input id="inputStationSSID" $wifiStationState value="$wifiStationSSID" type="text" /></td>
		</tr>
		
		<tr> 
		<td>Пароль</td>
		<td><input id="inputStationPassword" $wifiStationState value="$wifiStationPassword" type="text" /></td>
		</tr>
		
		<tr> 
		<td>Повторите пароль</td>
		<td><input id="inputStationPasswordRepeat" $wifiStationState value="$wifiStationPassword" type="text" /></td>
		</tr>
				
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		
		<tr> 
			<td colspan="2"><button id="changeWifiSettings">Сохранить</button>
			<br>
			<div id="divChangeWifiSettingsState">
			</div>
			</td>
		</tr>
		
		</form>
		
	  </table>
	  </center>

	  <br>
	  <hr>
	  <br>
   <form action="settings.php">
	  <center><button name="powerOff" value="1" id="buttonPowerOff">Отключить raspberryPi</button></center>
  </form>
  <br>
  <hr>
	  
      <div class="clear"> </div>
    </div>
    <!-- End Left Column -->
    <!-- Begin Right Column -->
   
    <!-- End Right Column -->
	
  </div>
  <!-- End Faux Columns -->
  <!-- Begin Footer -->
  <!-- End Footer -->
  <br>
  
  <form action="settings.php">
  <center><button name="logout" value="1" type="submit" id="buttonLogout">Выйти</button>
  </form>
  
  <br>
  <br>

  
  </center>
    <br>
 </div>
 
<!-- End Wrapper -->
<input type="hidden" id="server_address" value="$_SERVER[SERVER_ADDR]" />
<input type="hidden" id="inputBirthdaySave" value="" />
</body>
</html>

_HTMLCODE;

} else {
	
echo <<<_HTMLCODE
	<form action="settings.php">
		<div> 
		<br>
		<br>
		<br>
		Введите пароль: <input name='password' type=text> <input class="jqueryButton" type="submit" value="войти">
		<br>
		$requestPassword
		<br>
		</div>
	</form>
	<button id="buttonBack">Выбрать другой сервис</button>

</body>
</html>
_HTMLCODE;

}

?>