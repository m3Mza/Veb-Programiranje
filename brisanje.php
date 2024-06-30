<?php
session_start();

// Jel korisnik ulogovan?
if (!isset($_SESSION['username'])) {
    // Redirekcija ako nije
    header("Location: login.html");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$username = $_SESSION['username'];


$stmt = $conn->prepare("DELETE FROM korisnici WHERE korisnicko_ime = ?");
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    
    session_destroy();
    
  
    echo '<script>alert("Vaš nalog je uspešno obrisan."); window.location.href = "login.html";</script>';
    exit();
} else {
    echo "Greška pri brisanju: " . $conn->error;
}


$stmt->close();
$conn->close();
?>
