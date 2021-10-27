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
    //require_once("fnc_photoupload.php");
	require_once("fnc_general.php");
    
    $photo_upload_notice = null;
    $photo_upload_orig_dir = "../upload_photos_orig/";
    $photo_upload_normal_dir = "../upload_photos_normal/";
    $photo_upload_thumb_dir = "../upload_photos_thumb/";
    $photo_file_name_prefix = "vp_";
    $photo_file_size_limit = 1.2 * 1024 * 1024;
    $file_type = null;
    $file_name = null;
    $alt_text = null;
    $privacy = 1;
    
    if(isset($_POST["photo_submit"])){
        //var_dump($_POST);
        //var_dump($_FILES);
        
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
                
                //move_uploaded_file($_FILES["photo_input"]["tmp_name"], $person_photo_dir .$_FILES["photo_input"]["name"]);
                
            } else {
                $photo_upload_notice .= "Valitud fail ei ole pilt!!";
            }
        } else {
            $photo_upload_notice .= " Pilt on valimata!";
        }
        
        if(empty($photo_upload_notice) and $_FILES["photo_input"]["size"] > $photo_file_size_limit){
            $photo_upload_notice .= " Pildifail on liiga suur!";
        }
        
        
        if(empty($photo_upload_notice)){
            //teeme failinime
            //genereerin ajatempli
            $time_stamp = microtime(1) * 10000;
            $file_name = $photo_file_name_prefix .$time_stamp ."." .$file_type; 
            
            //muudame pildi suurust
            
            
            
            if(move_uploaded_file($_FILES["photo_input"]["tmp_name"], $photo_upload_orig_dir .$file_name)){
                //pildi info andmebaasi
                
            }
        }
    }//photo_submit
    
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
    <h2>Galeriipiltide üleslaadimine</h2>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <label for="photo_input">Vali pildifail</label>
        <input type="file" name="photo_input" id="photo_input">
        <br>
        <label for="alt_input">Alternatiivtekst (alt):</label>
        <input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst
        ..." value="<?php echo $alt_text; ?>">
        <br>
        <input type="radio" name="privacy_input" id="privacy_input_1" value="1" <?php if($privacy == 1){echo " checked";}?>>
        <label for="privacy_input_1">Privaatne (ainult mina näen)</label>
        <br>
        <input type="radio" name="privacy_input" id="privacy_input_2" value="2" <?php if($privacy == 2){echo " checked";}?>>
        <label for="privacy_input_2">Sisseloginud kasutajatele</label>
        <br>
        <input type="radio" name="privacy_input" id="privacy_input_3" value="3" <?php if($privacy == 3){echo " checked";}?>>
        <label for="privacy_input_3">Avalik (kõik näevad)</label>
        <br>
        
        <input type="submit" name="photo_submit" value="Lae pilt üles">
    </form>
    <span><?php echo $photo_upload_notice; ?></span>
    
</body>
</html>