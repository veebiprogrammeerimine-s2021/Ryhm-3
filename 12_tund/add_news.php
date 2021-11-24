<?php
    //alustame sessiooni
    require_once("use_session.php");
    
    require_once("../../../../config_vp_s2021.php");
	require_once("fnc_photoupload.php");
    require_once("fnc_general.php");
    require_once("classes/Photoupload.class.php");
    
    $news_notice = null;
    
    $expire = new DateTime("now");
    $expire->add(new DateInterval("P7D"));
    $expire_date = date_format($expire, "Y-m-d");

    $normal_photo_max_width = 600;
    $normal_photo_max_height = 400;
	$thumbnail_width = $thumbnail_height = 100;
    $watermark_file = "../pics/vp_logo_w100_overlay.png";
    
    $photo_filename_prefix = "vpnews_";
    $photo_upload_size_limit = 1024 * 1024;
    $allowed_photo_types = ["image/jpeg", "image/png"];
    
    if(isset($_POST["news_submit"])){
        //kui uudisele on valitud foto, siis see salvestage esimesena ja lisage esimesena ka andmetabelisse (uudisefotodel eraldi andmetabel).
        //siis lisate uudise koos uudise pealkirja, aegumise ja foto id-ga eraldi andmetabelisse.
        //Andmebaasi salvestamisel saab pärast execute() käsku just salvestatud kirje id kätte:
        //$muutuja = $conn->insert_id;
        //uudise sisu peaks läbima funktsiooni test_input(uudis) (fnc_general.php).
        //seal on htmlspecialchars() funktsioon, mis teisendab html märgised (näiteks:  < --> &lt;   )
        //tagasi saab: htmlspecialchars_decode(uudis andmebaasist).
        
        //aegumistähtaja saate date inputist.
        //uudiste näitamisel võrdlete SQL lauses andmebaasis olevat aegumiskuupäeva tänasega
        //$today = date("Y-m-d");
        //SQL-is    WHERE expire >= ?
    }
    
    $to_head = '<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>' ."\n";
    
    require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
    </ul>
	<hr>
    <h2>Uudise lisamine</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    
        <label for="news_input">Uudise sisu</label>
        <br>
        <textarea id="news_input" name="news_input"></textarea>
        <script>CKEDITOR.replace( 'news_input' );</script>
        <br>
        <label for="expire_input">Uudis aegub pärast:</label>
        <input type="date" id="expire_input" name="expire_input" value="<?php echo $expire_date; ?>">
        <b>
        <label for="photo_input"> Vali pildifail! </label>
        <input type="file" name="photo_input" id="photo_input">
        <br>
        
        <input type="submit" name="news_submit" id="news_submit" value="Salvesta uudis">
    </form>
    <span><?php echo $news_notice; ?></span>
</body>
</html>