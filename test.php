<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');

$admins = 1;
$admins .= 2;


if ($admins == true){
	
	
	echo 'działa'.$admins;
}
else {
	echo 'nie działa';
}



include_once ('include/stopka.html');

} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>