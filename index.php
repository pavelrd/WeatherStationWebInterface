<?php

echo <<<_HTMLCODE

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Сервер Rapberry Pi, комната 400</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.structure.css" />
	<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.theme.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />

	<script src="js/lib/jquery.js"></script>
	<script src="js/lib/jquery-ui.js"></script>
	<script src="js/index.js"></script>

	</head>
	<body>
	<!-- Begin Wrapper -->
	<div id="wrapper">
	<!-- Begin Header -->
	<div id="header">
	<div style="width:20%;float:left;">.</div>
	<div style="width:60%;float:left;"><h1 align="center">Сервер Rapberry Pi, комната 400 </h1> </div>
	<div style="width:20%;height:100%;float:left;"><img id="pi_image" src="images/pi.jpg" /></div>
  
	</div>
	<!-- End Header -->
	<!-- Begin Navigation -->
	<div id="navigation">  </div>
	<!-- End Navigation -->
	<!-- Begin Faux Columns -->
	<div id="faux">
    <!-- Begin Left Column -->
    <div id="leftcolumn">
      <h1> Доступные сервисы: </h1>
	  <br>
	  <hr>
	  <br>
      <button class="goButton" id="buttonClimateStation" >Климатическая станция</button>
	  <br>
	  <br>
	  <hr>
	  <br>
      <button class="goButton" id="buttonClimateGraphs" >Графики погоды</button>
	  <br>
	  <br>
	  <hr>
	  <br>
      <button class="goButton" id="buttonBirthdays" >Календарь</button>
	  <br>
	  <br>
	  <hr>
	  <br>
	  <button class="goButton" id="buttonSettings" >Настройки</button>
	  <br>
      <div class="clear"> </div>
    </div>
    <!-- End Left Column -->
    <!-- Begin Right Column -->
   
    <!-- End Right Column -->
	</div>
	<!-- End Faux Columns -->
	<!-- Begin Footer -->
	<!-- End Footer -->
	</div>
	<div id="footer"> </div>
	<!-- End Wrapper -->
	</body>
	<input type="hidden" id="server_address" value="$_SERVER[SERVER_ADDR]">
	</html>
_HTMLCODE;

?>