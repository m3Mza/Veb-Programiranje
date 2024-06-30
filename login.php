<?php
session_start();


$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "sajt_baza";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$korisnicko_ime = $_POST['username'];
$lozinka = $_POST['password'];


$stmt = $conn->prepare("SELECT lozinka FROM korisnici WHERE korisnicko_ime = ?");
$stmt->bind_param("s", $korisnicko_ime);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_lozinka);
    $stmt->fetch();

    
    if ($lozinka === $hashed_lozinka) {
        // Kreiranje sesije za tacnu lozinku
        $_SESSION['username'] = $korisnicko_ime;
        header("Location: index2.php");
        exit();
    } else {
        
        echo "Pogresna lozinka.";
    }
} else {
   
    echo "Korisnicko ime ne postoji.";
}


$stmt->close();
$conn->close();
?>
