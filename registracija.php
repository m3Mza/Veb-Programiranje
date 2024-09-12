<?php
class BazaPodataka {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Veza neuspešna: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function __destruct() {
        $this->conn->close();
    }
}

class Korisnik {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function registrujKorisnika($korisnickoIme, $lozinka) {
        $stmt = $this->db->getConnection()->prepare("CALL RegistracijaKorisnika(?, ?)");

        if ($stmt) {
            $stmt->bind_param("ss", $korisnickoIme, $lozinka);

            if ($stmt->execute()) {
                header("Location: index2.php");
                exit();
            } else {
                echo "Greška: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Greška: " . $this->db->getConnection()->error;
        }
    }
}

// Korisnički podaci
$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "sajt_baza";

$baza = new BazaPodataka($servername, $username, $password, $dbname);
$korisnik = new Korisnik($baza);

// Pribavljanje podataka iz forme
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $korisnicko_ime = $_POST['username'];
    $lozinka = $_POST['lozinka'];

    // Registracija korisnika
    $korisnik->registrujKorisnika($korisnicko_ime, $lozinka);
}
?>
