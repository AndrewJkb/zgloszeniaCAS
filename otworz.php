<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
include "php/db_connect.php";
			$sql = "UPDATE `zgloszenia` SET `status` = '0' WHERE `zgloszenia`.`id` ='$_GET[id]'";
		   $wynik = mysql_query($sql) or die('Błąd zapytania '.mysql_error());
   include "php/db_disconnect.php";
echo '<font face=verdana size=2 color=red>Rekord Zaktualizowano</font>';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=main.php">');  
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>


