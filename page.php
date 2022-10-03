<?php
	require_once "../../config.php";
	//echo $server_host;
	$author_name = "K Kolkanen";
	$full_time_now = date("d/m/Y H:i:s");
	$weekday_now = date("N");
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	$hours_now = date("H");
	//echo $hours_now;
	$part_of_day = "suvaline päeva osa";
	$sayings = ["Hommik on õhtust targem", "Kes tasa sõuab, see kaugele jõuab", "Enne töö, siis lõbu", "Amet ei riku meest, kui mees ametit ei riku", "Kuu on poissmeeste päike"];

	if ($weekday_now <= 5) {
		if ($hours_now >= 7 & $hours_now <=8){
			$part_of_day = "hommik";
		}
		if ($hours_now >= 9 & $hours_now <=17) {
			$part_of_day = "koolipäev";
		} 
		if ($hours_now >=18 & $hours_now <=22) {
			$part_of_day = "õhtu chill";
		}
		if ($hours_now <7 || $hours_now >22) {
			$part_of_day = "uneaeg";
		}
	}

	if ($weekday_now == 6 || 7) {
		if ($hours_now >= 10 & $hours_now <=13){
			$part_of_day = "hommik";
		} else if ($hours_now >= 14 & $hours_now <=17) {
			$part_of_day = "vaba päev";
		} else {
			$part_of_day = "õhtu";
		}
	}


	//uurime semestri kestmist
	$semester_begin = new DateTime("2022-9-5");
	$semester_end = new DateTime("2022-12-18");
	$semester_duartion = $semester_begin ->diff ($semester_end);
	$semester_duartion_days = $semester_duartion ->format("%r%a");
	$from_semester_begin = $semester_begin ->diff(new DateTime("now"));
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	
	// juhuslik arv
	// küsin massiivi pikkust
	//echo count($weekday_names_et);
	//echo $weekday_names_et[mt_rand(0, count($weekday_names_et) -1)];
	
	// juhuslik muutuja
	$photo_dir = "photos";
	// loen kataloogi sisu
	$all_files = array_slice(scandir($photo_dir), 2);
	// kontrollin, kas ikka foto
	$allowed_photo_types = ["image/jpeg", "image/png"];
	// tsükkel
	/*for($i = 0; $i < count($all_files); $i ++){
		echo $all_files[$i];
	}*/
	$photo_files = [];
	foreach($all_files as $filename){
		//echo$filename;
		$file_info = getimagesize($photo_dir ."/" .$filename);
		//var_dump($file_info);
		//kontrollime, kas on lubatud tüüpide nimekirjas
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $filename);
			}
		}
	}
		
	//var_dump($photo_files);
	// img <img src="kataloog/fail" alt="tekst">
	$photo_html = '<img src="' .$photo_dir ."/" .$photo_files[mt_rand(0, count($photo_files) -1)] .'"';
	$photo_html .= ' alt="Tallinna pilt">';
	
	//vaatame, mida vormis sisestati
	//var_dump($_POST); 
	//echo $_POST["todays_adjective_input"];
	$todays_adjective = "pole midagi sisestatud";
	if(isset ($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"])){
		$todays_adjective = $_POST["todays_adjective_input"];
	} 
	/*	<option value="0">tln_104.jpg</option>
		<option value="1">tln_115.jpg</option> */
		// loome rippmenüü valikud
		$select_html = '<option value="" selected disabled>Vali pilt</option>';
		for($i = 0; $i < count($photo_files); $i ++) {
			$select_html .= '<option value="' .$i .'">'; 
			$select_html .= $photo_files[$i];
			$select_html .= "</option>";
		}
		
		if(isset ($_POST["photo_select"]) and !empty($_POST["photo_select"])) {
			echo "Valiti pilt nr:" .$_POST["photo_select"];
		}
		
		$comment_error = null;
		$grade = 7;
		//Kas klikiti päeva kommentaari nuppu
		if(isset($_POST["comment_submit"])) {
			if(isset($_POST["comment_input"]) and !empty($_POST["comment_input"])) {
				$comment = $_POST["comment_input"];
			} else {
				$comment_error = "Kommentaar jäi kirjutamata!";
			}
			$grade = $_POST["grade_input"];
			
			if(empty($comment_error)){
				
				//loon andmebaasiga ühenduse
				//server, kasutaja, parool, andmebaas
				$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
				//määran suhtlemisel kasutatava kooditabeli
				$conn->set_charset("utf8");
				//valmistame ette andmete saatmise SQL käsu
				$stmt = $conn->prepare("INSERT INTO vp_daycomment (comment, grade) values(?,?)");
				echo $conn->error;
				//seome SQL käsu õigete andmetega
				//andmetüübid i - integer d - decimal s - string
				$stmt->bind_param("si", $comment, $grade);
				$stmt->execute();
				//sulgeme käsu
				$stmt->close();
				//sulgeme andmebaasiühenduse
				$conn->close();
			}
		}
	
?>
<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title><?php echo $author_name;?> programmeerib veebi</title>
</head>

<body>
<img src="pics/banner.png" alt="Tallinna Ülikooli Terra õppehoone">
<h1>Suurim Pealkiri</h1>
<h5>Lehe avamise hetk:</h5>
<?php echo $full_time_now.", ".$weekday_names_et[$weekday_now -1];?>
<p>Praegu on <?php echo $part_of_day;?></p>
<p>Semester kestab <?php echo $semester_duartion_days;?> päeva.</p>
<p>Semester on kestnud <?php echo $from_semester_begin_days; ?> päeva.</p>
<p>See leht on loodud õppetöö raames ja ei sisalda tõsiselt võetavat sisu!</p>
<p>Õppetöö toimus <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikoolis</a></p>
<a href="https://www.tlu.ee" target="_blank">
<img src="pics/tlu_42.jpg" alt="Tallinna Ülikooli Terra õppehoone">
</a>
<p>Tänane vanasõna: <?php echo $sayings [mt_rand(0, count($sayings) -1)]; ?></p>
<hr>
<form method="POST">
	<label for="comment_input">Kommentaar tänase päeva kohta (140 tähte)</label>
	<br>
	<textarea id="comment_input" name="comment_input" cols="35" rows="4" placeholder="kommentaar"></textarea>
	<br>
	<label for="grade_input">Hinne tänasele päevale (0-10)</label>
	<input type="number" id="grade_input" name="grade_input" min="0" max="10" step="1" value="<?php echo $grade ?>">
	<br>
	<input type="submit" id="comment_submit" name="comment_submit" value="Salvesta">
	<span> <?php echo $comment_error ?> </span>
</form>
<hr>
<hr>
<form method="POST">
	<input type="text" id="todays_adjective_input" name="todays_adjective_input" 
	placeholder="Kirjuta siia omadussõna tänase päeva kohta">
	<input type="submit" id="todays_adjective_submit" name="todays_adjective_submit" value="Saada vastus">
</form>
<p>Omadussõna tänase päeva kohta: <?php echo $todays_adjective; ?></p>
<hr>
<form method="POST">
	<select id="photo_select" name="photo_select">
		<?php echo $select_html; ?>
	</select>
	<input type="submit" id="photo_submit" name="photo_submit" value="Määra foto">
</form>	
<?php echo $photo_html; ?>
<hr>
</body>

</html>