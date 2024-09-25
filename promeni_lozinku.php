<?php
session_start();

// Proveri da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

require_once 'klase/Korisnik.php'; // Uključivanje klase Korisnik

// Kreiranje instance klase Korisnik
$korisnik = new Korisnik();
$korisnik->setUsername($_SESSION['username']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = $_POST['new_password'];

        // Pozivanje metode promeniLozinku
        if ($korisnik->promeniLozinku($new_password)) {
            echo '<script>alert("Lozinka je uspešno promenjena."); window.location.href = "nalog.php";</script>';
        } else {
            echo "Greška pri menjanju lozinke.";
        }
    } else {
        echo "Nova lozinka nije uneta.";
    }
} else {
    echo "Molimo unesite novu lozinku.";
}

// Zatvaranje konekcije
$korisnik->zatvoriKonekciju();
?>
