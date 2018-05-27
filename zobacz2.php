<?php
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');


function zmiana($parametr)
{ if ($parametr==0) {
  return 'Otwarte';}
else {
    return 'Zamknięte';  }
}
function change($i)
{if ($i == 0) {
    return '<b style="color:red;">Nie przypisany</b>';
} elseif ($i == 1) {
    return 'Andrzej';
} elseif ($i == 2) {
    return 'Marcin';
}}



include "php/db_connect.php";
$query = mysql_query("select * from zgloszenia where id = '$_GET[id]'");
$wiersz = mysql_fetch_array($query) or die('Błšd zapytania '.mysql_error());
$numer = $wiersz['id'];
$data = $wiersz['data'];
$czas = $wiersz['czas'];
$skad = $wiersz['skad'];
$temat = $wiersz['temat'];
$osoba_kontaktowa = $wiersz['osoba_kontaktowa'];
$telefon = $wiersz['telefon'];
$kto = $wiersz['przypisany']; 
$close = $wiersz['status'];

echo '<tr ><td colspan="7">&nbsp&nbsp<b>Data:</b> '.$data.' '.substr($czas,0,5).'&nbsp&nbsp&nbsp&nbsp<a href="edytuj.php?id='.$numer.'">edytuj</a></td></tr>
<tr><td colspan="3" width="200" align="left" valign="top"><b>Skąd:</b><p>&nbsp&nbsp&nbsp&nbsp'.$skad.'</p></td>
<td colspan="4"><b>Opis:</b><p>'.$temat.'</p>
<hr><b>Osoba kontaktowa</b>: '.$osoba_kontaktowa.'&nbsp&nbsp&nbsp&nbsp<b>Telefon:</b>'.$telefon.'&nbsp&nbsp&nbsp&nbsp<b>Przypisany:</b>&nbsp&nbsp'.change($kto).'
&nbsp&nbsp&nbsp&nbsp<b>Status:</b>&nbsp&nbsp'.zmiana($close).'</td></tr>
<tr><td bgcolor="#ffffff" colspan="7"></td></tr>';
	
$sql = "SELECT * FROM `historia_zgloszenia` WHERE `id_zgl`='$numer' ORDER BY `data_zgl` DESC";
        $wynik = mysql_query($sql);
		if(mysql_num_rows($wynik) > 0) {
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
	{
$numer1 = $wiersz['id_zgl_hist'];
$numer_nad = $wiersz['id_zgl'];
$data = $wiersz['data_zgl'];
$komentarz = $wiersz['komentarz'];
echo '<tr ><td colspan="7"><b>Data:</b>&nbsp&nbsp '.$data.' - &nbsp&nbsp&nbsp&nbsp<b>Opis:</b>'.$komentarz.'&nbsp&nbsp&nbsp&nbsp<b>Osoba kontaktowa</b>:</td></tr>';
	}
} else {
	$numer_nad = $numer;
	echo '<tr><td colspan="7" align="center"><b>Brak historii zgłoszenia</b></td></tr>';
	echo '<form action="add_koment.php" method=post>
<tr height="30" bgcolor="#E6E6Ff"><td width="125" colspan="7">Zgłoszenia<br><b>Aktualizacja:</b></td></tr>
<tr><td colspan="7"><input type="hidden" name="id_zgl" value="'.$numer_nad.'"></td></tr>
<tr bgcolor="#E6E6Ff"><td><b>Opis:</b></td><td colspan="6"><textarea name="komentarz" rows="15" cols="30"></textarea></td></tr>
<tr bgcolor="#E6E6Ff"><td colspan="7" align="center"><input type="submit" value="Aktualizuj"></td></tr>
</form>';

}

include "php/db_disconnect.php";

include_once ('include/stopka.html');

?>