<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
include ('function.php');
include "php/db_connect.php";
			$user = $_SESSION['user_id'];
			$tekst = escape_data($_POST['do_zrobienia']);
			$who = escape_data($_POST['kto_dodal']);


			 //$sql = "INSERT INTO `komentarze_zgl` (`id_zgl_hist`, `id_zgl`, `data_zgl`, `komentarz`) VALUES ('','$getid', now(), '$komentarz')" or die('Błąd zapytania '.mysql_error());

			 $sql="INSERT INTO `do_zrobienia`(`tekst`, `status`, `kto_dodal`) VALUES ('$tekst','0','$who')";
			 $wynik = mysql_query($sql) or die('Błąd zapytania do dodawanko'.mysql_error());

   include "php/db_disconnect.php";
echo '<font face=verdana size=2 color=red>Rekord Dodano</font>';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=todo.php">');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
