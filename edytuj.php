<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'function.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');

include "php/db_connect.php";
        $sql = "SELECT * FROM zgloszenia WHERE id='$_GET[id]'";
        $wynik = mysql_query($sql) or die(mysql_error()) or die(mysql_error());
		//print_r ($wynik);
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{


$numer = $wiersz['id'];
$jednostka_zglaszajaca = $wiersz['jednostka_zglaszajaca'];
$lokalizacja = $wiersz['lokalizacja'];
$temat = $wiersz['temat'];
$osoba_kontaktowa = $wiersz['osoba_kontaktowa'];
$telefon = $wiersz['telefon'];
$kto = $wiersz['przypisany'];
$close = $wiersz['status'];


echo '<div class="panel panel-default">
<div class="panel-heading"><center><h4>Edycja</h4></center></div>
		<div class="panel-body">
        <img class="img_notepad" align="left" src="img/notepad.jpg" alt="zgloszenie" style="width:60px">
        
		<form action="edit.php" method=post>
		<a href="javascript: history.go(0)" class="btn btn-default">Odśweż</a>
		&nbsp;&nbsp;<a href="javascript:history.back()" class="btn btn-default">Powrót</a><br /><br />
		<input type="hidden" name="numer" value="'.$numer.'" class="dodaj">
		<input type="hidden" name="kto" value="'.$kto.'" class="dodaj">
		<label class="dodaj"><b>Jednostka zgłaszająca:</b></label>
		<input type="text" name="jednostka_zglaszajaca" size="150" value="'.ucwords($jednostka_zglaszajaca).'" class="dodaj"><br />
		<label class="dodaj"><b>Loklizacja:</b></label>
		<input type="text" name="lokalizacja" size="150" value="'.ucwords($lokalizacja).'" class="dodaj"><br />
		<label class="dodaj"><b>Opis:</b></label>
		<textarea name="temat" rows="15" cols="30" class="dodaj">'.strip_tags($temat).'</textarea><br />
		<label class="dodaj"><b>Osoba Kontaktowa:</b></label>
		<input type="text" name="osoba_kontaktowa" size="150" value="'.ucwords($osoba_kontaktowa).'" class="dodaj"><br />
		<label class="dodaj"><b>Telefon:</b></label>
		<input type="text" name="telefon" size="40" value="'.$telefon.'" size="50" class="dodaj"><br />
		<center><button type="submit" name="Dodaj" value="Dodaj" class="btn btn-default">Aktualizuj</button></center>
		</form></div>
        </div>
';
}

include "php/db_disconnect.php";

echo '</div><footer class="footer">';

include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
