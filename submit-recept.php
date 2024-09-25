<?php
session_start();

// Prikazivanje grešaka
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Proveri da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Uzimanje korisničkog imena iz sesije
$napravio = $_SESSION['username'];

// Obrada podataka iz forme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prikupljanje podataka iz forme
    $ime = $_POST['ime'];
    $opis = $_POST['opis'];
    $instrukcije = $_POST['instrukcije'];
    $kategorija = $_POST['kategorija'];
    $dijeta = $_POST['dijeta'] === "Sve" ? "" : $_POST['dijeta'];

    // Uzimanje instance klase Recept
    require_once 'klase/Recept.php';
    $recept = new Recept();
    $poruka = $recept->dodajRecept($ime, $opis, $instrukcije, $dijeta, $napravio, $kategorija);
    echo $poruka;
}
?>
