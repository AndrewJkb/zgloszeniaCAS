<?php 
require_once 'CAS/config.php';
require_once $phpcas_path . 'CAS.php';
//phpCAS::setDebug();
phpCAS::setVerbose(true);
phpCAS::client(SAML_VERSION_1_1, $cas_host, $cas_port, $cas_context);
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
if (isset($_REQUEST['logout'])) {
	phpCAS::logout();
}

$user_cas_id = phpCAS::getUser();

/*
teoria:
pobrać id usera z phpcas(czyli domena).
sprawdzić czy zgadza się z tym w bazie user_cas_id = id_user_cas
dodac do sesji id , grupę i username z is_user_cas.
*/

require_once ('php/db_connect.php');

$sql="SELECT count(id_user_cas) FROM `members` WHERE id_user_cas=$user_cas_id";
$result=mysql_query($sql);
$wiersz = mysql_fetch_row ($result);
$user = $wiersz[0];
//echo $user;
$imie = phpCAS::getAttribute('givenName');
$nazwisko = phpCAS::getAttribute('sn');
$email = strtolower(phpCAS::getAttribute('mail'));
$cas_id = phpCAS::getUser();


if ($user != 1) {
			echo 'Nie jesteś zautoryzowanym  użytkownikiem.';
			echo '<br /> Potwierdź swoje dane.
			<form action="'.$_SERVER['PHP_SELF'].'" metod=post>
			<label class="dodaj"><b>Użytkownik</b></label><input type="text" name="jednostka_zglaszajaca" size="50" class="dodaj" value="'.$imie.' '.$nazwisko.'"><br />
			<label class="dodaj"><b>Email</b></label><input type="text" name="jednostka_zglaszajaca" size="50" class="dodaj" value="'.$email.'"><br />
			<label class="dodaj"><b>ID z CAS</b></label><input type="text" name="jednostka_zglaszajaca" size="50" class="dodaj" value="'.$cas_id.'"><br />
			<button type="submit" name="submit" value="Zatwierdź" class="w3-btn w3-theme-d1 w3-margin-bottom">Zatwierdź</button>
			</form>
			';
			}
			else
				{
					//echo 'Znam Cię :) - jesteś w zarejestorwanym userem';
					$sql1="SELECT id, username,grupa FROM `members` WHERE id_user_cas=$user_cas_id LIMIT 1";
					$result1=mysql_query($sql1);
					while ($wiersz = mysql_fetch_array($result1, MYSQL_ASSOC)) {
						$user_id = $wiersz['id'];
						$username = $wiersz['username'];
						$grupa = $wiersz['grupa'];
						
					// XSS protection 
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;

                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

                    $_SESSION['username'] = $username;
					$_SESSION['grupa'] = $grupa;
					/*
					//logowanie userów - kto i kiedy
					$mysqli->query("INSERT INTO login_members(log_id, user_id, log_data) VALUES ('', '$user_id', now())");
					// oraz ustawienie statusu aktualnie zalogowanego użytkownika UPDATE `members` SET `active`=`1` WHERE
					$mysqli->query("UPDATE `members` SET `active`= 1 WHERE `id` =$user_id");
					
					!!!!!!!!!!!!!! http://developer.jasig.org/cas-clients/php/1.3.3/docs/api/group__publicAuth.html !!!!!!!!!!!!
					
					// oraz ustawienie statusu aktualnie zalogowanego użytkownika UPDATE `members` SET `last_login`= now() WHERE
					$mysqli->query("UPDATE `members` SET `last_login`= now() WHERE `id` =$user_id");
					*/
						}
					/*	echo '<br /><br />Twoje ID :  '.$_SESSION['user_id'].'
						<br /><br />Twój CAS ID : '.phpCAS::getUser().'
						<br /><br />Twój użytkownik :  '.htmlentities(ucfirst($_SESSION['username'])).'
						<br /><br />Twoja grupa :  '.$_SESSION['grupa'].'
						<br /><br />';

						echo '<br />Dane z CAS\'a<br /><br />';	
						echo phpCAS::getAttribute('givenName').' '.phpCAS::getAttribute('sn').'<br />';
						echo phpCAS::getAttribute('uidNumber').'<br />';
						echo strtolower(phpCAS::getAttribute('mail')).'<br />';						*/
				}
		//echo phpCAS::checkAuthentication();
		//echo '<p><a href="dodaj.php">Dodaj</a></p>';
	  //echo '<p><a href="?logout=">Logout</a></p>';
?>