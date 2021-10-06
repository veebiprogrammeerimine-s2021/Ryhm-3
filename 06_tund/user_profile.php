<?php
    //alustame sessiooni
    session_start();
    //kas on sisselogitud
    if(!isset($_SESSION["user_id"])){
        header("Location: page.php");
    }
    //väljalogimine
    if(isset($_GET["logout"])){
        session_destroy();
        header("Location: page.php");
    }
	
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_user.php");
	require_once("fnc_general.php");
    
    $notice = null;
    $description = read_user_description();
    
	if(isset($_POST["profile_submit"])){
		$description = test_input(filter_var($_POST["description_input"], FILTER_SANITIZE_STRING));
		$notice = store_user_profile($description, $_POST["bg_color_input"],$_POST["text_color_input"]);
	}
    
    require("page_header.php");
?>
	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
	</ul>
	<hr>
    <h2>Kasutajaprofiil</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="description_input">Lühikirjeldus</label>
        <br>
        <textarea name="description_input" id="description_input" rows="10" cols="80" placeholder="Minu lühikirjeldus ..."><?php echo $description; ?></textarea>
        <br>
        <label for="bg_color_input">Taustavärv</label>
        <br>
        <input type="color" name="bg_color_input" id="bg_color_input" value="<?php echo $_SESSION["bg_color"]; ?>">
        <br>
        <label for="text_color_input">Tekstivärv</label>
        <br>
        <input type="color" name="text_color_input" id="text_color_input" value="<?php echo $_SESSION["text_color"]; ?>">
        <br>
        <input type="submit" name="profile_submit" value="Salvesta">
    </form>
    <span><?php echo $notice; ?></span>
    
</body>
</html>