<?php
require_once "../../config.php";

$title_error = null;
$year_error = null;
//Kas klikiti submit nuppu
if(isset($_POST["film_submit"])) {
    if(isset($_POST["title_input"]) and !empty($_POST["title_input"])) {
        $title = $_POST["title_input"];
    } else {
        $title_error = "Filmi nimi jäi kirjutamata!";
    }
   if(isset($_POST["year_input"]) and ($_POST["year_input"]) > 1912) {
        $year = $_POST["year_input"];
   } else {
        $year_error = "Sisestatud aastaarv on vale!";
   }
   $duration = $_POST["duration_input"];
   $director = $_POST["director_input"];
   $studio = $_POST["studio_input"];
   $genre = $_POST["genre_input"];
    
    if(empty($title_error and $year_error)){
        
        //loon andmebaasiga ühenduse
        //server, kasutaja, parool, andmebaas
        $conn = new mysqli($server_host, $server_user_name, $server_password, $database);
        //määran suhtlemisel kasutatava kooditabeli
        $conn->set_charset("utf8");
        //valmistame ette andmete saatmise SQL käsu
        $stmt = $conn->prepare("INSERT INTO film (aasta, kestus, lavastaja, pealkiri, tootja, zanr) values(?,?,?,?,?,?)");
        echo $conn->error;
        //seome SQL käsu õigete andmetega
        //andmetüübid i - integer d - decimal s - string
        $stmt->bind_param("iissss", $year, $duration, $director, $title, $studio, $genre);
       // if($stmt->execute()) {
       //     $title = null;
       //     $year = null;
       //     $duration = null;
       //     $director = null;
       //     $studio = null;
       //     $genre = null;
       // }
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
    <title>Filmide sisestamine</title>
</head>
<body>
    <h1>Filmide sisestamine</h1>

<form method="POST">
        <label for="title_input">Filmi pealkiri</label>
        <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri">
        <span> <?php echo $title_error ?> </span>
        <br>
        <label for="year_input">Valmimisaasta</label>
        <input type="number" name="year_input" id="year_input" min="1912">
        <span> <?php echo $year_error ?> </span>
        <br>
        <label for="duration_input">Kestus</label>
        <input type="number" name="duration_input" id="duration_input" min="1" value="60" max="600">
        <br>
        <label for="genre_input">Filmi žanr</label>
        <input type="text" name="genre_input" id="genre_input" placeholder="žanr">
        <br>
        <label for="studio_input">Filmi tootja</label>
        <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja">
        <br>
        <label for="director_input">Filmi režissöör</label>
        <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör">
        <br>
        <input type="submit" name="film_submit" value="Salvesta">
    </form>
</body>
</html>