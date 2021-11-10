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
    require_once("fnc_general.php");
	require_once("fnc_gallery.php");
    $privacy = 2;
    
    //greeny.cs.tlu.ee/~rinde/vp2021/Ryhm-3/09_tund/gallery_public.php?page=2
    $page = 1;
    $limit = 10;
    $photo_count = count_public_photos($privacy);
    //kontrollime, mis lehel oleme ja kas selline leht on võimalik
    if(!isset($_GET["page"]) or $_GET["page"] < 1){
        $page = 1;
    } elseif(round($_GET["page"] - 1) * $limit >= $photo_count){
        $page = ceil($photo_count / $limit);
    } else {
        $page = $_GET["page"];
    }
    
    $to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' . "\n";
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
    <h2>Avalike fotode galerii</h2>
    <div class="gallery">
        <p>
            <?php
                //Eelmine leht | Järgmine leht
                //<span>Eelmine leht</span> | <span><a href="?page=2">Järgmine leht</a></span>
                if($page > 1){
                    echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span>';
                } else {
                    echo "<span>Eelmine leht</span>";
                }
                echo " | ";
                if($page * $limit < $photo_count){
                    echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>';
                } else {
                    echo "<span>Järgmine leht</span>";
                }
            ?>
        </p>
        <?php echo read_public_photo_thumbs($privacy, $page, $limit); ?>
    </div>
</body>
</html>