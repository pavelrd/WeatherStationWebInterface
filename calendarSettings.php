<?php

require_once("fixString.php");

session_start();

$db = 0;

if( isset($_GET['logout']) ){
	
	unset($_SESSION['logged']);
	
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

} else if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) && isset($_GET['getCalendarColors']) ) {
	
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
	
} else if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) && isset($_GET['changeCalendarColors']) ) {
	
	$file = fopen( "calendarColors.config", "w+" );
	
	fwrite( $file, $_GET['freeDayBackground']."\n" );
	fwrite( $file, $_GET['freeDayBorder']."\n" );
	fwrite( $file, $_GET['birthDayBackground']."\n" );
	fwrite( $file, $_GET['birthDayBorder']."\n" );
	fwrite( $file, $_GET['goodDayBackground']."\n" );
	fwrite( $file, $_GET['goodDayBorder']."\n" );
	
	fclose( $file );
	
	$cssFile = fopen( "css/colors.css", "w+" );
		
	fwrite( $cssFile, "td.highlightFreeDays a { background: ".$_GET['freeDayBackground']." !important; border: 1px ".$_GET['freeDayBorder']." solid !important; }\n" );
	fwrite( $cssFile, "td.highlightBirthdays a { background: ".$_GET['birthDayBackground']." !important; border: 1px ".$_GET['birthDayBorder']." solid !important; }\n" );
	fwrite( $cssFile, "td.highlightGoodDays a { background: ".$_GET['goodDayBackground']." !important; border: 1px ".$_GET['goodDayBorder']." solid !important; }\n" );
	
	fclose($cssFile);
	
	$data['state'] = $_GET['freeDayBackground'];
	
	echo json_encode($data);
	
	return;
	
}

echo <<<_HTMLCODE

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Настройки календаря</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.structure.css" />
<link rel="stylesheet" type="text/css" href="css/lib/jquery-ui.theme.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/calendarSettings.css" />
<link rel="stylesheet" type="text/css" href="css/iris.css" />

<script src="js/lib/jquery.js"></script>
<script src="js/lib/jquery-ui.js"></script>
<script src="js/lib/color.js"></script>
<script src="js/lib/iris.js"></script>
<script src="js/makeCalendar.js"></script>
<script src="js/calendarSettings.js"></script>

</head>
<body>

<div style="height:100%">


  
  <div id="faux" style="width:100%; height: 100%" >
	
_HTMLCODE;

if( isset($_SESSION['logged']) && ($_SESSION['logged'] == 1) ){

echo <<<_HTMLCODE

	<center>
	<br>
	<div id="accordion" style="height:100%" >
		<h3>Дни рождений</h3>
		<div id="divPeoplesPanel">
			<center>
			<table cellspacing="10" >
	  
			<tr> 
				<td>Фамилия</td>
				<td><input id="inputLastName" type="text" /></td>
			</tr>
		
			<tr> 
				<td>Имя</td>
				<td><input id="inputName" type="text" />*</td>
			</tr>
		
			<tr> 
				<td>Отчество</td>
				<td><input id="inputSecondName" type="text" /></td>
			</tr>
		
			<tr> 
				<td>День рождения</td>
				<td><input id="inputBirthday">*</td>
			</tr>
		
			<tr> 
				<td>Примечание</td>
				<td><input id="inputMessage" type="text" /></td>
			</tr>
		
			<tr> 
				<td align="center" colspan="2"><button id="addUser">Добавить</button></td>
			</tr>	
		
			</table>
	
			<div id="usersTable" >

_HTMLCODE;

			include_once("birthdaysTable.php");
		
echo <<<_HTMLCODE
			</div>
			
		</div>
		
		<h3>Выходные дни</h3>
		
		<div id="divFreeDaysPanel" >
			<div id="boxBlock">
			
				<center>
					<font size="14px">
						<div id="divCurrentYear">
						</div>
					</font>
				</center>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth1">
					</div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth2"></div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth3"></div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth4">
					</div>
				</div>
				
				<div class="clear">
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth5">
					</div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth6">
					</div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth7">
					</div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth8">
					</div>
				</div>
				
				<div class="clear">
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth9">
					</div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth10">
					</div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth11">
					</div>
				</div>
				
				<div class="box hidden">
					<div class="calendar" id="divMoth12">
					</div>
				</div>
				
			</div>
		
			<center>
				<button id="buttonPrevYear">Предыдущий год</button>
				<button id="buttonApplyFreeDays">Применить</button>
				<button id="buttonNextYear">Следующий год</button>
				<br>
				<br>
				<select id="selectedDayOfWeek" >
					<option value="1" >Понедельники</option>
					<option value="2" >Вторники</option>
					<option value="3" >Среды</option>
					<option value="4" >Четверги</option>
					<option value="5" >Пятницы</option>
					<option value="6" >Субботы</option>
					<option value="0" >Воскресенья</option>
				</select>
				<button id="selectDaysOfWeek">Выбрать все</button>
				<button id="unselectAllDays">Снять выделение со всех дней</button><br>
				
				<div id="divApplyFreeDaysState" ></div>
				
			</center>

		
		</div>
		
		<h3>Цвета</h3>
		<div id="divColorsPanel" style="text-align: center">
			<div style="display: inline-block">
			<p>
			<div style="position:relative; float:left;">
			<table>

			<tr>
				<td><h3>Выходной день</h3></td> <td>Фон</td> <td><div id="divFreeDayColor" ></td> <td>Граница</td> <td><div id="divFreeDayBorderColor" ></td>
			</tr>
			<tr>
				<td><h3>День рождения</h3></td> <td>Фон</td> <td><div id="divBirthDayColor" ></td> <td>Граница</td> <td><div id="divBirthDayBorderColor" ></td>
			</tr>
			<tr>
				<td><h3>День рождения и выходной день</h3></td> <td>Фон</td> <td><div id="divFreeAndBirthDayColor" ></td> <td>Граница</td> <td><div id="divFreeAndBirthDayBorderColor" ></td> <td></td>
			</tr>
			</table>
			
			</div>
			<div style="float:left; margin-top: 10%">
				<div id="colorCalendar">
				</div>
				<br>
							<button id="applyColors">Применить</button>
							<div id="divApplyColorsState"></div>
			</div>
			<div style="clear:both">
			</div>

			</p>
			</div>
		</div>
		
	</div>
	
  
  <br>
  
  <form action="calendarSettings.php">
 <button name="logout" value="1" type="submit" id="buttonGoBack">Вернуться к календарю</button> <button name="logout" value="1" type="submit" id="buttonLogout">Выйти</button>
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
	<form action="calendarSettings.php">
		<div style="position:absolute;top:40%; left: 40%"> 
		<br>
		<br>
		<br>
		Введите пароль: <input name='password' type=text> <input class="jqueryButton" id="buttonLogin" type="submit" value="войти">
		<br>
		$requestPassword
		<br>
		<center>
		<button id="buttonBack" >Вернуться к календарю</button>
		</center>
		</div>
	</form>
</body>
</html>
_HTMLCODE;

}


?>