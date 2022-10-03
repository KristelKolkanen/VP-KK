<?php
require_once "../config.php";

	//loon andmebaasiga ühenduse
	//server, kasutaja, parool, andmebaas
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määran suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	
	//valmistame ette andmete saatmise SQL käsu
	$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, lavastaja, zanr, tootja added FROM film");
	//echo $conn->error;
	//seome saadavad andmed muutujatega
	$stmt->bind_result($pealkiri_from_db, $aasta_from_db, $kestus_from_db, $lavastaja_from_db, $zanr_from_db, $tootja_from_db);
	//täidame käsu
	$stmt->execute();
	//kui saan ühe kirje
	//if($stmt->fetch()){
		//mis selle kirjega teha
	//	}
	//kui tuleb teadmata arv kirjeid
	$films_html = null;
	while($stmt->fetch()){
		$films_html .= "<h3>" .$pealkiri_from_db ."</h3>" ."<ul><li> Valmimisaasta: " .$aasta_from_db ."</li><li> Kestus: " .$kestus_from_db ." minutit.</li><li> Žanr: " .$zanr_from_db . "</li><li> Tootja: " .$tootja_from_db ."</li><li> Lavastaja: " .$lavastaja_from_db . "</li></ul>";
	}
?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Filmid</title>
</head>

<body>
<h1>FILMID</h1>

<?php echo $films_html; ?>
</body>

</html>