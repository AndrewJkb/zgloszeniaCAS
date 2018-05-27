<?php

include_once 'auth.php';

/* utworzenie zmiennych */
$folder_upload="pliki";
$plik_nazwa=$_FILES['plik']['name'];
$plik_lokalizacja=$_FILES['plik']['tmp_name']; //tymczasowa lokalizacja pliku
$plik_mime=$_FILES['plik']['type']; //typ MIME pliku wysłany przez przeglądarkę
$plik_rozmiar=$_FILES['plik']['size'];
$plik_blad=$_FILES['plik']['error']; //kod błędu

/* sprawdzenie, czy plik został wysłany */
if (!$plik_lokalizacja) {
exit("Nie wysłano żadnego pliku");
}

/* sprawdzenie błędów */
switch ($plik_blad) {
case UPLOAD_ERR_OK:
break;
case UPLOAD_ERR_NO_FILE:
exit("Brak pliku.");
break;
case UPLOAD_ERR_INI_SIZE:
case UPLOAD_ERR_FORM_SIZE:
exit("Przekroczony maksymalny rozmiar pliku.");
break;
default:
exit("Nieznany błąd.");
break;
}

/* sprawdzenie rozszerzenia pliku - dzięki temu mamy pewność, że ktoś nie zapisze na serwerze pliku .php */
$dozwolone_rozszerzenia=array("jpeg", "jpg", "tiff", "tif", "png", "gif", "pdf");
$plik_rozszerzenie=pathinfo(strtolower($plik_nazwa), PATHINFO_EXTENSION);


$plik_nazwa_new = $plik_nazwa;
    $co=array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż', 'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż');
    $na=array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', 'A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z');
    
    $plik_nazwa_new = str_replace($co, $na, $plik_nazwa_new); 


if (!in_array($plik_rozszerzenie, $dozwolone_rozszerzenia, true)) {
exit("Niedozwolone rozszerzenie pliku.");
}

/* przeniesienie pliku z folderu tymczasowego do właściwej lokalizacji */
if (!move_uploaded_file($plik_lokalizacja, $folder_upload."/".$plik_nazwa_new)) {
exit("Nie udało się przenieść pliku.");
}

$link = $folder_upload."/".$plik_nazwa_new;
$przypisany = $_POST['przypisany'];
$zgloszenie = $_POST['zgloszenie'];

include "php/db_connect.php";
 
 $sql="INSERT INTO uploads(data,id_zgl,user_id,file,type,size, link) VALUES( now(),'$zgloszenie','$przypisany','$plik_nazwa_new','$plik_mime','$plik_rozmiar','$link')";
  mysql_query($sql);
  $sql1 = "INSERT INTO `historia_zmian` (log_id, user_id, id_zgl, log_data, log_typ_zmiany) VALUES (NULL, '$przypisany', '$zgloszenie',NOW(),'Dodano plik')";
		   $wynik1 = mysql_query($sql1) or die('Błąd zapytania do historia_zmian'.mysql_error());
		   
  
include "php/db_disconnect.php";

echo ('<META HTTP-EQUIV="Refresh" CONTENT="0;URL=zobacz.php?id='.$getid.'">');

?>