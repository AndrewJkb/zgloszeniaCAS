<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Paginacja</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
include 'php/db_connect.php';
        //musimy wyciągnąć z bazy informacje o ilości postów ogólnie do wyliczenia ilości stron
        //celowo nie kożystamy z SQL_CALC_FOUND_ROWS, bo zależy nam na zabezpieczeniu się przed wś****skimi 
        //użytkownikami, którzy zmodyfikują url i będą chcieli wejść na stronę jaka nie istnieje
        $query = "SELECT COUNT(*) as all_posts FROM zgloszenia";
        $result = mysql_query($query) or die (mysql_error());
        $row = mysql_fetch_array($result);
        extract($row);
        
        $onpage = 10; //ilość newsów na stronę
        $navnum = 3; //ilość wyświetlanych numerów stron, ze względów estetycznych niech będzie to liczba nieparzysta
        $allpages = ceil($all_posts/$onpage); //wszysttkie strony to zaokrąglony w górę iloraz wszystkich postów i ilości postów na stronę
        
        //sprawdzamy poprawnośc przekazanej zmiennej $_GET['page'] zwróć uwage na $_GET['page'] > $allpages
        if(!isset($_GET['page']) or $_GET['page'] > $allpages or !is_numeric($_GET['page']) or $_GET['page'] <= 0){
                $page = 1;
        }else{
                $page = $_GET['page'];
        }
        $limit = ($page - 1) * $onpage; //określamy od jakiego newsa będziemy pobierać informacje z bazy danych
                
        $query = "SELECT * FROM zgloszenia ORDER BY id DESC LIMIT $limit, $onpage";
        $result = mysql_query($query) or die (mysql_error());
        
        while($row = mysql_fetch_array($result)){
                echo "<h1>".$row['id']."</h1>";
                echo "<p>".$row['temat']."</p>";
                echo "<hr>";
        }
        
        //zabezpieczenie na wypadek gdyby ilość stron okazała sie większa niż ilośc wyświetlanych numerów stron
        if($navnum > $allpages){
                $navnum = $allpages;
        }
        
        //ten fragment może być trudny do zrozumienia
        //wyliczane są tu niezbędne dane do prawidłowego zbudowania pętli
        //zmienne są bardzo opisowę więc nie będę ich tłumaczyć
        $forstart = $page - floor($navnum/2);
        $forend = $forstart + $navnum;
        
        if($forstart <= 0){ $forstart = 1; }
        
        $overend = $allpages - $forend;
        
        if($overend < 0){ $forstart = $forstart + $overend + 1; }
        
        //ta linijka jest ponawiana ze względu na to, że $forstart mogła ulec zmianie
        $forend = $forstart + $navnum;
        //w tych zmiennych przechowujemy numery poprzedniej i następnej strony
        $prev = $page - 1;
        $next = $page + 1;
        
        //nie wpisujemy "sztywno" nazwy skryptu, pobieramy ja od serwera
        $script_name = $_SERVER['SCRIPT_NAME'];
        
        //ten fragment z kolei odpowiada za wyślwietenie naszej nawigacji
        echo "<div id=\"nav\"><ul>";
        if($page > 1) echo "<li><a href=\"".$script_name."?page=".$prev."\">Poprzednia</a></li>";
        if ($forstart > 1) echo "<li><a href=\"".$script_name."?page=1\">[1]</a></li>";
        if ($forstart > 2) echo "<li>...</li>";
        for($forstart; $forstart < $forend; $forstart++){
                if($forstart == $page){
                        echo "<li class=\"current\">";
                }else{
                        echo "<li>";
                }
                echo "<a href=\"".$script_name."?page=".$forstart."\">[".$forstart."]</a></li>";
        }
        if($forstart < $allpages) echo "<li>...</li>";
        if($forstart - 1 < $allpages) echo "<li><a href=\"".$script_name."?page=".$allpages."\">[".$allpages."]</a></li>";
        if($page < $allpages) echo "<li><a href=\"".$script_name."?page=".$next."\">Następna</a></li>";
        echo "</ul></div><div class=\"clear\">";
		

} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}		
?>
</body>
</html>