<?php
session_start();

// Klasa za rad sa bazom podataka
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

// Klasa za rad sa receptima
class Recept {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function dodajReceptBezProcedure($ime, $opis, $instrukcije, $dijeta, $napravio, $kategorija) {
        // SQL INSERT izjava
        $sql = "INSERT INTO recepti (ime, opis, instrukcije, dijeta, napravio, kategorija) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssss", $ime, $opis, $instrukcije, $dijeta, $napravio, $kategorija);

            if ($stmt->execute()) {
                return "Novi recept je uspešno dodat!";
            } else {
                return "Greška: " . $stmt->error;
            }

            $stmt->close();
        } else {
            return "Greška sa pripremom izjave: " . $this->db->getConnection()->error;
        }
    }
}

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Korisnički podaci
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";

// Uzimanje korisničkog imena iz sesije
$napravio = $_SESSION['username'];

$baza = new BazaPodataka($servername, $username, $password, $dbname);
$recept = new Recept($baza);

// Obrada podataka iz forme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = $_POST['ime'];
    $opis = $_POST['opis'];
    $instrukcije = $_POST['instrukcije'];
    $kategorija = $_POST['kategorija'];
    $dijeta = $_POST['dijeta'];

    if ($dijeta == "Sve") {
        $dijeta = ""; // Ako je "Sve", ostavi prazno
    }

    // Dodaj recept
    $poruka = $recept->dodajReceptBezProcedure($ime, $opis, $instrukcije, $dijeta, $napravio, $kategorija);
    echo $poruka;
}
?>
