<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true || login_check($mysqli) == true) {

$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');
include_once ('function.php');

include "php/db_connect.php";

$session_grupa = $_SESSION['grupa'];

$allowedC = array('date_add', 'skad');
$allowedD = array('asc', 'desc');

$orderBy = in_array($_GET['orderBy'], $allowedC) ? $_POST['orderBy'] : $allowedC[0];
$dir = in_array($_GET['dir'], $allowedD) ? $_GET['dir'] : $allowedD[0];

$sql = "SELECT zgloszenia.id,zgloszenia.date_add,zgloszenia.skad,zgloszenia.temat,zgloszenia.osoba_kontaktowa,zgloszenia.telefon,members.id as memid,members.username
FROM zgloszenia,members WHERE zgloszenia.status=0 AND zgloszenia.grupa = $session_grupa AND zgloszenia.przypisany = members.id ORDER BY $orderBy $dir";


//A w zmiennej $nDir będziesz mial przeciwna wartosc dla $dir. Czyli jak w adresie masz asc to przyjmie ona desc i na odwrót
$nDir = $dir == 'asc' ? 'desc' : 'asc';

echo '<tr><td colspan="7" align="center" valign="middle">'.$orderBy.' - '.$dir.'-'.$nDir.'
		<b>Sortuj :</b>&nbsp&nbsp&nbsp&nbsp<a href="main2.php?sortby=date_add&dir='.$nDir.'">Data</a>&nbsp&nbsp|&nbsp&nbsp<a href="main2.php?sortby=skad&dir='.$nDir.'">Skąd</a>
		</td></tr>';
echo '<tr class="przerwa"><td align="center" colspan="7"><hr size="5" color="white"></td></tr>';


$wynik = mysql_query($sql);
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{
$numer = $wiersz['id'];
$date_add = $wiersz['date_add'];
$skad = $wiersz['skad'];
$temat = $wiersz['temat'];
$osoba_kontaktowa = $wiersz['osoba_kontaktowa'];
$telefon = $wiersz['telefon'];
$przypisanyid = $wiersz['memid'];
$przypisany = $wiersz['username'];

$sql1 = "SELECT COUNT(*) FROM `komentarze_zgl` WHERE `id_zgl`='$numer'"; // ile jest komentarzy do zgłoszenia
$wynik1 = mysql_query($sql1) or die ("Błąd w zapytaniu!");
$komentarz = mysql_fetch_row ($wynik1);

echo '
<tr><td align="center" width="120"><b>Data:</b><br> '.$date_add.'</td>
<td width="120"><b>Skąd: </b><br />'.$skad.' </td>
<td colspan="4"><b>Opis:</b>&nbsp&nbsp'.strip_tags(substr($temat, 0, 150)).'<br />
<b>Osoba kontaktowa</b>: &nbsp&nbsp'.$osoba_kontaktowa.'&nbsp&nbsp -&nbsp&nbsp <b>Telefon:</b>&nbsp&nbsp '.$telefon.'<br />
<b>Komentarze:&nbsp&nbsp '.$komentarz[0].' </b><br>
<b>Przypisany do:</b>&nbsp&nbsp'.ucfirst($przypisany).'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
// przypisz
echo '<table><tr><td>';
echo '<form action="przepisz.php" method=post>
      <input type="hidden" name="numer" value="'.$numer.'">
      <select name="przypisany">';
$users_przypisany = "SELECT id, username FROM members WHERE grupa = $session_grupa";
$wynik_przypisany = mysql_query($users_przypisany) or die(mysql_error()) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik_przypisany, MYSQL_ASSOC))
{
$userid = $wiersz['id'];
$user = $wiersz['username'];

echo '<option name="'.$userid.'" value="'.$userid.'">'.htmlentities(ucfirst($user)).'</option>';
}

echo '</select>&nbsp&nbsp&nbsp&nbsp<button id="przycisk" type="submit" value="Przypisz" >Przypisz</button></form></td><td>';
//zmien status
echo '<form action="zmien_status.php" method=post>
      <input type="hidden" name="numer" value="'.$numer.'">
      <select name="status">';

$status_id = "SELECT id_statusu,opis_statusu FROM status_zgloszen";
$wynik_status = mysql_query($status_id) or die(mysql_error()) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik_status, MYSQL_ASSOC)){
	$stat_id = $wiersz['id_statusu'];
	$stat_opis = $wiersz['opis_statusu'];
	
echo '<option name="'.$stat_id.'" value="'.$stat_id.'">'.htmlentities(ucfirst($stat_opis)).'</option>';	
}
echo '</select>&nbsp&nbsp&nbsp&nbsp<button id="przycisk" type="submit" value="zmien" >Zmień</button></form></td></tr></table>';

echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
<td align="center">&nbsp&nbsp<a href="zobacz.php?id='.$numer.'"><img src="img/more.png" title="Więcej"></a>&nbsp&nbsp
&nbsp&nbsp <a href="edytuj.php?id='.$numer.'"><img src="img/edit.png" title="Edycja"></a>&nbsp&nbsp
&nbsp&nbsp<a href="zamknij.php?id='.$numer.'&user_id='.$przypisanyid.'"><img src="img/lock.png" title="Zamknij"></a>&nbsp&nbsp
&nbsp&nbsp<a href="usun.php?id='.$numer.'&user_id='.$przypisanyid.'"><img src="img/delete.png" title="Usuń"></a>
</td></tr>';
echo '<tr class="przerwa"><td align="center" colspan="7"><hr size="5" color="white"></td></tr>';
}


include "php/db_disconnect.php";


include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
