<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';
sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');

include "php/db_connect.php";
        $sql = 'SELECT COUNT(*) FROM zgloszenia';
		$rs = mysql_query($sql) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia!");
		$allusers = mysql_fetch_row($rs);

		$sql1 = 'SELECT COUNT(*) FROM zgloszenia WHERE `status`=0 ';
        $wynik = mysql_query($sql1) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia WHERE status=0 !");
        $all_open_bugs = mysql_fetch_row ($wynik);

		$sql5 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-09-01' and '2016-09-30')";
        $wynik5 = mysql_query($sql5) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-09-01' and '2016-09-30')!");
        $zgloszenia_wrzesien = mysql_fetch_row ($wynik5);

		$sql2 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-10-01' and '2016-10-31')";
        $wynik2 = mysql_query($sql2) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia WHERE (date_add BETWEEN 2016-10-01 and 2016-10-31)!");
        $zgloszenia_pazdziernik = mysql_fetch_row ($wynik2);

		$sql3 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-11-01' and '2016-11-30')";
        $wynik3 = mysql_query($sql3) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia WHERE (date_add BETWEEN 2016-11-01 and 2016-11-30)!");
        $zgloszenia_listopad = mysql_fetch_row ($wynik3);

		$sql4 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-12-01' and '2016-12-31')";
        $wynik4 = mysql_query($sql4) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia WHERE (date_add BETWEEN 2016-12-01 and 2016-12-31)!");
        $zgloszenia_grudzien = mysql_fetch_row ($wynik4);

echo '<tr class="przerwa"><td align="center" colspan="7"><hr size="5" color="white"></td></tr>';
echo '<tr><td align="center" colspan="7">';
echo 'Wszystkie przyjęte zgłoszenia: <b>'.$allusers[0].'</b>&nbsp&nbsp&nbsp&nbsp<br>
  Wszystkie przyjęte otwarte zgłoszenia: <b>'.$all_open_bugs[0].'</b>&nbsp&nbsp&nbsp&nbsp<br /><br /><br /><br />';
echo  'Zgłoszenia wrzesień : <b>'.$zgloszenia_wrzesien[0].'</b><br /><br />
&nbsp&nbsp&nbsp&nbspZgłoszenia Październik : <b>'.$zgloszenia_pazdziernik[0].'</b><br /><br />
&nbsp&nbsp&nbsp&nbsp Zgłoszenia Listopad : <b>'.$zgloszenia_listopad[0].'</b><br /><br />
&nbsp&nbsp&nbsp&nbspZgłoszenia Grudzień : <b>'.$zgloszenia_grudzien[0].'</b><br /><br />';

foreach($_SESSION as $sesja=>$wartosc) {
    echo "<p>".$sesja." = ".$wartosc."</p>";
}

//http://forum.webhelp.pl/php-i-bazy-danych/sprawdzanie-czy-user-jest-online-t171212.html
//http://chart.apis.google.com/chart?chs=400x200&cht=p3&chd=t:2.0,69.0,64.0&chl=Wrzesie%C5%84%7CPa%C5%BAdziernik|Listopad
echo '<img src="http://chart.apis.google.com/chart?chs=800x375&cht=p3&chd=t:'.$zgloszenia_wrzesien[0].','.$zgloszenia_pazdziernik[0].','.$zgloszenia_listopad[0].','.$zgloszenia_grudzien[0].'
&chl=Wrzesie%C5%84%7CPa%C5%BAdziernik|Listopad|Grudzień"><br /><br /><br /></td></tr>';
echo '<tr class="przerwa"><td align="center" colspan="7"><hr size="5" color="white"></td></tr>';

include "php/db_disconnect.php";
include_once ('include/stopka.html');

} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
