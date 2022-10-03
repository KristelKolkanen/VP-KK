<?php
	require_once "../../config.php";
	
	//loon andmebaasiga ühenduse
	//server, kasutaja, parool, andmebaas
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määran suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	
	//valmistame ette andmete saatmise SQL käsu
	$stmt = $conn->prepare("SELECT comment, grade, added FROM vp_daycomment");
	echo $conn->error;
	//seome saadavad andmed muutujatega
	$stmt->bind_result($comment_from_db, $grade_from_db, $added_from_db);
	//täidame käsu
	$stmt->execute();
	//kui saan ühe kirje
	//if($stmt->fetch()){
		//mis selle kirjega teha
	//	}
	//kui tuleb teadmata arv kirjeid
	$comment_html = null;
	while($stmt->fetch()){
		//echo $comment_from_db;
		//<p>kommentaar, hinne päevale: 6, lisatud xxxx-xx-xx x:x:x</p>
		$comment_html .= "<p>" .$comment_from_db .", hinne päevale: " .$grade_from_db .", lisatud " .$added_from_db .".</p> \n";
	}
?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Kristel Kolkanen programmeerib veebi</title>
</head>

<body>
<img src="pics/banner.png" alt="Tallinna Ülikooli Terra õppehoone">
<h1>Suurim Pealkiri</h1>
<p>See leht on loodud õppetöö raames ja ei sisalda tõsiselt võetavat sisu!</p>
<p>Õppetöö toimus <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikoolis</a></p>
<?php echo $comment_html; ?>
</body>

</html>