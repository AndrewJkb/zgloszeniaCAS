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
$id_user_session = $_SESSION['user_id'];

list($rola) = mysql_fetch_row(mysql_query("SELECT members.user_role from members WHERE members.id = $id_user_session"));
//Domyślne wartości, odpowiednio liczby rekordów na strone i przesunięcia 

if ($rola == 1 || $rola == 2) {
	$sql = "Select count(*) from `zgloszenia` WHERE `status`=0 or status=3" ;
	}else {
	$sql = "Select count(*) from `zgloszenia` WHERE `status`=0 or status=3 AND `grupa` = $session_grupa" ;	
			}
	

$wynik = mysql_query($sql);
$r = mysql_fetch_array($wynik);
$all_posts= $r['0'];
$onpage = 10; //ilość newsów na stronę
$navnum = 3; //ilość wyświetlanych numerów stron, ze względów estetycznych niech będzie to liczba nieparzysta
$allpages = ceil($all_posts/$onpage); //wszysttkie strony to zaokrąglony w górę iloraz wszystkich postów i ilości postów na stronę


//sprawdzamy poprawnośc przekazanej zmiennej $_GET['page'] zwróć uwage na $_GET['page'] > $allpages
        if(!isset($_GET['page']) or $_GET['page'] > $allpages or !is_numeric($_GET['page']) or $_GET['page'] <= 0){
                $page = 1;
        }else{
                $page = $_GET['page'];
        }
        $limit = ($page - 1) * $onpage; //określamy od jakiego newsa będziemy pobierać informacje z bazy danych
		
	if ($rola == 1 || $rola == 2) {		
$sql1= "SELECT zgloszenia.id,zgloszenia.ticketID,zgloszenia.date_add,zgloszenia.jednostka_zglaszajaca,zgloszenia.lokalizacja,
zgloszenia.temat,zgloszenia.osoba_kontaktowa,zgloszenia.telefon, members.id as memid,members.username, grupa.nazwa as obszar,status_zgloszen.opis_statusu 
FROM zgloszenia,members,grupa,status_zgloszen 
WHERE (zgloszenia.status=0 or zgloszenia.status=3) AND zgloszenia.status=status_zgloszen.id_statusu AND zgloszenia.grupa = grupa.grupaid AND zgloszenia.przypisany = members.id 
ORDER BY zgloszenia.date_add DESC  Limit $limit, $onpage";
	} else{
		$sql1= "SELECT zgloszenia.id,zgloszenia.ticketID,zgloszenia.date_add,zgloszenia.jednostka_zglaszajaca,zgloszenia.lokalizacja,zgloszenia.temat,zgloszenia.osoba_kontaktowa,zgloszenia.telefon,
members.id as memid,members.username, grupa.nazwa as obszar,status_zgloszen.opis_statusu 
FROM zgloszenia,members,grupa,status_zgloszen 
WHERE (zgloszenia.status=0 or zgloszenia.status=3) AND zgloszenia.status=status_zgloszen.id_statusu AND (zgloszenia.grupa = $session_grupa AND grupa.grupaid = $session_grupa )AND zgloszenia.przypisany = members.id 
ORDER BY zgloszenia.date_add DESC Limit $limit, $onpage ";
	}


$wynik1 = mysql_query($sql1);
while ($wiersz = mysql_fetch_array($wynik1, MYSQL_ASSOC))
{
$numer = $wiersz['id'];
$unikalny_numer = $wiersz['ticketID'];
$obszar = $wiersz['obszar'];
$date_add = $wiersz['date_add'];
$jednostka_zglaszajaca = $wiersz['jednostka_zglaszajaca'];
$lokalizacja = $wiersz['lokalizacja'];
$temat = $wiersz['temat'];
$osoba_kontaktowa = $wiersz['osoba_kontaktowa'];
$status = $wiersz['opis_statusu'];
$telefon = $wiersz['telefon'];
$przypisanyid = $wiersz['memid'];
$przypisany = $wiersz['username'];

$sql2 = "SELECT COUNT(*) FROM `komentarze_zgl` WHERE `id_zgl`=$numer"; // ile jest komentarzy do zgłoszenia
$wynik2 = mysql_query($sql2) or die ("Błąd w zapytaniu!- koment");
$komentarz = mysql_fetch_row ($wynik2);

echo '<div class="panel panel-default">
	<div class="panel-body">
        <div class="panel-data-right"><b><span class="glyphicon glyphicon-calendar"></span>   '.$date_add.'</b></div>
        <img class="img_notepad" align="left" src="img/notepad.jpg" alt="zgloszenie" style="width:60px">
		<h5>Przypisany do:&nbsp&nbsp<b>'.ucfirst($przypisany).'</b><br />Status zgłoszenia: <b>'.ucfirst($status).'</b></h5><br /><br /><br />
		Jednostka zgłaszająca: <b>'.ucwords($jednostka_zglaszajaca).'</b><br />
		Lokalizacja: <b>'.ucwords($lokalizacja).'</b><br />
		Osoba kontaktowa:&nbsp&nbsp<b>'.ucwords($osoba_kontaktowa).'</b>&nbsp&nbsp&nbsp&nbsp&nbsp
		Telefon:&nbsp&nbsp<b>'.$telefon.'</b><br />
		Unikalny numer zgłoszenia: <b>&nbsp&nbsp'.$unikalny_numer.'</b>
		&nbsp&nbsp&nbsp&nbspObszar:&nbsp&nbsp<b>'.ucfirst($obszar).'</b>
		<hr>
        <p>'.strip_tags(substr($temat, 0, 150)).'</p>
		Komentarze: <span class="badge"><b>'.$komentarz[0].'</b></span><br /><br />
         <table><tr><td><div class="btn-group" role="group" aria-label="...">
        <a href="zobacz.php?id='.$numer.'" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span> Więcej</a>
		<a href="edytuj.php?id='.$numer.'" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Edytuj</a>
		<a href="zamknij.php?id='.$numer.'&user_id='.$przypisanyid.'" class="btn btn-default">
		<span class="glyphicon glyphicon-folder-close"></span> Zamknij</a>
		<a href="usun.php?id='.$numer.'&user_id='.$przypisanyid.'" class="btn btn-default">
		<span class="glyphicon glyphicon-trash"></span> Usuń</a></div></td>
		
		<td><form action="przepisz.php" method=post>
      <input type="hidden" name="numer" value="'.$numer.'">
      &nbsp&nbsp&nbsp&nbsp<select name="przypisany" class="btn btn-default">';

	  if ($rola == 1 || $rola == 2) {
	  $users_przypisany = "SELECT id, username FROM members";
	  } else {
	  $users_przypisany = "SELECT id, username FROM members WHERE grupa = $session_grupa";
	  }
$wynik_przypisany = mysql_query($users_przypisany) or die(mysql_error()) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik_przypisany, MYSQL_ASSOC))
{
$userid = $wiersz['id'];
$user = $wiersz['username'];
echo '<option name="'.$userid.'" value="'.$userid.'">'.htmlentities(ucfirst($user)).'</option>';
}

