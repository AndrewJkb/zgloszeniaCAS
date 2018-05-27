<html>
<title>Zgłoszenia CSS</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/w3-theme-blue-grey.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="style_admin.css">
<body class="w3-theme-l5">

<?php 
require_once 'CAS/config.php';
require_once $phpcas_path . '/CAS.php';
//phpCAS::setDebug();
phpCAS::setVerbose(true);
phpCAS::client(SAML_VERSION_1_1, $cas_host, $cas_port, $cas_context);
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
if (isset($_REQUEST['logout'])) {
	phpCAS::logout();
}

require_once ('php/db_connect.php');
$cas_id = phpCAS::getUser();

$sql="SELECT count(id_user_cas) FROM `members` WHERE id_user_cas=$cas_id";
$result=mysql_query($sql);
$wiersz = mysql_fetch_row ($result);
$user = $wiersz[0];
//echo $user;
$imie = phpCAS::getAttribute('givenName');
$nazwisko = phpCAS::getAttribute('sn');
$username = $imie + ' ' +$nazwisko;
$email = strtolower(phpCAS::getAttribute('mail'));



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
					$sql1="SELECT id, username,grupa FROM `members` WHERE id_user_cas=$cas_id LIMIT 1";
					$result1=mysql_query($sql1);
					while ($wiersz = mysql_fetch_array($result1, MYSQL_ASSOC)) {
						$user_id = $wiersz['id'];
						$username = $wiersz['username'];
						$grupa = $wiersz['grupa'];
					}	
					// XSS protection 
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;

                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

                    $_SESSION['username'] = $username;
					$_SESSION['grupa'] = $grupa;
					
					//logowanie userów - kto i kiedy
					$sql2="INSERT INTO login_members(log_id, user_id, log_data) VALUES ('', '$user_id', now())";
					$resul2=mysql_query($sql2);
					// oraz ustawienie statusu aktualnie zalogowanego użytkownika oraz daty ostatniego logowania
					$sql3="UPDATE `members` SET `active`= 1, `last_login`= now() WHERE `id` =$user_id";
					$resul3=mysql_query($sql3);
					//Porównać dane z CAS z bazą i zrobić update jeżeli się różnią
					
					$sql4="SELECT username,email,id_user_cas FROM `members` WHERE id_user_cas=$cas_id";
					$result4=mysql_query($sql4);
					while ($wiersz = mysql_fetch_array($result4, MYSQL_ASSOC)) {
						$username2 = $wiersz['username'];
						$email2 = $wiersz['email'];
						$id_user_cas2 = $wiersz['id_user_cas'];
					}
					
					echo $username2.' == '.$username.'<br />';
					echo $email2 .'== '.$email.'<br />';
					echo $id_user_cas2 .'== '.$cas_id.'<br />';
					
					if ($username2 != $username){
						$sql5="UPDATE `members` SET `username`=$username WHERE id_user_cas=$cas_id";
								$result5=mysql_query($sql5);
					}
						if($email2 != $email){
								$sql6="UPDATE `members` SET `email`=$email WHERE id_user_cas=$cas_id";
								$result6=mysql_query($sql6);
						}								
							/*if($id_user_cas2 != $cas_id){
								$sql7="UPDATE `members` SET `username`=$username, `email`=$email, `id_user_cas`=$cas_id";
								$result7=mysql_query($sql7);
							}*/
						
						
					
					//sleep(10);
					
					//header("Location: main.php");
					//exit();
					// !!!!!!!!!!!!!! http://developer.jasig.org/cas-clients/php/1.3.3/docs/api/group__publicAuth.html !!!!!!!!!!!!
					//}
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

</body>
</html>