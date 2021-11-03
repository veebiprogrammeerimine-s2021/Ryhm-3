<?php
	$database = "if21_rinde";

    function show_latest_public_photo(){
        $photo_html = null;
        $privacy = 3;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext FROM vprg_photos WHERE id = (SELECT MAX(id) FROM vprg_photos WHERE privacy = ? AND deleted IS NULL)");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //<img src="kataloog/fail" alt="tekst">
            $photo_html = '<img src="' .$GLOBALS["photo_normal_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '">' ."\n";
        } else {
            $photo_html = "<p>Kahjuks pole ühtegi avalikku fotot üles laetud!</p>";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
	
    function read_public_photo_thumbs($privacy, $page, $limit){
        $skip = ($page - 1) * $limit;
        $photo_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        //$stmt = $conn->prepare("SELECT filename, alttext FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL");
        //$stmt = $conn->prepare("SELECT filename, alttext FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC");
        //$stmt = $conn->prepare("SELECT filename, alttext FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT 5");
        $stmt = $conn->prepare("SELECT filename, alttext FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?, ?");
        echo $conn->error;
        $stmt->bind_param("iii", $privacy, $skip, $limit);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<div>
            //<img src="kataloog/fail" alt="tekst">
            //...
            //</div>
            $photo_html .= '<div class="gallerythumb">' ."\n";
            $photo_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '" class="thumbs">' ."\n";
            $photo_html .= "</div> \n";
        }
        if(empty($photo_html)){
            $photo_html = "<p>Kahjuks pole ühtegi avalikku fotot üles laetud!</p>";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
    
    function count_public_photos($privacy){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
    
    function count_own_photos(){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vprg_photos WHERE userid = ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
    
    function read_own_photo_thumbs($page, $limit){
        $skip = ($page - 1) * $limit;
        $photo_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, filename, alttext FROM vprg_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?, ?");
        echo $conn->error;
        $stmt->bind_param("iii", $_SESSION["user_id"], $skip, $limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<div>
            //<a href="edit_photo.php?photo=x">
            //<img src="kataloog/fail" alt="tekst">
            //</a>
            //...
            //</div>
            $photo_html .= '<div class="gallerythumb">' ."\n";
            $photo_html .= '<a href="edit_photo.php?photo=' .$id_from_db .'">'; 
            $photo_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '" class="thumbs"></a>' ."\n";
            $photo_html .= "</div> \n";
        }
        if(empty($photo_html)){
            $photo_html = "<p>Kahjuks pole ühtegi avalikku fotot üles laetud!</p>";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
    
    //UPDATE vprg_photos SET deleted = NOW() WHERE id = ?