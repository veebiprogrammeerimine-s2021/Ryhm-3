<?php
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_general.php");
    require_once("fnc_user.php");

    $notice = null;
    $firstname = null;
    $surname = null;
    $email = null;
    $gender = null;
    $birth_month = null;
    $birth_year = null;
    $birth_day = null;
    $birth_date = null;
    $month_names_et = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];

    //muutujad võimalike veateadetega
    $firstname_error = null;
    $surname_error = null;
    $birth_month_error = null;
    $birth_year_error = null;
    $birth_day_error = null;
    $birth_date_error = null;
    $gender_error = null;
    $email_error = null;
    $password_error = null;
    $confirm_password_error = null;

    //kontrollime sisestust
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["user_data_submit"])){
			//kontrollin sisestusi
            //eesnimi
            if(isset($_POST["first_name_input"]) and !empty($_POST["first_name_input"])){
                $firstname = test_input(filter_var($_POST["first_name_input"], FILTER_SANITIZE_STRING));
                if(empty($firstname)){
                    $firstname_error = "Palun sisesta oma eesnimi!";
                }
            } else {
                $firstname_error = "Palun sisesta oma eesnimi!";
            }
            
            //perekonnanimi
            if(isset($_POST["surname_input"]) and !empty($_POST["surname_input"])){
                $surname = test_input(filter_var($_POST["surname_input"], FILTER_SANITIZE_STRING));
                if(empty($surname)){
                    $surname_error = "Palun sisesta oma perekonnanimi!";
                }
            } else {
                $surname_error = "Palun sisesta oma perekonnanimi!";
            }
            
            //sugu
            if(isset($_POST["gender_input"]) and !empty($_POST["gender_input"])){
                $gender = filter_var($_POST["gender_input"], FILTER_VALIDATE_INT);
                if($gender != 1 and $gender != 2){
                    $gender_error = "Palun märgi oma sugu!";
                }
            } else {
                $gender_error = "Palun märgi oma sugu!";
            }
            
            //sünnikuupäev
            //päev
            if(isset($_POST["birth_day_input"]) and !empty($_POST["birth_day_input"])){
                $birth_day = filter_var($_POST["birth_day_input"], FILTER_VALIDATE_INT);
                if($birth_day < 1 or $birth_day > 31){
                    $birth_day_error = "Palun vali sünni päev!";
                }
            } else {
                $birth_day_error = "Palun vali sünni päev!";
            }
            //kuu
            if(isset($_POST["birth_month_input"]) and !empty($_POST["birth_month_input"])){
                $birth_month = filter_var($_POST["birth_month_input"], FILTER_VALIDATE_INT);
                if($birth_month < 1 or $birth_month > 12){
                    $birth_month_error = "Palun vali sünni kuu!";
                }
            } else {
                $birth_month_error = "Palun vali sünni kuu!";
            }
            //aasta
            if(isset($_POST["birth_year_input"]) and !empty($_POST["birth_year_input"])){
                $birth_year = filter_var($_POST["birth_year_input"], FILTER_VALIDATE_INT);
                if($birth_year < date("Y") - 110 or $birth_year > date("Y") - 13){
                    $birth_year_error = "Palun vali sünni aasta!";
                }
            } else {
                $birth_year_error = "Palun vali sünni aasta!";
            }
            //kuupäeva valideerimine ja kokku panemine
            if(empty($birth_day_error) and empty($birth_month_error) and empty($birth_year_error)){
                if(checkdate($birth_month, $birth_day, $birth_year)){
                    $temp_date = new DateTime($birth_year ."-" .$birth_month ."-" .$birth_day);
                    $birth_date = $temp_date->format("Y-m-d");
                } else {
                    $birth_date_error = "Valitud kuupäev on ebareaalne!";
                }
            }
            
            //email
            if(isset($_POST["email_input"]) and !empty($_POST["email_input"])){
                $email = test_input(filter_var($_POST["email_input"], FILTER_VALIDATE_EMAIL));
                if(empty($email)){
                    $email_error = "Palun sisesta oma e-posti aadress!";
                }
            } else {
                $email_error = "Palun sisesta oma e-posti aadress!";
            }
            //parool
            if(isset($_POST["password_input"]) and !empty($_POST["password_input"])){
                if(strlen($_POST["password_input"]) < 8){
                    $password_error = "Sisestatud salasõna on liiga lühike!";
                }
            } else {
                $password_error = "Palun sisesta salasõna!";
            }
            //parooli kordus
            if(isset($_POST["confirm_password_input"]) and !empty($_POST["confirm_password_input"])){
                if($_POST["confirm_password_input"] != $_POST["password_input"]){
                    $confirm_password_error = "Sisestatud salasõnad on erinevad!";
                }
            } else {
                $confirm_password_error = "Palun sisesta salasõna kaks korda!";
            }
            
            //kui kõik kombes, salvestame uue kasutaja
            if(empty($firstname_error) and empty($surname_error) and empty($birth_month_error) and empty($birth_year_error) and empty($birth_day_error) and empty($birth_date_error) and empty($gender_error) and empty($email_error) and empty($password_error) and empty($confirm_password_error)){
                $notice = sign_up($firstname, $surname, $email, $gender, $birth_date, $_POST["password_input"]);
            }
			
        }//if isset lõppeb
    }//if request_method lõppeb
