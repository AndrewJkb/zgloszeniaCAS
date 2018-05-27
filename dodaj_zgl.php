<?php
//include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
//include_once 'auth.php';

//sec_session_start();

//if (phpCAS::checkAuthentication() == true) {

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
	
	// SprawdĽ jednostkę zgłąszającą
	if (empty($_POST['jednostka_zglaszajaca'])) {
		$jednostka_zglaszajaca = FALSE;
		$message .= '<p>Zapomniałe¶ podać jednostkę zgłaszającą!</p>';
	} else {
		$jednostka_zglaszajaca = ucwords(strtolower(escape_data($_POST['jednostka_zglaszajaca'])));
		//echo $jednostka_zglaszajaca;
		//sleep(10);
		
	}
	
	// SprawdĽ lokalizacja.
	if (empty($_POST['lokalizacja'])) {
		$lokalizacja = FALSE;
		$message .= '<p>Zapomniałe¶ wpisać loklaizację</p>';
	} else {
		$lokalizacja = ucwords(strtolower(escape_data($_POST['lokalizacja'])));
		//echo $lokalizacja;
		//sleep(10);
	}
	
	// SprawdĽ temat.
	if (empty($_POST['temat'])) {
		$temat = FALSE;
		$message .= '<p>Zapomniałe¶ wpisać treść zgłoszenia</p>';
	} else {
		$temat = ucwords(strtolower(mysql_real_escape_string($_POST['temat'])));
		//echo $temat;
		//sleep(10);
	}
	
	// SprawdĽ osoba kontaktowa.
	if (empty($_POST['osoba_kontaktowa'])) {
		$osoba_kontaktowa = FALSE;
		$message .= '<p>Zapomniałe¶ wpisać osoby kontaktowej</p>';
	} else {
		$osoba_kontaktowa = ucwords(strtolower(escape_data($_POST['osoba_kontaktowa'])));
		//echo $osoba_kontaktowa;
		//sleep(10);
	}	
		
	// SprawdĽ telefon.
	if (empty($_POST['telefon'])) {
		$telefon = FALSE;
		$message .= '<p>Zapomniałe¶ wpisać numer telefonu</p>';
	} else {
		$telefon = escape_data($_POST['telefon']);
		//echo $telefon;
		//sleep(10);
	}

	if ($jednostka_zglaszajaca && $lokalizacja && $temat && $osoba_kontaktowa && $telefon) { // Jeżeli wszystko jest OK.
		
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
		
		$query = "INSERT INTO `zgloszenia` (`id`,`ticketID`, `date_add`, `jednostka_zglaszajaca`, `lokalizacja`, `temat`, `osoba_kontaktowa`, `telefon`, `przypisany`, `status`, `grupa`) 
		VALUES (NULL, '$trackID', NOW(), '$jednostka_zglaszajaca', '$lokalizacja', '$temat', '$osoba_kontaktowa', '$telefon', '$przypisany', '0', '$session_grupa')";
		$result = @mysql_query ($query) or die('Błąd zapytania '.mysql_error()); // Wykonaj zapytanie.
	
		//$sql2 = "SELECT `id` FROM `zgloszenia` WHERE `ticketID` = '$trackID'";
		//list($getid) = mysql_fetch_row(mysql_query($sql2));
	
	
	//$sql3= "INSERT INTO `historia_zmian` (`log_id`, `user_id`, `id_zgl`, `log_data`, `log_typ_zmiany`) VALUES(NULL, '$przypisany', '$getid', now(),'Dodano zgłoszenie')";
	//$result3 = @mysql_query ($sql3) or die('Błąd zapytania '.mysql_error()); // Wykonaj zapytanie.
	} else {
		echo "Coś poszło nie tak";
			}
}
$page_title = 'Zgłoszenia';
//include_once ('include/naglowek.html');
	
?>
<div class="panel panel-default">
<div class="panel-heading"><center><h4>Dodawanie</h4></center></div>
		<div class="panel-body">
        
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post>
		<a href="javascript: history.go(0)" class="btn btn-default">Odśweż</a>
		&nbsp;&nbsp;<a href="javascript:history.back()" class="btn btn-default">Powrót</a>
		<br /><br /><br />
		<label class="dodaj"><b>Jednostka zgłaszająca:</b></label>
		<input type="text" name="jednostka_zglaszajaca" size="50" class="dodaj"><br />
		<label class="dodaj"><b>Lokalizacja:</b></label>
		<input type="text" name="lokalizacja" size="50" class="dodaj"><br />
		<label class="dodaj"><b>Opis:</b></label>
		<textarea name="temat" rows="15" cols="15" class="dodaj"></textarea><br />
		<label class="dodaj"><b>Osoba Kontaktowa:</b></label>
		<input type="text" name="osoba_kontaktowa" size="50" class="dodaj"><br />
		<label class="dodaj"><b>Telefon:</b></label>
		<input type="text" name="telefon" size="50" class="dodaj"><br />
		<center><button type="submit" name="submit" value="Dodaj" class="btn btn-default">Dodaj</button></center>
		</form>
        </div>
        </div>


<?php

echo '</div><footer class="footer">';
//include_once ('include/stopka.html');
//} else {
//		echo 'Zaloguj sie';
//		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
//}
?>