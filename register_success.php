<?php

include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
sec_session_start();

if (login_check($mysqli) == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Zarejestrowałeś się</title>
        <link rel="stylesheet" href="styles/main.css" />
    </head>
    <body>
        <h1>Gratukacje</h1>
        <p>Teraz już możesz się <a href="main.php">logować</a> </p>
    </body>
</html>
<?php
include_once ('include/stopka.html');
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=main.php">');
}
?>