<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';
sec_session_start();

if (phpCAS::checkAuthentication() == true) {

$page_title = 'Zgłoszenia';
include_once ('include/naglowek_boot1.html');
include_once ('function.php');

include "php/db_connect.php";
$session_grupa = $_SESSION['grupa'];
//Domyślne wartości, odpowiednio liczby rekordów na strone i przesunięcia 
$sql = "Select count(*) from `zgloszenia` WHERE `status`=0 AND `grupa` = $session_grupa" ;
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


//$sql = "SELECT zgloszenia.id,zgloszenia.ticketID,zgloszenia.date_add,zgloszenia.jednostka_zglaszajaca,zgloszenia.temat,zgloszenia.osoba_kontaktowa,zgloszenia.telefon,members.id as memid,members.username
//FROM zgloszenia,members WHERE zgloszenia.status=0 AND zgloszenia.grupa = $session_grupa AND zgloszenia.przypisany = members.id 
//ORDER BY zgloszenia.date_add DESC Limit $limit, $onpage";
$sql= "SELECT zgloszenia.id,zgloszenia.ticketID,zgloszenia.date_add,zgloszenia.jednostka_zglaszajaca,zgloszenia.lokalizacja,zgloszenia.temat,zgloszenia.osoba_kontaktowa,zgloszenia.telefon,
members.id as memid,members.username, grupa.nazwa as obszar 
FROM zgloszenia,members,grupa 
WHERE zgloszenia.status=0 AND (zgloszenia.grupa = $session_grupa AND grupa.grupaid = $session_grupa )AND zgloszenia.przypisany = members.id 
ORDER BY zgloszenia.date_add DESC Limit $limit, $onpage ";



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
$przypisanyid = $wiersz['memid'];
$przypisany = $wiersz['username'];

$sql1 = "SELECT COUNT(*) FROM `komentarze_zgl` WHERE `id_zgl`='$numer'"; // ile jest komentarzy do zgłoszenia
$wynik1 = mysql_query($sql1) or die ("Błąd w zapytaniu!");
$komentarz = mysql_fetch_row ($wynik1);

echo '
<div class="w3-container w3-card-2 w3-white w3-round w3-margin"><br>
        <img src="img/notepad.jpg" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">
        <span class="w3-right w3-opacity"><b>'.$date_add.'</b></span>
        <h4>Przypisany do:&nbsp&nbsp<b>'.ucfirst($przypisany).'</b></h4><br>
		Jednostka zgłaszająca: <b>'.ucwords($jednostka_zglaszajaca).'</b><br />
		Lokalizacja: <b>'.ucwords($lokalizacja).'</b><br />
		Osoba kontaktowa:&nbsp&nbsp<b>'.ucwords($osoba_kontaktowa).'</b>&nbsp&nbsp&nbsp&nbspTelefon:&nbsp&nbsp<b>'.$telefon.'</b><br />
		Unikalny numer zgłoszenia: <b>&nbsp&nbsp'.$unikalny_numer.'</b>&nbsp&nbsp&nbsp&nbspObszar:&nbsp&nbsp<b>'.ucfirst($obszar).'</b>
		
		
        <hr class="w3-clear">
        <p>'.strip_tags(substr($temat, 0, 150)).'</p>
          <div class="w3-row-padding" style="margin:0 -16px">
            <div class="w3-half">Komentarze:&nbsp&nbsp<b>'.$komentarz[0].'</b><br /><br /></div>
            <div class="w3-half">
 <!-- przypisz --><table><tr><td>
			<form action="przepisz.php" method=post>
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
echo '</td></tr></table></div>
        </div>
        <a href="zobacz.php?id='.$numer.'"><button type="button" class="w3-btn w3-theme-d1 w3-margin-bottom" id="przycisk">Więcej</button></a>&nbsp&nbsp&nbsp&nbsp
		<a href="edytuj.php?id='.$numer.'"><button type="button" class="w3-btn w3-theme-d1 w3-margin-bottom" id="przycisk">Edytuj</button></a>&nbsp&nbsp&nbsp&nbsp
		<a href="zamknij.php?id='.$numer.'&user_id='.$przypisanyid.'"><button type="button" class="w3-btn w3-theme-d1 w3-margin-bottom" id="przycisk">Zamknij</button></a>&nbsp&nbsp&nbsp&nbsp
		<a href="usun.php?id='.$numer.'&user_id='.$przypisanyid.'"><button type="button" class="w3-btn w3-theme-d1 w3-margin-bottom" id="przycisk">Usuń</button></a><br /><br />
		
	<!-- <button type="button" class="w3-btn w3-theme-d1 w3-margin-bottom" id="przycisk"><i class="fa fa-thumbs-up"></i>  Like</button> 
        <button type="button" class="w3-btn w3-theme-d2 w3-margin-bottom" id="przycisk"><i class="fa fa-comment"></i>  Comment</button> -->
      </div>

';
}
echo '</div>
    
    
    </div>
    
  <!-- End Grid -->
  </div>
  
<!-- End Page Container -->
</div>
<br>';
echo '<footer class="w3-container w3-theme-d3 w3-padding-16" style="text-align:center">';
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
        echo "<div id=\"nav\"><ul>";
        if($page > 1) echo "<li><a href=\"".$script_name."?page=".$prev."\">Poprzednia</a></li>";
        if ($forstart > 1) echo "<li><a href=\"".$script_name."?page=1\">[1]</a></li>";
        if ($forstart > 2) echo "<li>...</li>";
        for($forstart; $forstart < $forend; $forstart++){
                if($forstart == $page){
                        echo "<li class=\"current\">";
                }else{
                        echo "<li>";
                }
                echo "<a href=\"".$script_name."?page=".$forstart."\">[".$forstart."]</a></li>";
        }
        if($forstart < $allpages) echo "<li>...</li>";
        if($forstart - 1 < $allpages) echo "<li><a href=\"".$script_name."?page=".$allpages."\">[".$allpages."]</a></li>";
        if($page < $allpages) echo "<li><a href=\"".$script_name."?page=".$next."\">Następna</a></li>";
        echo "</ul></div><div class=\"clear\">";

echo '</footer>';
include "php/db_disconnect.php";


include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>