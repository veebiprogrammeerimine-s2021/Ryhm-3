<?php
    //alustame sessiooni
    //session_start();
    require_once("classes/SessionManager.class.php");
    SessionManager::sessionStart("vp", 0, "/~rinde/vp2021/Ryhm-3/", "greeny.cs.tlu.ee");
	
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_user.php");
    require_once("fnc_gallery.php");
	$author_name = "Andrus Rinde";
	
	//kontrollin, kas POST info jõuab kuhugi
	//var_dump($_POST);
	//kontrollime, kas klikiti submit nuppu
	$todays_adjective_html = null; //$todays_adjective_html = "";
	$todays_adjective_error = null;
	$todays_adjective = null;
	if(isset($_POST["adjective_submit"])){
		//echo "Klikiti!";
		//<p>Tänane päev on tuuline.</p>
		//kontrollime, kas midagi kirjutati ka
		if(!empty($_POST["todays_adjective_input"])){
			$todays_adjective_html = "<p>Tänane päev on " .$_POST["todays_adjective_input"] .".</p>";
			$todays_adjective = $_POST["todays_adjective_input"];
		} else {
			$todays_adjective_error = "Palun sisesta tänase kohta sobiv omadussõna!";
		}
	}
	
	//juhusliku foto lisamine
	$photo_dir = "../photos/";
	//loen kataloogi sisu
	$all_files = scandir($photo_dir);
	$all_real_files = array_slice($all_files, 2);
	
	//sõelume välja päris pildid
	$photo_files = [];
	$allowed_photo_types = ["image/jpeg", "image/png"];
	foreach($all_real_files as $file_name){
		$file_info = getimagesize($photo_dir .$file_name);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file_name);
			}//if in_array
		}//if isset lõppeb
	}//foreach lõppes
	
	$file_count = count($photo_files);
	$photo_num = mt_rand(0, $file_count - 1);
    
    if(isset($_POST["photo_select_submit"])){
		$photo_num = $_POST["photo_select"];
	}
    
	$photo_html = '<img src="' .$photo_dir .$photo_files[$photo_num] .'" alt="Tallinna Ülikool">';
    
    $photo_file_html = "\n <p>".$photo_files[$photo_num] ."</p> \n";
	
	//tsükkel
	//näiteks:
	//<ul>
	//     <li>pildifailinimi1.jpg</li>
	//     <li>pildifailinimi2.jpg</li>
	//     <li>pildifailinimi3.jpg</li>
	//      ...
	//</ul>
	
	$photo_list_html = "\n <ul> \n";
	for($i = 0;$i < $file_count;$i ++){
		$photo_list_html .= "<li>" .$photo_files[$i] ."</li> \n";
	}
	$photo_list_html .= "</ul> \n";
	
/* 	<select name="photo_select">
		<option value="0">tlu_astra_600x400_1.jpg</option> 
		<option value="1">tlu_astra_600x400_2.jpg</option> 
		<option value="2">tlu_hoov_600x400_1.jpg</option> 
		<option value="3">tlu_mare_600x400_1.jpg</option> 
		<option value="4">tlu_mare_600x400_2.jpg</option> 
		<option value="5">tlu_terra_600x400_1.jpg</option> 
		<option value="6">tlu_terra_600x400_2.jpg</option> 
		<option value="7">tlu_terra_600x400_3.jpg</option> 
	</select> */ 
	
	$photo_select_html = "\n" .'<select name="photo_select">' ."\n";
	for($i = 0;$i < $file_count;$i ++){
		$photo_select_html .= '<option value="' .$i .'"';
        if($i == $photo_num){
			$photo_select_html .= " selected";
		}
        $photo_select_html .= ">" .$photo_files[$i] ."</option> \n";
	}
	$photo_select_html .= "</select> \n";
	
    //sisselogimine
    $email = null;
	$email_error = null;
	$password_error = null;
	$notice = null;
    //sisselogimine
    if(isset($_POST["login_submit"])){
		if(isset($_POST["email_input"]) and !empty($_POST["email_input"])){
			$email = filter_var($_POST["email_input"], FILTER_VALIDATE_EMAIL);
			if(strlen($email) < 5){
				$email_error = "Palun sisesta kasutajatunnus (e-mail)!";
			}
		} else {
			$email_error = "Palun sisesta kasutajatunnus (e-mail)!";
		}
		if(isset($_POST["password_input"]) and !empty($_POST["password_input"])){
			if(strlen($_POST["password_input"]) < 8){
				$password_error = "Sisestatud salasõna on liiga lühike!";
			}
		} else {
			$password_error = "Palun sisesta salasõna!";
		}
		if(empty($email_error) and empty($password_error)){
			$notice = sign_in($email, $_POST["password_input"]);
		} else {
			$notice = $email_error ." " .$password_error;
		}
    }
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="email" name="email_input" placeholder="email ehk kasutajatunnus" value="<?php echo $email; ?>">
        <input type="password" name="password_input" placeholder="salasõna">
        <input type="submit" name="login_submit" value="Logi sisse">
		<span><?php echo $notice; ?></span>
    </form>
    <p>Loo omale <a href="add_user.php">kasutajakonto</a></p>
    <hr>
	<form method="POST">
		<input type="text" placeholder="omadussõna tänase kohta" name="todays_adjective_input" value="<?php echo $todays_adjective; ?>">
		<input type="submit" name="adjective_submit" value="Saada">
		<span><?php echo $todays_adjective_error; ?></span>
	</form>
	<?php echo $todays_adjective_html; ?>
	<hr>
    <?php echo show_latest_public_photo(); ?>
    <hr>
	<form method="POST">
		<?php echo $photo_select_html; ?>
        <input type="submit" name="photo_select_submit" value="Näita valitud fotot">
	</form>
	<hr>
	<?php
		echo $photo_html;
        echo $photo_file_html;
		echo "<hr> \n";
		echo $photo_list_html;
	?>
</body>
</html>