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
        echo $selected_person .$selected_movie .$selected_position .$role ."ahaa";
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
    //---- Vana osa ----------------------------------
    
    function read_all_films(){
        //var_dump($GLOBALS);
        //loon andmebaasiühenduse: server, kasutaja, parool, andmebaas
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
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
        //sulgeme käsu
        $stmt->close();
        //sulgeme andmebaasiühenduse
        $conn->close();
        return $film_html;
    }
    
    function store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input){
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES("Suvi", 1976, 80, "Komöödia", "Tallinnfilm", "Arvo Kruusement")
        $stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
        echo $conn->error;
        //seome SQL käsu päris andmetega
        //andmetüübid: i - integer, d - decimal, s - string
        $stmt->bind_param("siisss", $title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
        $success = null;
        if($stmt->execute()){
            $success = "Salvestamine õnnestus!";
        } else {
            $success = "Salvestamisel tekkis viga: " .$stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $success;
    }