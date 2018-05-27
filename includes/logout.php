<?php
include_once 'psl-config.php';
include_once 'functions.php';
include_once 'db_connect.php';


sec_session_start();
if (login_check($mysqli) == true) {

//logowanie userów - kto i kiedy 
$user_id = $_SESSION['user_id'];
//echo $user_id;

$mysqli->query("UPDATE `members` SET `active`= 0 WHERE `id` = $user_id");

// Unset all session values 
$_SESSION = array();

// get session parameters 
$params = session_get_cookie_params();

// Delete the actual cookie. 
setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

// Destroy session 
session_destroy();
header("Location: ../index.php");
exit();
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}