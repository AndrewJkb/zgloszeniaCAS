<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {

include "php/db_connect.php";
include "function.php";
			$getid = escape_data($_POST['numer']);
			$jednostka_zglaszajaca= escape_data($_POST['jednostka_zglaszajaca']);
			$lokalizacja = escape_data($_POST['lokalizacja']);
			$temat= escape_data($_POST['temat']);
			$osoba= escape_data($_POST['osoba_kontaktowa']);
			$telefon= escape_data($_POST['telefon']);
			$przypisany= escape_data($_POST['kto']);
			$session_user_id = $_SESSION['user_id'];
			$session_grupa = $_SESSION['grupa'];
			//echo $getid.' - '.$jednostka_zglaszajaca.' - '.$temat.' - '.$osoba.' - '.$telefon.' - '.$przypisany.' - '.$status;

           $sql = "UPDATE `zgloszenia` SET `jednostka_zglaszajaca` = '$jednostka_zglaszajaca',`lokalizacja` = '$lokalizacja', `temat` = '$temat', `osoba_kontaktowa` = '$osoba', `telefon` = '$telefon', `przypisany` = '$przypisany', `grupa`='$session_grupa' WHERE `id` = '$getid' ";
		   $wynik = mysql_query($sql) or die('Błąd zapytania '.mysql_error());
		   $sql1 = "INSERT INTO `historia_zmian` (`log_id`, `user_id`, `id_zgl`, `log_data`, `log_typ_zmiany`) VALUES('NULL', '$session_user_id', '$getid', NOW(),'Edycja zgłoszenia')";
		   $wynik1 = mysql_query($sql1) or die('Błąd zapytania 1'.mysql_error());


		   include "php/db_disconnect.php";
echo '<font face=verdana size=2 color=red>Rekord Zaktualizowano</font>';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=main.php">'); 
 } else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