?>

<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">
	<title>Veebiprogrammeerimine</title>
  </head>
  <body>
	<h1>Veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <h2>Loo endale kasutajakonto</h2>
		
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="first_name_input">Eesnimi:</label><br>
	  <input name="first_name_input" id="first_name_input" type="text" value="<?php echo $firstname; ?>"><span><?php echo $firstname_error; ?></span><br>
      <label for="surname_input">Perekonnanimi:</label><br>
	  <input name="surname_input" id="surname_input" type="text" value="<?php echo $surname; ?>"><span><?php echo $surname_error; ?></span>
	  <br>
	  
	  <input type="radio" name="gender_input" id="gender_input_1" value="2" <?php if($gender == "2"){echo " checked";} ?>><label for="gender_input_1">Naine</label>
	  <input type="radio" name="gender_input" id="gender_input_2" value="1" <?php if($gender == "1"){echo " checked";} ?>><label for="gender_input_2">Mees</label><br>
	  <span><?php echo $gender_error; ?></span>
	  <br>
	  
	  <label for="birth_day_input">Sünnikuupäev: </label>
	  <?php
	    //sünnikuupäev
	    echo '<select name="birth_day_input" id="birth_day_input">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>päev</option>' ."\n";
		for($i = 1; $i < 32; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if($i == $birth_day){
				echo " selected";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  	  <label for="birth_month_input">Sünnikuu: </label>
	  <?php
	    echo '<select name="birth_month_input" id="birth_month_input">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birth_month){
				echo " selected ";
			}
			echo ">" .$month_names_et[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label for="birth_year_input">Sünniaasta: </label>
	  <?php
	    echo '<select name="birth_year_input" id="birth_year_input">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 13; $i >= date("Y") - 110; $i --){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birth_year){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>

	  <span><?php echo $birth_date_error ." " .$birth_day_error ." " .$birth_month_error ." " .$birth_year_error; ?></span>
	  
	  <br>
	  <label for="email_input">E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email_input" id="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
	  <label for="password_input">Salasõna (min 8 tähemärki):</label><br>
	  <input name="password_input" id="password_input" type="password"><span><?php echo $password_error; ?></span><br>
	  <label for="confirm_password_input">Korrake salasõna:</label><br>
	  <input name="confirm_password_input" id="confirm_password_input" type="password"><span><?php echo $confirm_password_error; ?></span><br>
	  <input name="user_data_submit" type="submit" value="Loo kasutaja"><span><?php echo $notice; ?></span>
	</form>
	<hr>
    <p>Tagasi <a href="page.php">avalehele</a></p>
	
  </body>
</html>