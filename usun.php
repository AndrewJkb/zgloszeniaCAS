<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
include "php/db_connect.php";
			$getid = $_GET['id'];
			$user = $_GET['user_id'];
			$sql = "UPDATE `zgloszenia` SET `status` = '2' WHERE `zgloszenia`.`id` ='$getid'";
			$wynik = mysql_query($sql) or die('Błąd zapytania '.mysql_error());
			$sql1 = "INSERT INTO `historia_zmian` (`log_id`, `user_id`, `id_zgl`, `log_data`, `log_typ_zmiany`) VALUES('',$user,$getid,now(),'Usunięcie/Archiwum')";
		$wynik1 = mysql_query($sql1) or die('Błąd zapytania 1'.mysql_error());
			
		   
   include "php/db_disconnect.php";
echo '<font face=verdana size=2 color=red>Rekord Zaktualizowano</font>';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=main.php">');  
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>


