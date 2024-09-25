<?php
session_start();

require_once 'klase/Autentifikator.php'; // Uključivanje fajla sa klasom Autentifikator

// Kreiranje instance autentifikatora
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $korisnickoIme = $_POST['username'];
    $lozinka = $_POST['password'];

    // Kreiranje instance autentifikatora
    $autentifikator = new Autentifikator($korisnickoIme, $lozinka);
    $rezultat = $autentifikator->autentifikuj();

    if ($rezultat) {
        echo $rezultat;
    }
}
?>
