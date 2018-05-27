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
?>

<html>
<title>Zgłoszenia CSS</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/w3-theme-blue-grey.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="style_admin.css">
<body class="w3-theme-l5">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
require_once ('php/db_connect.php');
$cas_id = phpCAS::getUser();

$sql="SELECT count(id_user_cas) FROM `members` WHERE id_user_cas=$cas_id";
//sprawdzamy czy id cas jest w bazie aplikacji - musi mieć wartość 1
$result=mysql_query($sql);
$wiersz = mysql_fetch_row ($result);
$user = $wiersz[0];
//echo $user;
$imie = phpCAS::getAttribute('givenName');
//$nazwisko = phpCAS::getAttribute('sn');
$username1 = $imie;
$email = strtolower(phpCAS::getAttribute('mail'));

if ($user != 1) {
			echo '<br /><br /><br /><br /><br /><br /><br />
			<center>
			<div class="container">
				<div class="jumbotron">
					<div class="alert alert-info">
					<strong><h2>Brak Autoryzacji</h2></strong>
					<p>Nie jesteś zarejestrowanym  użytkownikiem w sytemie zgłoszeniowym. O możliwości zalogowania się do systemu zostaniesz powiadomiony mailem.</p>
					</div>
				</div>
			</div>
			</center> ';
					if ($username1 && $email && $cas_id) {
					$naglowki = "Reply-to: andrzej.jakubek@poczta.umcs.lublin.pl <andrzej.jakubek@poczta.umcs.lublin.pl>".PHP_EOL;
					$naglowki .= "From: andrzej.jakubek@poczta.umcs.lublin.pl <andrzej.jakubek@poczta.umcs.lublin.pl>".PHP_EOL;
					$naglowki .= "MIME-Version: 1.0".PHP_EOL;
					$naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 
					$adres = "andrzej.jakubek@poczta.umcs.lublin.pl";
					
					$temat = 'Nowy użytkownik w systemie  '.$username1;
					
					$tresc = 'Nowy użytkownik w Systemie zgłoszeniowym. 
					Jego dane: '.$username1.' '.$email.' '.$cas_id;
					
					$sql0 = "INSERT INTO `members`(`id`,`username`, `email`, `last_login`, `id_user_cas`) VALUES ('', '$username1', '$email', NOW(), '$cas_id')";
					$result0 = mysql_query($sql0);
					mail($adres, $temat, $tresc, $naglowki);
					}
    
                echo ('<META HTTP-EQUIV="Refresh" CONTENT="5;URL=http://umcs.pl">');
					exit();
				}
			else
				{
					//echo 'Znam Cię :) - jesteś w zarejestorwanym userem';
					$sql1="SELECT id,username,grupa,user_role,active FROM `members` WHERE id_user_cas=$cas_id LIMIT 1";
					$result1=mysql_query($sql1);
					while ($wiersz = mysql_fetch_array($result1, MYSQL_ASSOC)) {
						$user_id = $wiersz['id'];
						$username = $wiersz['username'];
						$grupa = $wiersz['grupa'];
						$rola = $wiersz['user_role'];
						$aktywne = $wiersz['active'];
						
					
					if ($aktywne == 1) {
					
					// XSS protection 
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;

                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

					unset($_SESSION['phpCAS']);
                    $_SESSION['username'] = $username;
					$_SESSION['grupa'] = $grupa;
					$_SESSION['rola'] = $rola;
					
					//logowanie userów - kto i kiedy
					$sql2="INSERT INTO login_members(log_id, user_id, log_data) VALUES (NULL, '$user_id', now())";
					$resul2=mysql_query($sql2);
					// oraz ustawienie statusu aktualnie zalogowanego użytkownika oraz daty ostatniego logowania
					$sql3="UPDATE `members` SET `last_login`= now() WHERE `id` = $user_id";
					$resul3=mysql_query($sql3);
					
					
					//Porównać dane z CAS z bazą i zrobić update jeżeli się różnią
					
					$sql4="SELECT username,email,id_user_cas FROM `members` WHERE id_user_cas=$cas_id";
					$result4=mysql_query($sql4);
					while ($wiersz = mysql_fetch_array($result4, MYSQL_ASSOC)) {
						$username2 = $wiersz['username'];
						$email2 = $wiersz['email'];
						$id_user_cas2 = $wiersz['id_user_cas'];
					}
						
						if ($username2!=$username1){
							$sql5="UPDATE `members` SET `username`='$username1' WHERE id_user_cas=$cas_id";
							$result5=mysql_query($sql5);
						}
						if($email2!=$email){
							$sql6="UPDATE `members` SET `email`='$email' WHERE id_user_cas=$cas_id";
							$result6=mysql_query($sql6);
						}	
										
					//header("Location: main.php");
						
					
					session_write_close();
					echo ('<META HTTP-EQUIV="Refresh" CONTENT="0;URL=main.php">');
					exit();
					// !!!!!!!!!!!!!! http://developer.jasig.org/cas-clients/php/1.3.3/docs/api/group__publicAuth.html !!!!!!!!!!!!
							}
							else{
							session_write_close();
							echo '<br /><br /><br /><br /><br /><br /><br />
			<center>
			<div class="container">
				<div class="jumbotron">
					<div class="alert alert-info">
					<strong><h2>Twoje konto jest nieaktywne</h2></strong>
					<p>O możliwości zalogowania się do systemu zostaniesz powiadomiony mailem.</p>
					</div>
				</div>
			</div>
			</center> ';
							
							
							//echo ('<META HTTP-EQUIV="Refresh" CONTENT="5;URL=index.php">');
							exit();	
								}
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

</body>
</html>