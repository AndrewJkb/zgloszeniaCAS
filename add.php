<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
include "function.php";
include "php/db_connect.php";
$przypisany = $_SESSION['user_id'];
$session_grupa = $_SESSION['grupa'];
$status = '0';
$jednostka_zglaszajaca = escape_data($_POST['jednostka_zglaszajaca']);
$temat = escape_data($_POST['temat']); 
$osoba_kontaktowa = escape_data($_POST['osoba_kontaktowa']);
$telefon = escape_data($_POST['telefon']);

	/* generujemy unikalny tracking ID i upewniamy siê czy ju¿ niema takiego samego */
		$useChars = 'AEUYBDGHJLMNPQRSTVWXZ123456789';
		$trackingID = $useChars{mt_rand(0,29)};
		for($i=1;$i<10;$i++)
		{
		$trackingID .= $useChars{mt_rand(0,29)};
		}

		$trackID = escape_data($trackingID);
		
		// Sprawdzamy czy taki trackID istnieje 
		$sql = "SELECT `id` FROM `zgloszenia` WHERE ticketID = '".$trackID."' LIMIT 1";
		$wynik = mysql_query($sql);
		if ( mysql_fetch_row($wynik) != 0) {
		// Tracking ID nie jest unikalne, generujemy je jeszcze raz kilkukrotnie
		$trackingID  = $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= substr(microtime(), -5);
		$trackID = escape_data($trackingID);
		
		$sql1 = "SELECT `id` FROM `zgloszenia` WHERE `ticketID` = '".$trackID."' Limit 1";
		$wynik1 = mysql_query($sql1);
			if (mysql_fetch_row($wynik) != 0) {
			echo " Houston mamy problem z generowaniem unikalengo id dla zg³oszenia";
			}
		}
	
		
		// Utwórz zapytanie.
		$query = "INSERT INTO `zgloszenia` (`id`, `ticketID`, `date_add`, `jednostka_zglaszajaca`, `temat`, `osoba_kontaktowa`, `telefon`, `przypisany`, `status`, `grupa`) VALUES ('','$trackID', NOW(), '$jednostka_zglaszajaca', '$temat', '$osoba_kontaktowa', '$telefon', '$przypisany','0','$session_grupa')" or die('B³¹d zapytania '.mysql_error());
		
		
		// Utwórz zapytanie.
		//$query = "INSERT INTO zgloszenia (id,ticketID,date_add,jednostka_zglaszajaca,temat,osoba_kontaktowa,telefon,przypisany,status,grupa) VALUES (null, '$trackID',NOW(),'$jednostka_zglaszajaca','$temat','$osoba_kontaktowa','$telefon','$przypisany',0,'$session_grupa')" or die('B³¹d zapytania '.$query.'<br />'.mysql_error());
		$result = @mysql_query ($query) or die('BÅ‚Ä…d zapytania  '.mysql_error()); // Wykonaj zapytanie.
		
		echo $result.'<br />';
		echo $query;
		
	//$sql= "INSERT INTO `historia_zmian` (`log_id`, `user_id`, `id_zgl`, `log_data`, `log_typ_zmiany`) VALUES('','$przypisany',$getid,now(),'ZgÅ‚oszenie zamkniÄ™te')";
	//$wynik = mysql_query($sql) or die('B³¹d zapytania do historia_zmian'.mysql_error());

	
include "php/db_disconnect.php";
echo '<font face=verdana size=2 color=red>Rekord Zaktualizowano</font><br /> ';
//echo ('<META HTTP-EQUIV="Refresh" CONTENT="5;URL=main.php">'); 	
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
