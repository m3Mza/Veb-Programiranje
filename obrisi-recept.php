<?php
session_start(); // Start the session

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

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

    // Funkcija za brisanje recepta po ID-u
    public function obrisiRecept($id, $imeAutora) {
        $sql = "DELETE FROM recepti WHERE id = ? AND napravio = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("is", $id, $imeAutora);
            $stmt->execute();
            $stmt->close();

            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

// Povezivanje sa bazom
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";
$baza = new BazaPodataka($servername, $username, $password, $dbname);
$recept = new Recept($baza);

// Provera da li je prosleđen ID recepta za brisanje
if (isset($_GET['id'])) {
    $receptId = intval($_GET['id']);
    $imeAutora = $_SESSION['username'];

    // Pokušaj brisanja recepta
    if ($recept->obrisiRecept($receptId, $imeAutora)) {
        // Uspešno brisanje, redirekcija na stranicu sa receptima korisnika
        header("Location: kreator-recepata.php?poruka=uspesno_brisanje");
        exit();
    } else {
        // Greška prilikom brisanja
        header("Location: kreator-recepata.php?poruka=greska_brisanja");
        exit();
    }
} else {
    // Ako ID nije prosleđen, redirekcija na glavnu stranicu sa greškom
    header("Location: kreator-recepata.php?poruka=nepostojeci_id");
    exit();
}
?>
