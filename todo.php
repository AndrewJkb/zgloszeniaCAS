<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');

include "php/db_connect.php";
$sql = "SELECT COUNT(do_zrobienia.id) FROM do_zrobienia WHERE do_zrobienia.status = 0";
$wynik = mysql_query($sql);
$r = mysql_fetch_array($wynik);
$all_posts = $r['0'];
$onpage = 30; //ilość newsów na stronę
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
	<div class="panel-heading"><center><h4>Pomysły/Sugestie zmian</h4></center></div>
	<div class="panel-body"><br>
	 <br>
	<ol>';		

include "php/db_connect.php";
$sql = "SELECT do_zrobienia.tekst,members.username FROM do_zrobienia,members WHERE do_zrobienia.kto_dodal = members.id AND do_zrobienia.status = 0 Limit $limit, $onpage";
$wynik = mysql_query($sql) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{
$tekst = $wiersz['tekst'];
$user = $wiersz['username'];
echo '<li>&nbsp<b>'.strip_tags(ucfirst($tekst)).'</b><br /><span style="font-size: 12px; font-weight: bold;">Dodał:&nbsp'.ucfirst($user).'</span></li><br />';
}
echo '</ol></p>
<h5><a href="archiwum_todo.php" class="btn btn-primary">Zrealizowane - Pomysły/Sugestie zmian <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span></a></h5>
<br /><br />
<center>
<form action="add_todo.php" method=post class="dodaj">
<input type="hidden" name="kto_dodal" value="'.$_SESSION['user_id'].'" class="dodaj">
<textarea name="do_zrobienia" rows="15" cols="30" class="dodaj"></textarea><br />
<button type="submit" name="Aktualizuj" value="Aktualizuj" class="btn btn-default">Dodaj</button>
	</form></center></div></div>';
			
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
 
include_once ('include/stopka.html');

} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
