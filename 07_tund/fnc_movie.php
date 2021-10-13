<?php
    $database = "if21_inga_pe_T1";
    
    function read_all_person_for_select($selected){
        $options_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT * FROM person");
        //<option value="x" selected>Eesnimi Perekonnanimi (sünnipäev)</option>
        echo $conn->error;
        $stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $birth_date_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            $options_html .= '<option value="' .$id_from_db .'"';
            if($id_from_db == $selected){
                $options_html .= " selected";
            }
            $options_html .= ">" .$first_name_from_db ." " .$last_name_from_db ." (" .$birth_date_from_db .")</options> \n";
        }
        $stmt->close();
        $conn->close();
        return $options_html;
    }
    
    function read_all_movie_for_select($selected){
        $options_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, title, production_year FROM movie");
        echo $conn->error;
        $stmt->bind_result($id_from_db, $title_from_db, $production_year_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            $options_html .= '<option value="' .$id_from_db .'"';
            if($id_from_db == $selected){
                $options_html .= " selected";
            }
            $options_html .= ">" .$title_from_db ." (" .$production_year_from_db .")</options> \n";
        }
        
        $stmt->close();
        $conn->close();
        return $options_html;
    }
    
    function read_all_position_for_select($selected){
        $options_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, position_name FROM position");
        echo $conn->error;
        $stmt->bind_result($id_from_db, $position_name_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            $options_html .= '<option value="' .$id_from_db .'"';
            if($id_from_db == $selected){
                $options_html .= " selected";
            }
            $options_html .= ">" .$position_name_from_db ."</options> \n";
        }
        
        $stmt->close();
        $conn->close();
        return $options_html;
    }
    
    function store_person_movie_relation($selected_person, $selected_movie, $selected_position, $role){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, role FROM person_in_movie WHERE person_id = ? AND movie_id = ? AND position_id = ?");
        echo $conn->error;
        $stmt->bind_param("iii", $selected_person, $selected_movie, $selected_position);
        $stmt->bind_result($id_from_db, $role_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            if($role_from_db == $role){
                //selline on olemas
                $notice = "Selline seos on juba olemas!";
            }
        }
        if(empty($notice)){
            $stmt->close();
            if($selected_person == 1){
                $role = "";
            }
            $stmt = $conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id, role) VALUES (?, ?, ?, ?)"); 
            $stmt->bind_param("iiis", $selected_person, $selected_movie, $selected_position, $role);
            if($stmt->execute()){
                $notice = "Uus seos edukalt salvestatud!";
            } else {
                $notice = "Uue seose salvestamisel tekkis viga: " .$stmt->error;
            }
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }

    function store_person_photo($file_name, $person_id){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("INSERT INTO picture (picture_file_name, person_id) VALUES (?, ?)");
        echo $conn->error;
        $stmt->bind_param("si", $file_name, $person_id);
        if($stmt->execute()){
            $notice = "Uus foto edukalt salvestatud!";
        } else {
            $notice = "Uue foto andmebaasi salvestamisel tekkis viga: " .$stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }