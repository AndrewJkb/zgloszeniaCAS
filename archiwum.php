<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');
$session_grupa = $_SESSION['grupa'];
include "php/db_connect.php";
//Pobranie liczby rekordów
$sql = 'Select count(*) from `zgloszenia` WHERE `status`=2';
$wynik = mysql_query($sql);
$r = mysql_fetch_array($wynik);
$all_posts = $r['0'];
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
echo '<div class="panel panel-default">
		<div class="panel-heading"><center><h5><b>Wszystkie usunięte zgłoszenia</b></h5></center></div>
		</div>';
//Pobranie odpowieniej paczki
$sql = "SELECT zgloszenia.id,zgloszenia.ticketID,zgloszenia.date_add,zgloszenia.jednostka_zglaszajaca,zgloszenia.lokalizacja,zgloszenia.temat,zgloszenia.osoba_kontaktowa,zgloszenia.telefon,zgloszenia.grupa,
members.id as memid, members.username,status_zgloszen.opis_statusu,grupa.nazwa as obszar 
FROM zgloszenia,members,status_zgloszen,grupa 
WHERE zgloszenia.przypisany=members.id AND zgloszenia.status=status_zgloszen.id_statusu AND zgloszenia.status=2 AND zgloszenia.grupa=grupa.grupaid
ORDER BY zgloszenia.date_add DESC  Limit $limit, $onpage";        
$wynik = mysql_query($sql);
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{
$numer = $wiersz['id'];
$unikalny_numer = $wiersz['ticketID'];
$obszar = $wiersz['obszar'];
$date_add = $wiersz['date_add'];
$jednostka_zglaszajaca = $wiersz['jednostka_zglaszajaca'];
$lokalizacja = $wiersz['lokalizacja'];
$temat = $wiersz['temat'];
$osoba_kontaktowa = $wiersz['osoba_kontaktowa'];
$telefon = $wiersz['telefon'];
$status = $wiersz['opis_statusu'];
$przypisany = $wiersz['username'];
$przypisanyid = $wiersz['memid'];

$sql1 = "SELECT COUNT(*) FROM `komentarze_zgl` WHERE `id_zgl`='$numer'"; // ile jest komentarzy do zgłoszenia
$wynik1 = mysql_query($sql1) or die ("Błąd w zapytaniu!");
$komentarz = mysql_fetch_row ($wynik1);

echo '<div class="panel panel-default">
	<div class="panel-body">
        <div class="panel-data-right"><b><span class="glyphicon glyphicon-calendar"></span>   '.$date_add.'</b></div>
        <img class="img_notepad" align="left" src="img/notepad.jpg" alt="zgloszenie" style="width:60px">
        <h5>Przypisany do:&nbsp&nbsp<b>'.ucfirst($przypisany).'</b></5><br>
		Jednostka zgłaszająca: <b>'.ucwords($jednostka_zglaszajaca).'</b><br />
		Lokalizacja: <b>'.ucwords($lokalizacja).'</b><br />
		Osoba kontaktowa:&nbsp&nbsp<b>'.ucwords($osoba_kontaktowa).'</b>&nbsp&nbsp&nbsp&nbspTelefon:&nbsp&nbsp<b>'.$telefon.'</b><br />
		Unikalny numer zgłoszenia: <b>&nbsp&nbsp'.$unikalny_numer.'</b>&nbsp&nbsp&nbsp&nbspObszar:&nbsp&nbsp<b>'.ucfirst($obszar).'</b>
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
$users_przypisany = "SELECT id, username FROM members WHERE grupa = $session_grupa";
$wynik_przypisany = mysql_query($users_przypisany) or die(mysql_error()) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik_przypisany, MYSQL_ASSOC))
{
$userid = $wiersz['id'];
$user = $wiersz['username'];

echo '<option name="'.$userid.'" value="'.$userid.'">'.htmlentities(ucfirst($user)).'</option>';
}

echo '</select>&nbsp&nbsp&nbsp&nbsp<button class="btn btn-default" type="submit" value="Przypisz">
<span class="glyphicon glyphicon-pencil"></span> Przypisz</button></form></td></tr></table>
		</div>
		<br />
		</div>

'; 
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