<?php
	require_once "../../config.php";
	//kõik muutujad, mis deklareeritud väljaspool funktsiooni, on globaalsed
	//ja kättesaadavd massivist $GLOBALS

	function sign_up($first_name, $last_name, $birth_date, $gender, $email, $password){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id FROM vp_users WHERE email = ?");
		echo $conn->error;
        $stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline kasutaja on juba olemas!";
		} else {
            $stmt->close();
			$stmt = $conn->prepare("INSERT INTO vp_users (firstname, lastname, birthdate, gender, email, password) values(?,?,?,?,?,?)");
			echo $conn->error;
			//krüpteerime parooli
			$pwd_hash = password_hash($password, PASSWORD_DEFAULT); 
			$stmt->bind_param("sssiss", $first_name, $last_name, $birth_date, $gender, $email, $pwd_hash);
			if($stmt->execute()){
				$notice = "Uus kasutaja loodud!";
			} else {
				$notice = "Kasutaja loomisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function sign_in($email, $password){
		$login_error = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT password FROM vp_users WHERE email = ?");
        echo $conn->error;
        $stmt->bind_param("s", $email);
        $stmt->bind_result($password_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //kasutaja on olemas, parool tuli ...
            if(password_verify($password, $password_from_db)){
				$stmt->close();
				$stmt = $conn->prepare("SELECT id, firstname, lastname FROM vp_users WHERE email = ?");
				$stmt->bind_param("s", $email);
				$stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db);
				$stmt->execute();
				if($stmt->fetch()){
					//parool õige, oleme sees!
					//määran sessioonimuutujad
					$_SESSION["user_id"] = $id_from_db;
					$_SESSION["firstname"] = $firstname_from_db;
					$_SESSION["lastname"] = $lastname_from_db;
					$stmt->close();
					$conn->close();
					header("Location: home.php");
					exit();
				} else {
					$login_error = "Sisselogimisel tekkis tõrge!";
				}
            } else {
                $login_error = "Kasutajatunnus või salasõna oli vale!";
            }
        } else {
            $login_error = "Kasutajatunnus või salasõna oli vale!";
        }

        $stmt->close();
        $conn->close();

		return $login_error;
	}