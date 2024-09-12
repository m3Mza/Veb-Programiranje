<?php
session_start();

class Korisnik
{
    private $conn;
    private $username;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($this->conn->connect_error) {
            die("Konekcija nije uspela: " . $this->conn->connect_error);
        }
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function obrisiNalog()
    {
        $stmt = $this->conn->prepare("DELETE FROM korisnici WHERE korisnicko_ime = ?");
        $stmt->bind_param("s", $this->username);

        if ($stmt->execute()) {
            // Uništavanje sesije nakon brisanja naloga
            session_destroy();
            echo '<script>alert("Vaš nalog je uspešno obrisan."); window.location.href = "login.html";</script>';
            exit();
        } else {
            echo "Greška pri brisanju: " . $this->conn->error;
        }

        $stmt->close();
    }

    public function zatvoriKonekciju()
    {
        // Zatvaranje konekcije
        $this->conn->close();
    }
}

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Kreiranje instance klase Korisnik
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";
$korisnik = new Korisnik($servername, $username, $password, $dbname);
$korisnik->setUsername($_SESSION['username']);

// Provera da li je došlo do zahteva za brisanje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Brisanje naloga
    $korisnik->obrisiNalog();
    // Zatvaranje konekcije
    $korisnik->zatvoriKonekciju();
    exit();
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisanje naloga</title>
</head>
<body>
    <h1>Brisanje naloga</h1>
    <p>Da li ste sigurni? Brisanje naloga je trajno!</p>
    <form action="" method="POST">
        <input type="hidden" name="delete" value="1">
        <button type="submit">Obriši nalog</button>
        <a href="nalog.php">Odustani</a>
    </form>
</body>
</html>
