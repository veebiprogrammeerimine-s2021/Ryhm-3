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
    require_once("fnc_films.php");
	require_once("fnc_general.php");
    //echo $server_host;
    $film_store_notice = null;
	$title_input = null;
	$year_input = date("Y");
	$duration_input = 60;
	$genre_input = null;
	$studio_input = null;
	$director_input = null;
	$title_input_error = null;
	$year_input_error = null;
	$duration_input_error = null;
	$genre_input_error = null;
	$studio_input_error = null;
	$director_input_error = null;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //kas klikiti submit nuppu
		if(isset($_POST["film_submit"])){
			//kontrollin, et andmeid ikka sisestati
			if(!empty($_POST["title_input"])){
				$title_input = test_input(filter_var($_POST["title_input"], FILTER_SANITIZE_STRING));
			} else {
				$title_input_error = "Palun sisesta filmi pealkiri!";
			}
			if(!empty($_POST["year_input"])){
				$year_input = filter_var($_POST["year_input"], FILTER_VALIDATE_INT);
			} else {
				$year_input_error = "Palun sisesta filmi valmimisaasta!";
			}
			if(!empty($_POST["duration_input"])){
				$duration_input = filter_var($_POST["duration_input"], FILTER_VALIDATE_INT);
			} else {
				$duration_input_error = "Palun sisesta filmi kestus!";
			}
			if(!empty($_POST["genre_input"])){
				$genre_input = test_input(filter_var($_POST["genre_input"], FILTER_SANITIZE_STRING));
			} else {
				$genre_input_error = "Palun sisesta filmi žanr!";
			}
			if(!empty($_POST["studio_input"])){
				$studio_input = test_input(filter_var($_POST["studio_input"], FILTER_SANITIZE_STRING));
			} else {
				$studio_input_error = "Palun sisesta filmi tootja!";
			}
			if(!empty($_POST["director_input"])){
				$director_input = test_input(filter_var($_POST["director_input"], FILTER_SANITIZE_STRING));
			} else {
				$director_input_error = "Palun sisesta filmi lavastaja!";
			}
			if(empty($title_input_error) and empty($year_input_error) and empty($duration_input_error) and empty($genre_input_error) and empty($studio_input_error) and empty($director_input_error)){
				
				$film_store_notice = store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
			} else {
				$film_store_notice = "Osa andmeid on puudu!";
			}
		}
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
		<li><a href="list_films.php">Filmide nimekirja vaatamine</a> versioon 1</li>
    </ul>
	<hr>
    <h2>Eesti filmide lisamine</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="title_input">Filmi pealkiri</label>
        <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri" value="<?php echo $title_input; ?>"><span><?php echo $title_input_error; ?></span>
        <br>
        <label for="year_input">Valmimisaasta</label>
        <input type="number" name="year_input" id="year_input" min="1912" value="<?php echo $year_input; ?>">
		<?php echo $year_input_error; ?></span>
        <br>
        <label for="duration_input">Kestus</label>
        <input type="number" name="duration_input" id="duration_input" min="1" value="<?php echo $duration_input; ?>" max="600">
		<?php echo $duration_input_error; ?></span>
        <br>
        <label for="genre_input">Filmi žanr</label>
        <input type="text" name="genre_input" id="genre_input" placeholder="žanr" value="<?php echo $genre_input; ?>">
		<?php echo $genre_input_error; ?></span>
        <br>
        <label for="studio_input">Filmi tootja</label>
        <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja" value="<?php echo $studio_input; ?>">
		<?php echo $studio_input_error; ?></span>
        <br>
        <label for="director_input">Filmi režissöör</label>
        <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör" value="<?php echo $director_input; ?>">
		<?php echo $director_input_error; ?></span>
        <br>
        <input type="submit" name="film_submit" value="Salvesta">
    </form>
    <span><?php echo $film_store_notice; ?></span>
    
</body>
</html>