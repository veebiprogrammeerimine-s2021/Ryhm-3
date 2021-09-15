<?php
	$author_name = "Andrus Rinde";
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_now = date("N");
	$day_category = "lihtsalt päev";
	//echo $weekday_now;
	//  võrdub ==   suurem/väiksem ...  <  >   <=  >=   pole võrdne  (excelis <>)   !=
	if($weekday_now <= 5){
		$day_category = "koolipäev";
	} else {
		$day_category = "puhkepäev";
	}
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekday_names_et[2];
	
	//if($hour_now < 7 or $hour_now > 23)
	//if($hour_now >= 8 and $hour_now < 18)
	
	//juhusliku foto lisamine
	$photo_dir = "photos/";
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
	
	//echo $all_files;
	//var_dump($all_real_files);
	//loen massiivi elemendid kokku
	$file_count = count($photo_files);
	//loosin juhusliku arvu (min peab olema 0 ja max count - 1)
	$photo_num = mt_rand(0, $file_count - 1);
	//<img src="kataloog/fail" alt="Tallinna Ülikool">
	$photo_html = '<img src="' .$photo_dir .$photo_files[$photo_num] .'" alt="Tallinna Ülikool">';
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
	<img src="3700x1100_pildivalik179.jpg" alt="Tallinna Ülikooli Mare hoone" width="600">
	<p>Lehe avamise hetk: <?php echo $weekday_names_et[$weekday_now - 1] .", " .$full_time_now .", " .$day_category; ?>.</p>
	<?php echo $photo_html; ?>
</body>
</html>