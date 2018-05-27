<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');

include "php/db_connect.php";

list($zgloszenia_styczen2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-01-01 00:00:00' and '2018-01-31 23:59:59')"));
list($zgloszenia_luty2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-02-01 00:00:00' and '2018-02-28 23:59:59')"));
list($zgloszenia_marzec2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-03-01 00:00:00' and '2018-03-31 23:59:59')"));
list($zgloszenia_kwiecien2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-04-01 00:00:00' and '2018-04-30 23:59:59')"));
list($zgloszenia_maj2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-05-01 00:00:00' and '2018-05-31 23:59:59')"));
list($zgloszenia_czerwiec2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-06-01 00:00:00' and '2018-06-30 23:59:59')"));
list($zgloszenia_lipiec2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-07-01 00:00:00' and '2018-07-31 23:59:59')"));
list($zgloszenia_sierpien2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-08-01 00:00:00' and '2018-08-31 23:59:59')"));
list($zgloszenia_wrzesien2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-09-01 00:00:00' and '2018-09-30 23:59:59')"));
list($zgloszenia_pazdziernik_2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-10-01 00:00:00' and '2018-10-31 23:59:59')"));
list($zgloszenia_listopad2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-11-01 00:00:00' and '2018-11-30 23:59:59')"));
list($zgloszenia_grudzien2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-12-01 00:00:00' and '2018-12-31 23:59:59')"));
list($zgloszenia_2018) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2018-01-01 00:00:00' and '2018-12-31 23:59:59')"));
echo '<div class="panel panel-default">
		<div class="panel-heading"><center><h4>Statystyki zgłoszeń rok 2018</h4></center></div>
		<div class="panel-body">
		<div class="col-lg-6">
		<table class="table" >
  <thead>
    <tr><th>Miesiąc</th><th>Ilość zgłoszeń</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>Styczeń</td><td><b>'.$zgloszenia_styczen2018.'</b></td></tr>
	<tr><td>Luty</td><td><b>'.$zgloszenia_luty2018.'</b></td></tr>
	<tr><td>Marzec</td><td><b>'.$zgloszenia_marzec2018.'</b></td></tr>
	<tr><td>Kwiecień</td><td><b>'.$zgloszenia_kwiecien2018.'</b></td></tr>
	<tr><td>Maj</td><td><b>'.$zgloszenia_maj2018.'</b></td></tr>
	<tr><td>Czerwiec</td><td><b>'.$zgloszenia_czerwiec2018.'</b></td></tr>
	<tr><td>Lipiec</td><td><b>'.$zgloszenia_lipiec2018.'</b></td></tr>
	<tr><td>Sierpień</td><td><b>'.$zgloszenia_sierpien2018.'</b></td></tr>
	<tr><td>Wrzesień</td><td><b>'.$zgloszenia_wrzesien2018.'</b></td></tr>
	<tr><td>Październik</td><td><b>'.$zgloszenia_pazdziernik_2018.'</b></td></tr>
	<tr><td>Listopad</td><td><b>'.$zgloszenia_listopad2018.'</b></td></tr>
	<tr><td>Grudzień</td><td><b>'.$zgloszenia_grudzien2018.'</b></td></tr>
	
    </tbody>
</table>
		</div>';
echo '<div class="col-lg-6"><br /><br /><br /><br /><br /><br /><br /><br /><br /><img src="http://chart.apis.google.com/chart?chs=450x225&cht=p3&chd=t:'.$zgloszenia_styczen2018.','.$zgloszenia_luty2018.','.$zgloszenia_marzec2018.','.$zgloszenia_kwiecien2018.','.$zgloszenia_maj2018.','.$zgloszenia_czerwiec2018.','.$zgloszenia_lipiec2018.','.$zgloszenia_sierpien2018.','.$zgloszenia_wrzesien2018.','.$zgloszenia_pazdziernik_2018.','.$zgloszenia_listopad2018.','.$zgloszenia_grudzien2018.'&chl=Styczeń|Luty|Marzec|Kwiecień|Maj|Czerwiec|Lipiec|Sierpień|Wrzesień|Październik|Listopad|Grudzień"></div></div><center><h4>W 2018 roku było - <b> '.$zgloszenia_2018.' </b> zgłoszeń.</h4></center><br></div>';



include "php/db_connect.php";
list($zgloszenia_2016) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-01-01 00:00:00' and '2016-12-31 23:59:59')"));
        $sql1 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-09-01 00:00:00' and '2016-09-30 23:59:59')";
        $wynik1 = mysql_query($sql1) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-09-01' and '2016-09-30')!");
        $zgloszenia_wrzesien = mysql_fetch_row ($wynik1);

		$sql2 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-10-01 00:00:00' and '2016-10-31 23:59:59')";
        $wynik2 = mysql_query($sql2) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia WHERE (date_add BETWEEN 2016-10-01 and 2016-10-31)!");
        $zgloszenia_pazdziernik = mysql_fetch_row ($wynik2);

		$sql3 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-11-01 00:00:00' and '2016-11-30 23:59:59')";
        $wynik3 = mysql_query($sql3) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia WHERE (date_add BETWEEN 2016-11-01 and 2016-11-30)!");
        $zgloszenia_listopad = mysql_fetch_row ($wynik3);

		$sql4 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2016-12-01 00:00:00' and '2016-12-31 23:59:59')";
        $wynik4 = mysql_query($sql4) or die ("Błąd w zapytaniu - SELECT COUNT(*) FROM zgloszenia WHERE (date_add BETWEEN 2016-12-01 and 2016-12-31)!");
        $zgloszenia_grudzien = mysql_fetch_row ($wynik4);

		$sql5 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-01-01 00:00:00' and '2017-01-31 23:59:59')";
        $wynik5 = mysql_query($sql5) or die ("Błąd w zapytaniu - styczen 2017!");
        $zgloszenia_styczen2017 = mysql_fetch_row ($wynik5);
		
		$sql6 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-02-01 00:00:00' and '2017-02-28 23:59:59')";
        $wynik6 = mysql_query($sql6) or die ("Błąd w zapytaniu - luty 2017!");
        $zgloszenia_luty2017 = mysql_fetch_row ($wynik6);
		
		$sql7 = "SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-03-01 00:00:00' and '2017-03-31 23:59:59')";
        $wynik7 = mysql_query($sql7) or die ("Błąd w zapytaniu - marzec 2017!");
        $zgloszenia_marzec2017 = mysql_fetch_row ($wynik7);
		//SELECT COUNT( * )FROM `zgloszenia`WHERE (`date_add`BETWEEN '2017-06-01 00:00:00'AND '2017-06-30 23:59:59')
		
list($zgloszenia_kwiecien2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-04-01 00:00:00' and '2017-04-30 23:59:59')"));
list($zgloszenia_maj2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-05-01 00:00:00' and '2017-05-31 23:59:59')"));
list($zgloszenia_czerwiec2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-06-01 00:00:00' and '2017-06-30 23:59:59')"));
list($zgloszenia_lipiec2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-07-01 00:00:00' and '2017-07-31 23:59:59')"));
list($zgloszenia_sierpien2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-08-01 00:00:00' and '2017-08-31 23:59:59')"));
list($zgloszenia_wrzesien2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-09-01 00:00:00' and '2017-09-30 23:59:59')"));
list($zgloszenia_pazdziernik_2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-10-01 00:00:00' and '2017-10-31 23:59:59')"));
list($zgloszenia_listopad2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-11-01 00:00:00' and '2017-11-30 23:59:59')"));
list($zgloszenia_grudzien2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-12-01 00:00:00' and '2017-12-31 23:59:59')"));
list($zgloszenia_2017) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `zgloszenia` WHERE (`date_add` BETWEEN '2017-01-01 00:00:00' and '2017-12-31 23:59:59')"));
echo '<div class="panel panel-default">
		<div class="panel-heading"><center><h4>Statystyki zgłoszeń rok 2017</h4></center></div>
		<div class="panel-body">
		<div class="col-lg-6">
		<table class="table" >
  <thead>
    <tr><th>Miesiąc</th><th>Ilość zgłoszeń</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>Styczeń</td><td><b>'.$zgloszenia_styczen2017[0].'</b></td></tr>
	<tr><td>Luty</td><td><b>'.$zgloszenia_luty2017[0].'</b></td></tr>
	<tr><td>Marzec</td><td><b>'.$zgloszenia_marzec2017[0].'</b></td></tr>
	<tr><td>Kwiecień</td><td><b>'.$zgloszenia_kwiecien2017.'</b></td></tr>
	<tr><td>Maj</td><td><b>'.$zgloszenia_maj2017.'</b></td></tr>
	<tr><td>Czerwiec</td><td><b>'.$zgloszenia_czerwiec2017.'</b></td></tr>
	<tr><td>Lipiec</td><td><b>'.$zgloszenia_lipiec2017.'</b></td></tr>
	<tr><td>Sierpień</td><td><b>'.$zgloszenia_sierpien2017.'</b></td></tr>
	<tr><td>Wrzesień</td><td><b>'.$zgloszenia_wrzesien2017.'</b></td></tr>
	<tr><td>Październik</td><td><b>'.$zgloszenia_pazdziernik_2017.'</b></td></tr>
	<tr><td>Listopad</td><td><b>'.$zgloszenia_listopad2017.'</b></td></tr>
	<tr><td>Grudzień</td><td><b>'.$zgloszenia_grudzien2017.'</b></td></tr>
	
    </tbody>
</table>
		</div>';
echo '<div class="col-lg-6"><br /><br /><br /><br /><br /><br /><br /><br /><br /><img src="http://chart.apis.google.com/chart?chs=450x225&cht=p3&chd=t:'.$zgloszenia_styczen2017[0].','.$zgloszenia_luty2017[0].','.$zgloszenia_marzec2017[0].','.$zgloszenia_kwiecien2017.','.$zgloszenia_maj2017.','.$zgloszenia_czerwiec2017.','.$zgloszenia_lipiec2017.','.$zgloszenia_sierpien2017.','.$zgloszenia_wrzesien2017.','.$zgloszenia_pazdziernik_2017.','.$zgloszenia_listopad2017.','.$zgloszenia_grudzien2017.'&chl=Styczeń|Luty|Marzec|Kwiecień|Maj|Czerwiec|Lipiec|Sierpień|Wrzesień|Październik|Listopad|Grudzień"></div></div><center><h4>W 2017 roku było - <b> '.$zgloszenia_2017.' </b> zgłoszeń.</h4></center><br></div>';
        		
echo '<div class="panel panel-default">
		<div class="panel-heading"><center><h4>Statystyki zgłoszeń rok 2016</h4></center></div>
		<div class="panel-body">
		<div class="col-lg-6">
		<table class="table" >
  <thead>
    <tr><th>Miesiąc</th><th>Ilość zgłoszeń</th>
    </tr>
  </thead>
  <tbody>
	<tr><td>Wrzesień</td><td><b>'.$zgloszenia_wrzesien[0].'</b></td></tr>
<tr><td>Październik</td><td><b>'.$zgloszenia_pazdziernik[0].'</b></td></tr>
<tr><td>Listopad</td><td><b>'.$zgloszenia_listopad[0].'</b></td></tr>
<tr><td>Grudzień</td><td><b>'.$zgloszenia_grudzien[0].'</b></td></tr>
</tbody>
</table>
		</div>';
echo '<div class="col-lg-6"><img src="http://chart.apis.google.com/chart?chs=400x175&cht=p3&chd=t:'.$zgloszenia_wrzesien[0].','.$zgloszenia_pazdziernik[0].','.$zgloszenia_listopad[0].','.$zgloszenia_grudzien[0].'&chl=Wrzesie%C5%84%7CPa%C5%BAdziernik|Listopad|Grudzień"><br /><br /><br />
          </center></div>
		<br />
		</div> <center><h4>W 2016 roku było - <b> '.$zgloszenia_2016.' </b> zgłoszeń.</h4></center><br>';

echo '</div><footer class="footer">';		

include "php/db_disconnect.php";
include_once ('include/stopka.html');

} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">');
}
?>
