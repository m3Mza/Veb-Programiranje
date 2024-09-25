<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

require_once 'klase/Recept.php'; // Uključi klasu Recept

// Kreira instancu klase Recept
$recept = new Recept();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Uzima ID recepta
    $napravio = $_SESSION['username']; // Korisničko ime iz sesije

    echo "ID: " . $id . ", Napravio: " . $napravio . "<br>"; // Debug poruka

    try {
        // Proveri da li recept postoji
        if ($recept->receptPostoji($id, $napravio)) {
            // Poziva metodu za brisanje recepta
            if ($recept->obrisiRecept($id, $napravio)) {
                echo "Recept je uspešno obrisan.";
            } else {
                echo "Recept nije pronađen ili nije obrisan.";
            }
        } else {
            echo "Recept sa ID: $id ne postoji ili ne pripada korisniku $napravio.";
        }
    } catch (Exception $e) {
        echo "Došlo je do greške: " . $e->getMessage();
    } finally {
        // Zatvara konekciju sa bazom
        $recept->zatvoriKonekciju();
    }
} else {
    echo "Nisu poslati validni podaci.";
}
?>
