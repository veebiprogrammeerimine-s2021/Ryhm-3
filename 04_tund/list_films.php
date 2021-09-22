<?php
    require_once("../../../../config_vp_s2021.php");
    //echo $server_host;
	$author_name = "Andrus Rinde";
    
    $database = "if21_inga_pe_T1";
    //loon andmebaasiühenduse: server, kasutaja, parool, andmebaas
    $conn = new mysqli($server_host, $server_user_name, $server_password, $database);
    //määrame korrektse kooditabeli
    $conn->set_charset("utf8");
    //valmistan ette SQL käsu
    //SELECT * FROM film
    $stmt = $conn->prepare("SELECT * FROM film");
    echo $conn->error;
    //seome tulemused muutujatega
    $stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $genre_from_db, $studio_from_db, $director_from_db);
    //anname käsu täitmiseks
    $film_html = null;
    $stmt->execute();
	//võtan andmed
    while($stmt->fetch()){
        //paneme andmed meile sobivasse vormi
        //<h3>filmipealkiri</h3>
        //<ul>
        //<li>1977</li>
        //<li>67</li>
        //...
        //</ul>
        $film_html .= "\n <h3>" .$title_from_db ."</h3> \n <ul> \n";
        $film_html .= "<li>Valmimisaasta: " .$year_from_db ."</li> \n";
        $film_html .= "<li>Kestus: " .$duration_from_db ."</li> \n";
        $film_html .= "<li>Žanr: " .$genre_from_db ."</li> \n";
        $film_html .= "<li>Tootja: " .$studio_from_db ."</li> \n";
        $film_html .= "<li>Lavastaja: " .$director_from_db ."</li> \n";
        $film_html .= "</ul> \n";
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
    <h2>Eesti filmid</h2>
    <?php echo $film_html; ?>
</body>
</html>