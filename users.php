<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');
include "php/db_connect.php";
$sql = "SELECT COUNT(*) FROM members";
$wynik = mysql_query($sql);
	$r = mysql_fetch_array($wynik);
	$all_posts= $r['0'];
	$onpage = 20; //ilość newsów na stronę
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
		<div class="panel-heading"><center><h4>Użytkownicy</h4></center></div>
	<div class="panel-body">
        <h4></h4><br>
	  <table class="table table-striped"><thead>
	  <th>LP</th>
	  <th>Id CAS</th>
	  <th>Użytkownik</th>
	  <th>Email</th>
	  <th>Ostatnie logowanie</th>
	  <th>Grupa</th><thead><tbody>';

include "php/db_connect.php";
$sql = "SELECT id,id_user_cas,username,email,last_login,grupa,nazwa FROM members,grupa WHERE members.grupa = grupa.grupaid Limit $limit, $onpage";
$wynik = mysql_query($sql) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{
$id = $wiersz['id'];
$id_casa = $wiersz['id_user_cas'];
$user = $wiersz['username'];
$email = $wiersz['email'];
$ost_log = $wiersz['last_login'];
$nazwa = $wiersz['nazwa'];

echo '<tr><td>'.$id.'</td><td>'.$id_casa.'</td><td>'.ucfirst($user).'</td><td>'.$email.'</td><td>'.$ost_log.'</td><td>'.$nazwa.'</td></tr>';
}
echo'</tbody></table></div></div>';

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
