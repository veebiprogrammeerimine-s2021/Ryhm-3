<?php
    $database = "if21_rinde";
    
    function sign_up($firstname, $surname, $email, $gender, $birth_date, $password){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vprg_users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			//kasutaja juba olemas
			$notice = "Sellise tunnusega (" .$email .") kasutaja on <strong>juba olemas</strong>!";
		} else {
			//sulgen eelmise käsu
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vprg_users (firstname, lastname, birthdate, gender, email, password) values(?,?,?,?,?,?)");
			echo $conn->error;
			//krüpteerime parooli
			$option = ["cost" => 12];
			$pwd_hash = password_hash($password, PASSWORD_BCRYPT, $option);
			$stmt->bind_param("sssiss", $firstname, $surname, $birth_date, $gender, $email, $pwd_hash);
			if($stmt->execute()){
				$notice = "Uus kasutaja edukalt loodud!";
			} else {
				$notice = "Uue kasutaja loomisel tekkis viga: " .$stmt->error;
			}
		}
        $stmt->close();
        $conn->close();
        return $notice;
    }
    
    function sign_in($email, $password){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, firstname, lastname, password FROM vprg_users WHERE email = ?");
        echo $conn->error;
        $stmt->bind_param("s", $email);
        $stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db, $password_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //kasutaja on olemas, parool tuli ...
            if(password_verify($password, $password_from_db)){
                //parool õige, oleme sees!
				$_SESSION["user_id"] = $id_from_db;
                $_SESSION["first_name"] = $firstname_from_db;
                $_SESSION["last_name"] = $lastname_from_db;
                //=================================================
                $stmt->close();
				$stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vprg_userprofiles WHERE userid = ?");
				$stmt->bind_param("i", $_SESSION["user_id"]);
				$stmt->bind_result($bg_color_from_db, $txt_color_from_db);
				$stmt->execute();
				$_SESSION["text_color"] = "#000000";
				$_SESSION["bg_color"] = "#FFFFFF";
				if($stmt->fetch()){
					if(!empty($txt_color_from_db)){
						$_SESSION["text_color"] = $txt_color_from_db;
					}
					if(!empty($bg_color_from_db)){
						$_SESSION["bg_color"] = $bg_color_from_db;
					}
				}
                //=================================================
                $stmt->close();
                $conn->close();
                header("Location: home.php");
                exit();
            } else {
                $notice = "Kasutajatunnus või salasõna oli vale!";
            }
        } else {
            $notice = "Kasutajatunnus või salasõna oli vale!";
        }
        
        $stmt->close();
        $conn->close();
        return $notice;
    }
	
	function read_user_description(){
		//kui profiil on olemas, loeb kasutaja lühitutvustuse
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//vaatame, kas on profiil olemas
		$stmt = $conn->prepare("SELECT description FROM vprg_userprofiles WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($description_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $description_from_db;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function store_user_profile($description, $bg_color, $txt_color){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//vaatame, kas on profiil olemas
		$stmt = $conn->prepare("SELECT id FROM vprg_userprofiles WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$stmt->close();
			//uuendame profiili
			$stmt= $conn->prepare("UPDATE vprg_userprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?");
			echo $conn->error;
			$stmt->bind_param("sssi", $description, $bg_color, $txt_color, $_SESSION["user_id"]);
		} else {
			$stmt->close();
			//tekitame uue profiili
			$stmt = $conn->prepare("INSERT INTO vprg_userprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
			echo $conn->error;
			$stmt->bind_param("isss", $_SESSION["user_id"], $description, $bg_color, $txt_color);
		}
		if($stmt->execute()){
			$_SESSION["bg_color"] = $_POST["bg_color_input"];
			$_SESSION["text_color"] = $_POST["text_color_input"];
			$notice = "Profiil salvestatud!";
		} else {
			$notice = "Profiili salvestamisel tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}