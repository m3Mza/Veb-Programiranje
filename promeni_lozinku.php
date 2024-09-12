<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

class BazaPodataka {
    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "sajt_baza";
    protected $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Veza neuspešna: " . $this->conn->connect_error);
        }
    }

    public function close() {
        $this->conn->close();
    }
}

class Korisnik extends BazaPodataka {
    public function changePassword($username, $newPassword) {
        // Ažuriraj lozinku u bazi
        $stmt = $this->conn->prepare("UPDATE korisnici SET lozinka = ? WHERE korisnicko_ime = ?");
        $stmt->bind_param("ss", $newPassword, $username);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

$username = $_SESSION['username'];
$new_password = $_POST['new_password'];

$korisnik = new Korisnik();

if ($korisnik->changePassword($username, $new_password)) {
    echo '<script>alert("Lozinka je uspešno promenjena."); window.location.href = "nalog.php";</script>';
} else {
    echo "Greška pri menjanju lozinke: " . $korisnik->conn->error;
}

$korisnik->close();
?>
