
<?php
session_start();
require_once 'klase/Korisnik.php'; // Uključi klasu Korisnik

if (isset($_SESSION['username'])) {
    $korisnik = new Korisnik();
    $korisnik->setUsername($_SESSION['username']);
    
    // Pozivamo metodu za brisanje naloga
    $korisnik->obrisiNalog();
} else {
    echo "Greška: Niste prijavljeni.";
}

?>
