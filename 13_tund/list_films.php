<?php
    //alustame sessiooni
    require_once("use_session.php");
    
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_films.php");
    //echo $server_host;
	$author_name = "Andrus Rinde";
    $film_html = null;
    $film_html = read_all_films();
    
    require("page_header.php");
?>
	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
		<li><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</li>
    </ul>
	<hr>
    <h2>Eesti filmid</h2>
    <?php echo $film_html; ?>
</body>
</html>