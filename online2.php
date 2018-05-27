<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
	
	include "php/db_connect.php";

$the_time = date('Y-m-d H:i');
$new_time = date('2016-12-21 07:50');	
	
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
	}
	
	
	} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>