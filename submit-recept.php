<?php
session_start();

// Jel ulogovan korisnik?
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";

// Uzimanje korisničkog imena iz sesije
$napravio = $_SESSION['username'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = $_POST['ime'];
    $opis = $_POST['opis'];
    $instrukcije = $_POST['instrukcije'];
    $kategorija = $_POST['kategorija'];
    $dijeta = $_POST['dijeta'];

    if ($dijeta == "Sve") {
        $dijeta = "";
    }

    $sql = "INSERT INTO recepti (ime, opis, instrukcije, kategorija, dijeta, napravio)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $ime, $opis, $instrukcije, $kategorija, $dijeta, $napravio);

    if ($stmt->execute()) {
        echo "Novi recept je uspešno dodat!";
    } else {
        echo "Greška: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
