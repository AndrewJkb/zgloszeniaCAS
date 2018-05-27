<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
include ('function.php');	
include "php/db_connect.php";
			$getid = escape_data($_POST['id_zgl']);
			//$user = escape_data($_POST['przypisany']);
			$user = $_SESSION['user_id'];
			$komentarz = escape_data($_POST['komentarz']);
				   
		   $sql = "INSERT INTO `komentarze_zgl` (`id_zgl_hist`, `id_zgl`, `data_zgl`, `komentarz`) VALUES ('','$getid', now(), '$komentarz')" or die('Błąd zapytania '.mysql_error());
           $wynik = mysql_query($sql) or die('Błąd zapytania do komentarze_zgl'.mysql_error());
		   $sql1 = "INSERT INTO `historia_zmian` (log_id, user_id, id_zgl, log_data, log_typ_zmiany) VALUES ('','$user','$getid', now(),'Dodano komentarz')";
		   $wynik1 = mysql_query($sql1) or die('Błąd zapytania do historia_zmian'.mysql_error());
	   
	   
 list($imie_zmieniajacego) = mysql_fetch_row(mysql_query("SELECT members.username from members WHERE members.id = $user"));

		   $naglowki = "Reply-to: serwis@umcs.pl <serwis@umcs.pl>".PHP_EOL;
		   $naglowki .= "From: serwis@umcs.pl <serwis@umcs.pl>".PHP_EOL;
		   $naglowki .= "MIME-Version: 1.0".PHP_EOL;
		   $naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 
		   $adres = "serwis@umcs.pl";
		   $temat = 'Dodano nową sugestię/zmianę';
		   $tresc = 'Użytkownik '.ucfirst($imie_zmieniajacego).' dodał nową sugestię/zmianię<br><br>
			https://zgloszenia.lubman.umcs.pl/todo.php
			  
			   '.$komentarz.'
			   -- <br>
Pozdrawiamy<br>

System Zgłoszeniowy - Sekcja Obsługi Informatycznej - LubMAN UMCS

------------------------------------------
LubMAN UMCS
Uniwersytet Marii Curie-Skłodowskiej w Lublinie tel. 81 537 26 03

Niniejsza wiadomość elektroniczna lub jej załączniki mogą zawierać poufne lub chronione prawem informacje, które s± przeznaczone wył±cznie dla wskazanego w nich adresata. Jeżeli nie jeste¶ adresatem wiadomo¶ci, prosimy o jej nieujawnianie, zawiadomienie nadawcy o jej otrzymaniu oraz usunięcie wraz z wszystkimi zał±cznikami. Dziękujemy.
';

list($email) = mysql_fetch_row(mysql_query("SELECT members.email FROM members WHERE members.id = (SELECT zgloszenia.przypisany FROM zgloszenia WHERE zgloszenia.id= $getid)"));

if ($temat != "" || $email != "" || $tresc != "" || $naglowki !="") {

if (mail($email, $temat, $tresc, $naglowki)) {
echo "Mail został wysłany.";
}
else {
echo "Wystąpił błąd. Mail nie został wysłany.";
} }
else {
echo "Niekompletne dane.";
		   }	   
	   
	   
	   
   include "php/db_disconnect.php";
echo '<font face=verdana size=2 color=red>Rekord Dodano</font>';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=zobacz.php?id='.$getid.'">');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>


 