echo '</select>&nbsp&nbsp&nbsp&nbsp<button class="btn btn-default" type="submit" value="Przypisz">
<span class="glyphicon glyphicon-pencil"></span> Przypisz</button></form></td><td>';

echo '<form action="zmien_status.php" method=post>
      <input type="hidden" name="numer" value="'.$numer.'">
      &nbsp&nbsp&nbsp&nbsp<select name="status" class="btn btn-default">';

$status_id = "SELECT id_statusu,opis_statusu FROM status_zgloszen";
$wynik_status = mysql_query($status_id) or die(mysql_error()) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik_status, MYSQL_ASSOC)){
	$stat_id = $wiersz['id_statusu'];
	$stat_opis = $wiersz['opis_statusu'];
	
echo '<option name="'.$stat_id.'" value="'.$stat_id.'">'.htmlentities(ucfirst($stat_opis)).'</option>';	
}
echo '</select>&nbsp&nbsp&nbsp&nbsp<button class="btn btn-default" type="submit" value="zmien" >Zmień</button></form></td>
		</tr></table></div></div>';

}

echo '</div><footer class="footer">';
echo '<b>Stron</b>';


//zabezpieczenie na wypadek gdyby ilość stron okazała sie większa niż ilośc wyświetlanych numerów stron
        if($navnum > $allpages){
                $navnum = $allpages;
        }

 //sprawdzamy poprawnośc przekazanej zmiennej $_GET['page'] zwróć uwage na $_GET['page'] > $allpages
        if(!isset($_GET['page']) or $_GET['page'] > $allpages or !is_numeric($_GET['page']) or $_GET['page'] <= 0){
                $page = 1;
        }else{
                $page = $_GET['page'];
        }
$forstart = $page - floor($navnum/2);
        $forend = $forstart + $navnum;
        
        if($forstart <= 0){ $forstart = 1; }
        
        $overend = $allpages - $forend;
        
        if($overend < 0){ $forstart = $forstart + $overend + 1; }
        
        //ta linijka jest ponawiana ze względu na to, że $forstart mogła ulec zmianie
        $forend = $forstart + $navnum;
        //w tych zmiennych przechowujemy numery poprzedniej i następnej strony
        $prev = $page - 1;
        $next = $page + 1;
        
        //nie wpisujemy "sztywno" nazwy skryptu, pobieramy ja od serwera
        $script_name = $_SERVER['SCRIPT_NAME'];
        
        //ten fragment z kolei odpowiada za wyślwietenie naszej nawigacji
        echo '<div><ul class="pagination">';
        if($page > 1) echo '<li><a href="'.$script_name.'?page='.$prev.'">Poprzednia</a></li>';
        if ($forstart > 1) echo '<li><a href="'.$script_name.'?page=1">1</a></li>';
        if ($forstart > 2) echo '<li class="page-item disabled"><a href="">...</a></li>';
        for($forstart; $forstart < $forend; $forstart++){
                if($forstart == $page){
                        echo '<li class="page-item active">';
                }else{
                        echo '<li>';
                }
                echo '<a href="'.$script_name.'?page='.$forstart.'">'.$forstart.'</a></li>';
        }
        if($forstart < $allpages) echo '<li class="page-item disabled"><a href="">...</a></li>';
        if($forstart - 1 < $allpages) echo '<li><a href="'.$script_name.'?page='.$allpages.'">'.$allpages.'</a></li>';
        if($page < $allpages) echo '<li><a href="'.$script_name.'?page='.$next.'">Następna</a></li>';
        echo '</ul></div><div class="clear">';

include "php/db_disconnect.php";


include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>