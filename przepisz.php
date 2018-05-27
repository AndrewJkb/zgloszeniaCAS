<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
// Utworz funkcję wstawiaj±c± przed znakami specjalnymi znak odwrotnego uko¶nika.
  	function escape_data ($data) {
  		global $link; // Potrzebujemy poł±czenia.
  		if (ini_get('magic_quotes_gpc')) {
  			$data = stripslashes($data);
  		}
  		return mysql_real_escape_string($data, $link);
  	} // Koniec funkcji.

  include "php/db_connect.php";
  $getid = escape_data($_POST['numer']);
  $przypisany = escape_data($_POST['przypisany']);
  $przepisujacy = $_SESSION['user_id'];
  list($grupa) = mysql_fetch_row(mysql_query("SELECT members.grupa FROM members WHERE members.id = $przypisany"));
//echo $getid.' - '.$przypisany.'<br / >';

$sql = "UPDATE `zgloszenia` SET `przypisany` = '$przypisany', `grupa`='$grupa' WHERE `id` = '$getid'";
$wynik = mysql_query($sql) or die('Błąd zapytania '.mysql_error());

$sql1 = "INSERT INTO `historia_zmian` (`log_id`, `user_id`, `id_zgl`, `log_data`, `log_typ_zmiany`) VALUES ('', '$przepisujacy', '$getid', NOW(), 'Zgłoszenie przepisane')";
$wynik1 = mysql_query($sql1) or die('Błąd zapytania insert - '.mysql_error());


list($email) = mysql_fetch_row(mysql_query("SELECT members.email FROM members WHERE members.id = $przypisany"));

$subject = 'Zgłoszenie zostało przypisane do Ciebie ';
$message = 'Zgłoszenie zostało przypisane Tobie. <a href="https://zgloszenia.lubman.umcs.pl/zobacz.php?id='.$getid.'">Sprawdź</a>';
$naglowki = "Reply-to: serwis@umcs.pl <serwis@umcs.pl>".PHP_EOL;
$naglowki .= "From: serwis@umcs.pl <serwis@umcs.pl>".PHP_EOL;
$naglowki .= "MIME-Version: 1.0".PHP_EOL;
$naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 

mail($email, $subject, $message, $naglowki);

echo '<font face=verdana size=2 color=red>Rekord Zaktualizowano</font>';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="0;URL=main.php">');

} else {
   echo 'Zaloguj sie';
   echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
