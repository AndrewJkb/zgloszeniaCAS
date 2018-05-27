
<?php
echo '<select name="przypisany">';
//testowanie
$users_przypisany = "SELECT id, username FROM members";
$wynik_przypisany = mysql_query($users_przypisany) or die(mysql_error()) or die(mysql_error());
while ($wiersz = mysql_fetch_array($wynik_przypisany, MYSQL_ASSOC))
{
$id = $wiersz['id'];
$user = $wiersz['username'];

echo '<option value="'.$id.'">'.$user.'</option>';


function change($i){
$sql = "SELECT id, username FROM members";
$wynik = mysql_query($sql)or die(mysql_error()) or die(mysql_error())
$wiersz = mysql_fetch_row($wynik)

if ($i == 0) {
    return '<b style="color:red;">Nie przypisany</b>';
} elseif ($i == $wiersz[0]) {
    return htmlentities(ucfirst($wiersz[1]));
}
}



?>