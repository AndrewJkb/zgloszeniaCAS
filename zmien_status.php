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
  $status = escape_data($_POST['status']);
  $przepisujacy = $_SESSION['user_id'];

//echo $getid.' - '.$status.'  - '.$przepisujacy.'<br / >';

$sql = "UPDATE `zgloszenia` SET `status` = '$status' WHERE `id` = '$getid'";
$wynik = mysql_query($sql) or die('Błąd zapytania '.mysql_error());

$sql1 = "INSERT INTO historia_zmian (log_id, user_id, id_zgl, log_data, log_typ_zmiany) VALUES ('NULL', '$przepisujacy', '$getid', NOW(), 'Przepisanie zgłoszenia')";
$wynik1 = mysql_query($sql1) or die('Błąd zapytania insert - '.mysql_error());

echo '<font face=verdana size=2 color=red>Rekord Zaktualizowano</font>';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="0;URL=main.php">');

} else {
   echo 'Zaloguj sie';
   echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
