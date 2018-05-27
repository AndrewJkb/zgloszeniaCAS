<?php
include 'function.php';
echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload</title>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';


include "php/db_connect.php";

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

echo '<tr>zobacz.php?id=
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
include "php/db_disconnect.php";
echo '<br /></tbody>
</table></body>
</html>';
?>