<?php
require_once 'klase/Korisnik.php'; // Uključivanje klase Korisnik

// Kreiranje instance klase Korisnik
$korisnik = new Korisnik();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $korisnicko_ime = $_POST['username'];
    $lozinka = $_POST['lozinka'];

    // Registracija korisnika
    $korisnik->registrujKorisnika($korisnicko_ime, $lozinka);
}
?>
