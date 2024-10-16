<?php

	$db = mysql_connect( "127.0.0.1", "root", "71425319" );
	
	mysql_select_db( "main", $db );
	
	$query = "SELECT * FROM users";

	$result = mysql_query($query);
	
	$rows = mysql_num_rows($result);

	$usersList = "";	
	
	for( $j = 0; $j < $rows ; ++$j ){
		
		$usersList .= "<tr>";
		
		$usersList .= "<td align=\"center\"><input value=\"".mysql_result($result, $j, 'lastname')."\" id=\"inputLastName".mysql_result($result, $j, 'id')."\" type=\"text\" /></td>";
		$usersList .= "<td align=\"center\"><input value=\"".mysql_result($result, $j, 'name')."\" id=\"inputFirstName".mysql_result($result, $j, 'id')."\" type=\"text\" /></td>";
		$usersList .= "<td align=\"center\"><input value=\"".mysql_result($result, $j, 'secondname')."\" id=\"inputSecondName".mysql_result($result, $j, 'id')."\" type=\"text\" /></td>";
		$usersList .= "<td align=\"center\"><input value=\"".mysql_result($result, $j, 'message')."\" id=\"inputMessage".mysql_result($result, $j, 'id')."\" type=\"text\" /></td>";
		$usersList .= "<td align=\"center\">".mysql_result($result, $j, 'birthday')." ";
		
		switch( mysql_result($result, $j, 'birthmonth') ){
			case '1' : $usersList .= "Января"; break;
			case '2' : $usersList .= "Февраля"; break;
			case '3' : $usersList .= "Марта"; break;
			case '4' : $usersList .= "Апреля"; break;
			case '5' : $usersList .= "Мая"; break;
			case '6' : $usersList .= "Июня"; break;
			case '7' : $usersList .= "Июля"; break;
			case '8' : $usersList .= "Августа"; break;
			case '9' : $usersList  .= "Сентября"; break;
			case '10' : $usersList .= "Октября"; break;
			case '11' : $usersList .= "Ноября"; break;
			case '12' : $usersList .= "Декабря"; break;
		}
		
		$usersList .= "</td>";
		$usersList .= "<td colspan=\"2\"align=\"center\" ><button class=\"buttonChangeUser\" id=\"buttonChangeUser".mysql_result($result, $j, 'id')."\">Сохранить</button>";
		$usersList .= "<button class=\"buttonDeleteUser\" id=\"buttonDeleteUser".mysql_result($result, $j, 'id')."\">Удалить </button></td>";
		
		$usersList .= "</tr>";
		
	}
	
	$birthdaysTable = "<center><table cellspacing=\"3\" align=\"center\" border=\"1px\">";
	
	$birthdaysTable .= "<tr><td align=\"center\" width=\"150\">Фамилия</td> <td  align=\"center\" width=\"150\">Имя</td> <td align=\"center\" width=\"150\">Отчество</td> <td align=\"center\" width=\"150\">Примечание</td><td  align=\"center\" width=\"150\"> День рождения</td> <td colspan=\"2\"></td></tr>";
	
	$birthdaysTable .= $usersList;
	
	$birthdaysTable .= "</table></center>";
	
	echo $birthdaysTable;
	
?>