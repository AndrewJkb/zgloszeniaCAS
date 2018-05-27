<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {

if (isset($_POST['submit'])) { // Obsłuż formularz.
	
	// Zarejestruj uzytkownika w bazie danych.
	require_once ('php/db_connect.php'); // Poł±cz się z baz± danych.	
	
	// Utworz funkcję wstawiaj±c± przed znakami specjalnymi znak odwrotnego uko¶nika.
	function escape_data ($data) {
		global $link; // Potrzebujemy poł±czenia.
		if (ini_get('magic_quotes_gpc')) {
			$data = stripslashes($data);
		}
		return mysql_real_escape_string($data, $link);
	} // Koniec funkcji.
	
	$message = NULL; // Utwórz now±, pust± zmienn±.
	
	// SprawdĽ Skąd dzwonią
	if (empty($_POST['jednostka_zglaszajaca'])) {
		$jednostka_zglaszajaca = FALSE;
		$message .= '<p>Zapomniałe¶ podać Skąd dzwonią!</p>';
	} else {
		$jednostka_zglaszajaca = escape_data($_POST['jednostka_zglaszajaca']);
	}
	
	// SprawdĽ temat.
	if (empty($_POST['temat'])) {
		$temat = FALSE;
		$message .= '<p>Zapomniałe¶ wpisać treść zgłoszenia</p>';
	} else {
		$temat = escape_data($_POST['temat']);
	}
	
	// SprawdĽ osoba kontaktowa.
	if (empty($_POST['osoba_kontaktowa'])) {
		$osoba_kontaktowa = FALSE;
		$message .= '<p>Zapomniałe¶ wpisać osoby kontaktowej</p>';
	} else {
		$osoba_kontaktowa = escape_data($_POST['osoba_kontaktowa']);
	}	
		
	// SprawdĽ telefon.
	if (empty($_POST['telefon'])) {
		$telefon = FALSE;
		$message .= '<p>Zapomniałe¶ wpisać numer telefonu</p>';
	} else {
		$telefon = escape_data($_POST['telefon']);
	}

	if ($jednostka_zglaszajaca && $temat && $osoba_kontaktowa && $telefon ) { // Jeżeli wszystko jest OK.
		
		/* generujemy unikalny tracking ID i upewniamy się czy już niema takiego samego */
		$useChars = 'AEUYBDGHJLMNPQRSTVWXZ123456789';
		$trackingID = $useChars{mt_rand(0,29)};
		for($i=1;$i<10;$i++)
		{
		$trackingID .= $useChars{mt_rand(0,29)};
		}

		$trackID = escape_data($trackingID);
		
		// Sprawdzamy czy taki trackID istnieje 
		$sql = "SELECT `id` FROM `zgloszenia` WHERE ticketID = '".$trackID."' LIMIT 1";
		$wynik = mysql_query($sql);
		if ( mysql_fetch_row($wynik) != 0) {
		// Tracking ID nie jest unikalne, generujemy je jeszcze raz kilkukrotnie
		$trackingID  = $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= $useChars[mt_rand(0,29)];
		$trackingID .= substr(microtime(), -5);
		$trackID = escape_data($trackingID);
		
		$sql1 = "SELECT `id` FROM `zgloszenia` WHERE `ticketID` = '".$trackID."' Limit 1";
		$wynik1 = mysql_query($sql1);
			if (mysql_fetch_row($wynik) != 0) {
			echo " Houston mamy problem z generowaniem unikalengo id dla zgłoszenia";
			}
	
		}
		
		$przypisany = $_SESSION['user_id'];
		$session_grupa = $_SESSION['grupa'];
		// Utwórz zapytanie.
		$query = "INSERT INTO `zgloszenia` (`id`, `ticketID`, `date_add`, `jednostka_zglaszajaca`, `temat`, `osoba_kontaktowa`, `telefon`, `przypisany`, `status`, `grupa`) VALUES ('','$trackID', NOW(), '$jednostka_zglaszajaca', '$temat', '$osoba_kontaktowa', '$telefon', '$przypisany','0','$session_grupa')" or die('Błąd zapytania '.mysql_error());
		$result = @mysql_query ($query) or die('Błąd zapytania '.mysql_error()); // Wykonaj zapytanie.
	//
	$sql= "INSERT INTO `historia_zmian` (`log_id`, `user_id`, `id_zgl`, `log_data`, `log_typ_zmiany`) VALUES('','$przypisany',$getid,now(),'Zgłoszenie zamknięte')";
	} else {
		echo "Coś poszło nie tak";
			}
}
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');



?>
<div class="w3-container w3-card-2 w3-white w3-round w3-margin"><br>
        <img src="img/img_avatar.png" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">
		<span class="w3-right w3-opacity"><b></b></span>
        <h4></h4><br>
		<hr class="w3-clear">
        <p></p>
          <div class="w3-row-padding" style="margin:0 -16px">
            <div class="w3-half">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post>Zgłoszenia<br><b>Dodawanie:</b>
<a href="javascript: history.go(0)">Odśweż</a>&nbsp;&nbsp;<a href="javascript:history.back()">Powrót</a>
<label class="dodaj"><b>Skąd:</b></label><input type="text" name="jednostka_zglaszajaca" size="150" class="dodaj"><br />
<label class="dodaj"><b>Opis:</b></label><textarea name="temat" rows="15" cols="30" class="dodaj"></textarea><br />
<label class="dodaj"><b>Osoba Kontaktowa:</b></label><input type="text" name="osoba_kontaktowa" size="150" class="dodaj"><br />
<label class="dodaj"><b>Telefon:</b></label><input type="text" name="telefon" size="40" class="dodaj"><br />
<input type="submit" name="submit" value="Dodaj" class="dodaj">
</form>
	<br /></div>
            <div class="w3-half">
			</div>
        </div>
        </div>

<center>
<table border="0"><tbody>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post>
<tr height="30" ><td width="125">Zgłoszenia<br><b>Dodawanie:</b></td>
<td><a href="javascript: history.go(0)">Odśweż</a>&nbsp;&nbsp;<a href="javascript:history.back()">Powrót</a></td></tr>
<tr height="30" ><td><b>Skąd:</b></td><td><input type="text" name="jednostka_zglaszajaca" size="150"></td></tr>
<tr ><td><b>Opis:</b></td><td><textarea name="temat" rows="15" cols="30"></textarea></td></tr>
<tr height="30" ><td><b>Osoba Kontaktowa:</b></td><td><input type="text" name="osoba_kontaktowa" size="150"></td></tr>
<tr height="30" ><td><b>Telefon:</b></td><td><input type="text" name="telefon" size="40"></td></tr>
<!-- <tr ><td colspan="2" align="center"><input type="submit" name="submit" value="Dodaj"></td></tr>-->
</form>
</tbody></table>
</body>
</html>

<?php
include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>