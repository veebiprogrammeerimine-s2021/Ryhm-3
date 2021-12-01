<?php
    require_once("use_session.php");
	require_once("../../../../config_vp_s2021.php");
	require_once("fnc_news.php");
    
    //testin klassi
    /* require_once("classes/Test.class.php");
    $test_object = new Test(6);
    echo " Teadaolev, avalik number: " .$test_object->known_number;
    $test_object->reveal();
    unset($test_object); */
    
    //küpsised
    //time() + sekundid, 86400 sekundit ööpäevas (60 * 60 * 24)
    setcookie("vpvisitor", $_SESSION["first_name"] ." " .$_SESSION["last_name"], time() + (86400 * 8), "/~rinde/vp2021/Ryhm-3/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
    //var_dump($_COOKIE);
    $last_visitor = "pole teada";
    if(isset($_COOKIE["vpvisitor"]) and !empty($_COOKIE["vpvisitor"])){
        $last_visitor = $_COOKIE["vpvisitor"];
    }
    //cookie kustutamine, pannakse aegumine minevikus
    //time() - 3600
    
    require("page_header.php");
?>
	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
        <?php echo "<p>Eelmine külastaja " .$last_visitor ."</p> \n"; ?>
    <hr>
    <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="list_films.php">Filmide nimekirja vaatamine</a> versioon 1</li>
		<li><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</li>
        <li><a href="user_profile.php">Kasutajaprofiil</a></li>
        <li><a href="movie_relations.php">Filmi, isiku jms seoste loomine</a></li>
        <li><a href="list_movie_info.php">Isikute ja filmide info</a></li>
        <li><a href="gallery_photo_upload.php">Galeriipiltide üleslaadimine</a></li>
        <li><a href="gallery_public.php">Sisseloginud kasutajatele nähtavate fotode galerii</a></li>
        <li><a href="gallery_own.php">Minu fotode galerii</a></li>
        <li><a href="add_news.php">Uudiste lisamine</a></li>
    </ul>
	<br>
	<h2>Uudised</h2>
	<?php
	echo latest_news(5);
	?>
</body>
</html>