<?php
    //alustame sessiooni
    require_once("use_session.php");
    
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_movie.php");
	require_once("fnc_general.php");
    
    $person_movie_relation_notice = null;
    $person_selected = null;
    $movie_selected = null;
    $position_selected = null;
    $role = null;
    
    if(isset($_POST["person_movie_relation_submit"])){
        if(isset($_POST["movie_select"]) and !empty($_POST["movie_select"])){
            $movie_selected = filter_var($_POST["movie_select"], FILTER_VALIDATE_INT);
        }
        if(empty($movie_selected)){
            $person_movie_relation_notice .= "Film on valimata! ";
        }
        
        if(isset($_POST["person_select"]) and !empty($_POST["person_select"])){
            $person_selected = filter_var($_POST["person_select"], FILTER_VALIDATE_INT);
        }
        if(empty($person_selected)){
            $person_movie_relation_notice .= "Isik on valimata! ";
        }
        
        if(isset($_POST["position_select"]) and !empty($_POST["position_select"])){
            $position_selected = filter_var($_POST["position_select"], FILTER_VALIDATE_INT);
        }
        if(empty($position_selected)){
            $person_movie_relation_notice .= "Amet on valimata! ";
        }
        
        if($position_selected == 1){
            if(isset($_POST["role_input"]) and !empty($_POST["role_input"])){
                $role = test_input(filter_var($_POST["role_input"], FILTER_SANITIZE_STRING));
            }
            if(empty($role)){
                $person_movie_relation_notice .= "Roll on kirjutamata! ";
            }
        }
        
        if(empty($person_movie_relation_notice)){
            $person_movie_relation_notice = store_person_movie_relation($person_selected, $movie_selected, $position_selected, $role);
            //echo $person_selected .$movie_selected .$position_selected .$role ."ahaa";
        }
    }
    
    $person_selected_for_photo = null;
    $photo_upload_notice = null;
    $person_photo_dir = "../person_photo/";
    $file_type = null;
    $file_name = null;
    
    if(isset($_POST["person_photo_submit"])){
        //var_dump($_POST);
        //var_dump($_FILES);
        if(isset($_POST["person_select"]) and !empty($_POST["person_select"])){
            $person_selected_for_photo = filter_var($_POST["person_select"], FILTER_VALIDATE_INT);
        }
        if(empty($person_selected_for_photo)){
            $photo_upload_notice .= "Isik on valimata! ";
        }
        
        if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
            $image_check = getimagesize($_FILES["photo_input"]["tmp_name"]);
            if($image_check !== false){
                if($image_check["mime"] == "image/jpeg"){
                    $file_type = "jpg";
                }
                if($image_check["mime"] == "image/png"){
                    $file_type = "png";
                }
                if($image_check["mime"] == "image/gif"){
                    $file_type = "gif";
                }
                
                //teeme failinime
                //genereerin ajatempli
                $time_stamp = microtime(1) * 10000;
                $file_name = "person_" .$_POST["person_select"] ."_" .$time_stamp ."." .$file_type; 
                
                //move_uploaded_file($_FILES["photo_input"]["tmp_name"], $person_photo_dir .$_FILES["photo_input"]["name"]);
                
            } else {
                $photo_upload_notice .= "Pilt on valimata!";
            }
        }
        
        if(empty($photo_upload_notice)){
            if(move_uploaded_file($_FILES["photo_input"]["tmp_name"], $person_photo_dir .$file_name)){
                //pildi info andmebaasi
                $photo_upload_notice = store_person_photo($file_name, $person_selected_for_photo);
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
    </ul>
	<hr>
    <h2>Filmi info seoste loomine</h2>
    <h3>Film, isik ja amet</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="movie_select">Film: </label>
        <select name="movie_select" id="movie_select">
            <option value="" selected disabled>Film</option>
            <?php echo read_all_movie_for_select($movie_selected); ?>
        </select>

        <label for="person_select">Isik: </label>
        <select name="person_select" id="person_select">
            <option value="" selected disabled>Isik</option>
            <?php echo read_all_person_for_select($person_selected); ?>
        </select>
        
        <label for="position_select">Amet: </label>
        <select name="position_select" id="position_select">
            <option value="" selected disabled>Amet</option>
            <?php echo read_all_position_for_select($position_selected); ?>
        </select>
        
        <label for="role_input">Roll: </label>
        <input type="text" name="role_input" id="role_input" placeholder="roll" value="<?php echo $role; ?>">
        
        <input type="submit" name="person_movie_relation_submit" value="Salvesta">
    </form>
    <span><?php echo $person_movie_relation_notice; ?></span>
    <hr>
    <h3>Filmitegelase foto lisamine</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <label for="person_select_for_photo">Isik: </label>
        <select name="person_select" id="person_select_for_photo">
            <option value="" selected disabled>Isik</option>
            <?php echo read_all_person_for_select($person_selected_for_photo); ?>
        </select>
        <input type="file" name="photo_input">
        <input type="submit" name="person_photo_submit" value="Lae pilt üles">
    </form>
    <span><?php echo $photo_upload_notice; ?></span>
    
</body>
</html>