<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veza neuspešna: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];


$stmt = $conn->prepare("UPDATE korisnici SET lozinka = ? WHERE korisnicko_ime = ?");
$stmt->bind_param("ss", $new_password, $username);

if ($stmt->execute()) {
    echo '<script>alert("Lozinka je uspešno promenjena."); window.location.href = "nalog.php";</script>';
    exit();
} else {
    echo "Greška pri menjanju lozinke: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
