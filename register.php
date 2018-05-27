<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';

include_once 'includes/db_connect.php';
sec_session_start();

if (login_check($mysqli) == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');
echo '';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Formularz rejestracyjny</title>
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
        <link rel="stylesheet" href="style_admin.css" />
    </head>
    <body>
	<center>
<div class="w3-container w3-card-2 w3-white w3-round w3-margin"><br>
        <!-- <img src="img/img_avatar.png" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">-->
        <span class="w3-right w3-opacity"></span>
        <h4></h4><br>    

	<table border="0" width="450"><tr><td align="center" valign="middle"><h1>Zarejestruj Użytkownika </h1></td></tr>

		<?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
        <tr><td>
		<ul>
            <li>Nazwa użytkownika może zawierać tylko cyfry, duże i małe litery oraz znaki podkreślenia</li>
            <li>Pole e-mail musi mieć poprawny format</li>
            <li>Hasło musi mieć co najmniej 8 znaków</li>
            <li>Hasło musi zawierać:
                <ul>
                    <li>Przynajmniej jedną dużą literę (A..Z)</li>
                    <li>Przynajmniej jedną małą literę (a..z)</li>
                    <li>Przynajmniej jedną cyfrę 0..9)</li>
                </ul>
            </li>
            <li>hasła podane muszą byc identyczne</li>
        </ul>
		</td></tr>
		<tr><td><center>
        <form method="post" name="registration_form" action="<?php echo esc_url($_SERVER['PHP_SELF']);?>">
            <label><b>Użytkownik:</b></label><br />
			<input type='text' name='username' id='username' /><br />
            <label><b>Email:</b></label><br />
			<input type="text" name="email" id="email" /><br />
            <label><b>Hasło:</b></label><br />
			<input type="password" name="password" id="password"/><br>
            <label><b>Powtórz hasło password:</b> </label><br />
			<input type="password" name="confirmpwd" id="confirmpwd" /><br />
			<label><b>Grupa</b>:</label><br />
<?php
			echo '<select name=\"grupa\">';

include "php/db_connect.php";

$sql = "SELECT * FROM grupa";
$wynik = mysql_query($sql) or die(mysql_error()) or die(mysql_error());

while ($wiersz = mysql_fetch_array($wynik, MYSQL_ASSOC))
            {
              $grupa = $wiersz['grupaid'];
              $nazwa = $wiersz['nazwa'];
              echo '<option name="'.$grupa.'" value="'.$grupa.'">'.htmlentities(ucfirst($nazwa)).'</option>';
            }
include "php/db_disconnect.php";
 echo  '</select>';
  
 ?>

			<br />


			<button type="button" id="przycisk" value="Register" onclick="return regformhash(this.form,this.form.username,
			this.form.email,this.form.password,this.form.confirmpwd,this.form.grupa);">Zarejestruj</button>
    </form></center>
        <p>Powrót do strony <a href="main.php">głównej</a>.</p>
		</td></tr></table>
		</center>
    </body>
</html>

<?php
include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=main.php">');
}
?>
