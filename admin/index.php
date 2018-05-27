<?php

include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include_once '../auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true || login_check($mysqli) == true) {

$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');
include_once ('../function.php');
include "../php/db_connect.php";
list($rola) = mysql_fetch_row(mysql_query("SELECT members.user_role from members WHERE members.id = $id_user_session"));
//Domyślne wartości, odpowiednio liczby rekordów na strone i przesunięcia 

echo '<div class="panel panel-default">
	<div class="panel-body">';

$sql1= "SELECT COUNT(*) FROM `members` WHERE active=0";		
$ile_nieaktywnych = mysql_num_rows(mysql_query($sql1));

	if ($ile_nieaktywnych == 0){
		echo '<center>
			<div class="container">
					<div class="alert alert-info">
					<strong><h2>Brak nieaktywnych użytkowników</h2></strong>
					</div>
				</div>
		
			</center>';
	}
		else{	   
	   echo '<h3>Nieaktywni użytkownicy</h3>
	   <table class="table">
	   <thead>
      <tr>
        <th>Imię</th>
        <th>Email</th>
        <th>ID CAS</th>
        <th>Aktywny</th>
      </tr>
    </thead>
    <tbody>';

$sql= "SELECT `id`,`username`,`email`,`id_user_cas`,`active` FROM `members` WHERE active=0";
$wynik = mysql_query($sql);


while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{
$username = ucfirst($wiersz['username']);
$email = strtolower($wiersz['email']);
$cas_user = $wiersz['id_user_cas'];
$aktywny = $wiersz['active'];
$id = $wiersz['id'];

echo '<tr>
        <td>'.$username.'</td>
        <td>'.$email.'</td>
        <td>'.$cas_user.'</td><td>
		<input type="checkbox" name="active" value="'.$id.'"></td>
		<td><select	name="obszar" class="btn btn-default">';
	  $obszar = "SELECT `grupaid`,`nazwa` FROM `grupa`";
$wynik_obszar = mysql_query($obszar) or die(mysql_error()) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik_obszar, MYSQL_ASSOC))
{
$grupaid = $wiersz['grupaid'];
$grupanazwa = $wiersz['nazwa'];

echo '<option name="'.$grupaid.'" value="'.$grupaid.'">'.htmlentities(ucfirst($grupanazwa)).'</option>';
}

echo '</select>
		</td>
	</tr>';
	}
	
	
	echo '</tbody>
  </table>';
		}
		
		
// TODO akceptacja===================
 echo '<h3>Do odchaczenia zrobione</h3>
	   <table class="table">
	   <thead>
      <tr>
        <th>ID</th>
        <th>Tekst</th>
        <th>Czyj Pomysł</th>
      </tr>
    </thead>
    <tbody>';

$sql= "SELECT do_zrobienia.id,do_zrobienia.tekst,members.username FROM `do_zrobienia`,`members` WHERE status = 0 and do_zrobienia.kto_dodal=members.id";
$wynik = mysql_query($sql);


while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{
$id = $wiersz['id'];
$tekst = ucfirst($wiersz['tekst']);
$user = ucfirst($wiersz['username']);

echo '<tr>
        <td>'.$id.'</td>
        <td>'.$tekst.'</td>
        <td>'.$user.'</td><td>
		<input type="checkbox" name="todo[]" value="'.$id.'"></td>
	</tr>';
	}
	
	
	echo '</tbody>
  </table> <br /><br /><br /><br /><h3>Dodane Pliki</h3><br />';

  
  
include "../php/db_connect.php";

$sql="SELECT uploads.id,`data`,`id_zgl`,`username`,`file`,`type`,`size`,`link` FROM `uploads`,`members` where uploads.user_id = members.id";

echo '<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Data</th>
      <th scope="col">ID Zgłoszenia</th>
      <th scope="col">Użytkownik</th>
	  <th scope="col">Nazwa Pliku</th>
      <th scope="col">Typ</th>
      <th scope="col">Rozmiar</th>
      <th scope="col">Link</th>
    </tr>
  </thead>
  <tbody>';
$wynik = mysql_query($sql);
while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
{
$numer = $wiersz['id'];
$data = $wiersz['data'];
$id_zgl = $wiersz['id_zgl'];
$username = $wiersz['username'];
$file = $wiersz['file'];
$type = $wiersz['type'];
$size = $wiersz['size'];
$link = $wiersz['link'];

echo '<tr>
      <td>'.$numer.'</td>
      <td>'.$data.'</td>
      <td><a href="zobacz.php?id='.$id_zgl.'">'.$id_zgl.'</a></td>
      <td>'.$username.'</td>
      <td>'.$file.'</td>
      <td>'.$b=substr(strstr($type, "/"), 1).'</td>
      <td>'.FileSizeConvert($size).'</td>
      <td><a href="'.$link.'">Pobierz</a></td>      
    </tr>';

}
include "../php/db_disconnect.php";
echo '<br /></tbody>
</table>';





      
	 echo'</div>
		</div></div>';

echo '<footer style="text-align:center">';
include "../php/db_disconnect.php";


include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=../index.php">');
}
?>