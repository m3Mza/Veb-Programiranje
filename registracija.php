<?php
$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "sajt_baza";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Veza neuspešna: " . $conn->connect_error);
}


$korisnicko_ime = $_POST['username'];
$lozinka = $_POST['lozinka'];

// Provera da li je korisnicko ime zauzeto
$stmt = $conn->prepare("SELECT id FROM korisnici WHERE korisnicko_ime = ?");
$stmt->bind_param("s", $korisnicko_ime);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    
    echo "Korisničko ime je zauzeto.";
} else {
    
    $stmt = $conn->prepare("INSERT INTO korisnici (korisnicko_ime, lozinka) VALUES (?, ?)");
    $stmt->bind_param("ss", $korisnicko_ime, $lozinka);

    
    if ($stmt->execute()) {
       
        header("Location: index2.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}


$stmt->close();
$conn->close();
?>

