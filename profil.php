<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
$page_title = 'Zgłoszenia';
include_once ('include/naglowek.html');

include "php/db_connect.php";


$user_id= $_SESSION['user_id'];
        $sql = "SELECT username,email FROM members WHERE id=$user_id";
		$rs = mysql_query($sql) or die ("Błąd w zapytaniu - SELECT id,username,email FROM `members`!");
		$wiersz = mysql_fetch_array($rs, MYSQL_ASSOC);


$user = $wiersz['username'];
$mail = $wiersz['email'];


//echo $user_id;
echo '<div class="w3-container w3-card-2 w3-white w3-round w3-margin"><br>
        <img src="img/img_avatar.png" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">
        <span class="w3-right w3-opacity"><b></b></span>
        <h4>Profil</h4><br><br>
		<form action="edycja_profilu.php" method=post>
		<input type="hidden" name="numer" value="'.$user_id.'" class="profil">
		<label class="profil"><b>Nazwa Użytkownika:</b></label>
		<input type="text" name="username" size="50" value="'.$user.'" class="profil"><br />
		<label class="profil"><b>Adres email:</b></label><input type="text" name="email" size="50" value="'.$mail.'" class="profil"><br />
		<label class="profil"><b>Hasło:</b></label><input type="password" name="password" id="password" size="50" class="profil" placeholder="Wpisz hasło" /><br />
		<label class="profil"><b>Powtórz hasło:</b></label><input type="password" name="password2" id="password2" size="50" class="profil" placeholder="Powtórz hasło" /><br />
    
		<button type="submit" id="przycisk" value="Register" class="profil" onclick="return regformhash(this.form,this.form.password,this.form.confirmpwd);">Aktualizuj</button>
		</form>
    </center>
		<p></p>
          <div class="w3-row-padding" style="margin:0 -16px">
            <div class="w3-half"><br /><br /></div>
            <div class="w3-half">
			</div>
        </div>
        </div>

';
echo '</div>
	</div>
    <!-- End Grid -->
  </div>
  
<!-- End Page Container -->
</div><br>';

include "php/db_disconnect.php";
include_once ('include/stopka.html');

echo '<footer class="w3-container w3-theme-d3 w3-padding-16" style="text-align:center">';
echo '</footer>';
} else {
		echo 'Zaloguj sie';
		echo ('<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php">');
}
?>
