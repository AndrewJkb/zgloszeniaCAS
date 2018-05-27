<html>

<form action="mail.php" method="post">
Aderes e-mail: <input type="text" name="email" /><br />
<input type="submit" value="Wyœlij" />
</form>

<?php
$naglowki = "Reply-to: serwis@umcs.pl <serwis@umcs.pl>".PHP_EOL;
$naglowki .= "From: serwis@umcs.pl <serwis@umcs.pl>".PHP_EOL;
$naglowki .= "MIME-Version: 1.0".PHP_EOL;
$naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 

$adres = "serwis@umcs.pl";
if (isSet($_POST['email'])) {
$temat = 'Zdalna pomoc - LubMAN UMCS';
$email = $_POST['email'];
$tresc = 'Proszê pobraæ i uruchomiæ
		https://get.teamviewer.com/wb65qde

--
Pozdrawiamy

Sekcja Obs³ugi Informatycznej - LubMAN UMCS

------------------------------------------
LubMAN UMCS
Uniwersytet Marii Curie-Sk³odowskiej w Lublinie tel. 81 537 26 03

Niniejsza wiadomo¶æ elektroniczna lub jej za³±czniki mog± zawieraæ poufne lub chronione prawem informacje, które s± przeznaczone wy³±cznie dla wskazanego w nich adresata. Je¿eli nie jeste¶ adresatem wiadomo¶ci, prosimy o jej nieujawnianie, zawiadomienie nadawcy o jej otrzymaniu oraz usuniêcie wraz z wszystkimi za³±cznikami. Dziêkujemy.
';
if ($temat != "" || $email != "" || $tresc != "") {

if (mail($email, $temat, $tresc)) {
echo "Mail zosta³ wys³any.";
}
else {
echo "Wyst¹pi³ b³¹d. Mail nie zosta³ wys³any.";
} }
else {
echo "Niekompletne dane.";
} }

echo '-'.$tresc.'-';
?>

</html>