<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'auth.php';

sec_session_start();

if (phpCAS::checkAuthentication() == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');


echo '<div class="w3-container w3-card-2 w3-white w3-round w3-margin">
<br /><br />

Wyszykiwać można po:<br />
<ul>
<li>lokalizacji</li>
<li>opisie np. sap, instalacja sap</li>
<li>numerze telefonu ( zazwyczaj numer skrócony)</li>
<li>osobie kontaktowej</li>
<li>unikalnym numerze zgłoszenia</li>
</ul>
<center><form action="wyniki.php" method=GET><br /><br /><br />
<b>Szukaj:</b>&nbsp;&nbsp;<input type="text" name="zapytanie" size="30" placeholder="Szukany tekst"><br /><br /><br />
<button type="submit" id="przycisk">Szukaj</button><br /><br /><br />
</form>
</center>
</div>';


echo '</div>
	</div>
    <!-- End Grid -->
  </div>
  
<!-- End Page Container -->
</div><br>';

include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
