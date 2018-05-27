<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');
include_once ('function.php');


include "php/db_connect.php";
//$query = mysql_query("select * from zgloszenia where id = '$_GET[id]'");
$query = mysql_query("SELECT zgloszenia.id,zgloszenia.ticketID,zgloszenia.date_add,zgloszenia.jednostka_zglaszajaca,zgloszenia.lokalizacja,zgloszenia.temat,zgloszenia.osoba_kontaktowa,zgloszenia.telefon,
members.id as memid,members.username,status_zgloszen.opis_statusu,grupa.nazwa as obszar
FROM zgloszenia,members,status_zgloszen,grupa
WHERE zgloszenia.id='$_GET[id]' AND zgloszenia.przypisany = members.id AND zgloszenia.status = status_zgloszen.id_statusu AND zgloszenia.grupa = grupa.grupaid");
$wiersz = mysql_fetch_array($query) or die('Błšd zapytania '.mysql_error());


$numer = $wiersz['id'];
$unikalny_numer = $wiersz['ticketID'];
$date_add = $wiersz['date_add'];
$obszar = $wiersz['obszar'];
$jednostka_zglaszajaca = $wiersz['jednostka_zglaszajaca'];
$lokalizacja = $wiersz['lokalizacja'];
$temat = $wiersz['temat'];
$osoba_kontaktowa = $wiersz['osoba_kontaktowa'];
$telefon = $wiersz['telefon'];
$ktoid = $wiersz['memid'];
$kto = $wiersz['username'];
$close = $wiersz['opis_statusu'];

//1 div otwarty
echo '<div class="panel panel-default">
	<div class="panel-body">
        <div class="panel-data-right"><b><span class="glyphicon glyphicon-calendar"></span>   '.$date_add.'</b></div>
		<img class="img_notepad" align="left" src="img/notepad.jpg" alt="zgloszenie" style="width:60px">
        <h5>Przypisany do:&nbsp&nbsp<b>'.ucfirst($kto).'</b><br />Status Zgłoszenia:&nbsp&nbsp<b>'.ucfirst($close).'</b></h5><br />
		Jednostka zgłaszająca: <b>'.ucwords($jednostka_zglaszajaca).'</b><br />
		Lokalizacja: <b>'.ucwords($lokalizacja).'</b><br />
		Osoba kontaktowa:&nbsp&nbsp<b>'.ucwords($osoba_kontaktowa).'</b>&nbsp&nbsp&nbsp&nbspTelefon:&nbsp&nbsp<b>'.$telefon.'</b><br />
		Unikalny numer zgłoszenia: <b>&nbsp&nbsp'.$unikalny_numer.'</b>&nbsp&nbsp&nbsp&nbspObszar:&nbsp&nbsp<b>'.ucfirst($obszar).'</b><hr>';
		
		$sql2 = "SELECT * FROM `uploads` WHERE `id_zgl`='$numer' ORDER BY `data` ASC";
		 $wynik2 = mysql_query($sql2);
		$num = mysql_num_rows($wynik2);
		if( $num == 0)
				{
		
        echo '<form action="upload.php" method="post" enctype="multipart/form-data">
        	<input type="file" name="plik" id="plik">
			<input type="hidden" name="przypisany" value="'.$_SESSION['user_id'].'">
			<input type="hidden" name="zgloszenie" value="'.$numer.'">
            <input type="submit" value="Wyślij">
        </form>';
		
				} else {
					$sql3="SELECT `id`,`data`,`file`,`type`,`size`,`link` FROM `uploads`";

echo '<table class="table table-bordered"><thead><tr><th scope="col">#</th>
      <th scope="col">Data</th><th scope="col">Nazwa Pliku</th><th scope="col">Typ</th><th scope="col">Rozmiar</th><th scope="col">Link</th></tr></thead><tbody>';

	  $wynik3 = mysql_query($sql3);
while ($wiersz = mysql_fetch_array($wynik3, MYSQL_ASSOC))
{
$numer = $wiersz['id'];
$data = $wiersz['data'];
$file = $wiersz['file'];
$type = $wiersz['type'];
$size = $wiersz['size'];
$link = $wiersz['link'];

echo '<tr><td>'.$numer.'</td><td>'.$data.'</td><td>'.$file.'</td><td>'.$b=substr(strstr($type, "/"), 1).'</td><td>'.FileSizeConvert($size).'</td><td><a href="/zgloszenia/'.$link.'">Pobierz</a></td></tr>';

}

echo '<br /></tbody></table>';

echo '<form action="upload.php" method="post" enctype="multipart/form-data">
        	<input type="file" name="plik" id="plik">
			<input type="hidden" name="przypisany" value="'.$_SESSION['user_id'].'">
			<input type="hidden" name="zgloszenie" value="'.$numer.'">
            <input type="submit" value="Wyślij">
        </form>';
				}
		
		
		echo '<br /><br /><hr>
        <p>'.$temat.'</p>
		<div class="btn-group" role="group" aria-label="...">
        <a href="edytuj.php?id='.$numer.'"><button type="button" class="btn btn-default">Edytuj</button></a>
		<a href="zamknij.php?id='.$numer.'&user_id='.$ktoid.'"><button type="button" class="btn btn-default">Zamknij</button></a>
		<a href="usun.php?id='.$numer.'&user_id='.$ktoid.'"><button type="button" class="btn btn-default">Usuń</button></a></div></div></div>';

$sql = "SELECT * FROM `komentarze_zgl` WHERE `id_zgl`='$numer' ORDER BY `data_zgl` ASC";
        $wynik = mysql_query($sql);
		$num = mysql_num_rows($wynik);
		if( $num == 0)
				{

		$numer_nad = $numer;
		$kto1 = $kto;
		
echo 	'<div class="panel panel-default">
		<div class="panel-heading"><center><h4>Historia zgłoszenia</h4></center></div>
		<div class="panel-body"><br>
		<font color="red"><b>Brak historii zgłoszenia</b></font>
		</div></div>
		';//drugi div zamknięty
		}
		else
		{
// trzeci div otwarty
	echo'<div class="panel panel-default">
		<div class="panel-body">';
		while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
	{
$numer1 = $wiersz['id_zgl_hist'];
$numer_nad = $wiersz['id_zgl'];
$data = $wiersz['data_zgl'];
$kto1 = $kto;
$komentarz = $wiersz['komentarz'];

echo	'<label><b>Data:</b></label>&nbsp&nbsp'.$data.'<br />
		<br /><br />'.$komentarz.'<br />
		
		<hr>
        ';

}
echo '</div></div>';	
	}
		

echo'<div class="panel panel-default">
<div class="panel-heading"><center><h4>Aktualizacja</h4></center></div>
	<div class="panel-body">	
		<form action="add_koment.php" method=post>
		
		<input type="hidden" name="id_zgl" value="'.$numer_nad.'">
		<input type="hidden" name="przypisany" value="'.$kto1.'">
		<textarea name="komentarz" class="dodaj"></textarea><br />
		<center><button class="btn btn-default" type="submit" value="Aktualizuj" >Aktualizuj</button></center>
		</form>
        </div></div>';// trzeci div zamknięty	
		
//$sql1 = "SELECT * FROM `historia_zmian` WHERE `id_zgl`='$numer' ORDER BY `log_data` DESC";
$sql1 = "SELECT log_data,log_typ_zmiany,username FROM historia_zmian,members WHERE id_zgl='$numer'
 AND historia_zmian.user_id=members.id ORDER BY `log_data` DESC";
        $wynik1 = mysql_query($sql1);
		$num1 = mysql_num_rows($wynik1);
if( $num1 == 0){
		
	echo '<div class="panel panel-default">
	<div class="panel-heading"><center><h4>Historia zmian</h4></center></div>
	<div class="panel-body">
		<font color="red"><b>Brak historii zmian</b></font>
		</div></div>';
	} else {
		echo 	'<div class="panel panel-default">
		<div class="panel-heading"><center><h4>Historia zmian</h4></center></div>
	<div class="panel-body">
		<table class="table table-striped"><thead>
		<th>Data</th><th>Użytkownik</th><th>Typ zmiany</th>
		<thead><tbody>';
	while ($wiersz1 = mysql_fetch_array($wynik1, MYSQL_ASSOC)){
	//$id_zgl= $wiersz1['id_zgl'];
	$user_log= $wiersz1['username'];
	$log_data= $wiersz1['log_data'];
	$log_typ_zmiany= $wiersz1['log_typ_zmiany'];
	echo '<tr><td>'.$log_data.'</td><td>'.ucfirst($user_log).'</td><td>'.$log_typ_zmiany.'</td></tr>';
		}
		echo '
		</tbody></table>
		</div></div>';
		
	}

echo '</div><footer class="footer">';	
	
	
	
include "php/db_disconnect.php";

include_once ('include/stopka.html');
} else {
echo 'Zaloguj sie';
echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
