<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
	
	include "php/db_connect.php";

$stoper_start = microtime(true);  // start pomiaru

$the_time = date('Y-m-d H:i');
include "php/db_connect.php";
$sql = "SELECT username,last_login FROM `members` WHERE active=1";
$wynik = mysql_query($sql) or die ("Błąd w zapytaniu - SELECT last_login FROM `members` WHERE active=1!!!!");
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
	{
	$new_time = $wiersz['last_login'];
	$datetime1 = new DateTime($the_time);
	$datetime2 = new DateTime($new_time);
	$interval = $datetime1->diff($datetime2);
	$minutes= $interval->format('%i');
	
	if ($minutes <=15){
		$user = $wiersz['username'];
		echo '<b>'.htmlentities(ucfirst($user)).'</b>&nbsp-&nbsp';
	}
	else
	{
		$sql1= "SELECT id FROM members WHERE active=1";
		$wynik1 = mysql_query($sql1) or die ("Błąd w zapytaniu - SELECT id FROM members WHERE active=1");
			while ($wiersz = mysql_fetch_array($wynik1, MYSQL_ASSOC))
				{
					$id_user = $wiersz['id'];
					$sql2 = "UPDATE members SET active= 0 WHERE id = $id_user";
					$wynik2 = mysql_query($sql2) or die ("Błąd w zapytaniu - UPDATE `members` SET `active`= '0' WHERE id =$id_user".$id_user);
				}
	}
	
}
/*$new_time = date('2016-12-21 08:00');	
	
$datetime1 = new DateTime($the_time);
$datetime2 = new DateTime($new_time);
$interval = $datetime1->diff($datetime2);
$minutes= $interval->format('%i');
echo 'Bierzący czas - '.$the_time;
echo '</br>Last Logon - '.$new_time.'</br>';
echo '</br></br>Różnica '.$minutes.' minut </br></br>';

if ($minutes <=15){
	echo 'Jesteś online.';
	
	}
	else{
		echo 'Jesteś offline.';
		
	}*/
	
$stoper_stop = microtime(true); //koniec pomiaru

echo bcsub($stoper_stop, $stoper_start, 2); 

	
	} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>