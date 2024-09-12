<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "sajt_baza";

class BazaPodataka {
    private $konekcija;

    public function __construct($servername, $username, $password, $dbname) {
        $this->konekcija = new mysqli($servername, $username, $password, $dbname);

        if ($this->konekcija->connect_error) {
            die("Greška prilikom povezivanja: " . $this->konekcija->connect_error);
        }
    }

    public function dohvatiKonekciju() {
        return $this->konekcija;
    }

    public function zatvoriKonekciju() {
        $this->konekcija->close();
    }
}

class Autentifikator {
    private $baza;
    private $korisnickoIme;
    private $lozinka;

    public function __construct($baza, $korisnickoIme, $lozinka) {
        $this->baza = $baza;
        $this->korisnickoIme = $korisnickoIme;
        $this->lozinka = $lozinka;
    }

    public function autentifikuj() {
        $konekcija = $this->baza->dohvatiKonekciju();
        $upit = $konekcija->prepare("SELECT lozinka FROM korisnici WHERE korisnicko_ime = ?");
        $upit->bind_param("s", $this->korisnickoIme);
        $upit->execute();
        $upit->store_result();

        if ($upit->num_rows > 0) {
            $upit->bind_result($sacuvanaLozinka);
            $upit->fetch();

            if ($this->lozinka === $sacuvanaLozinka) {
                $_SESSION['username'] = $this->korisnickoIme;
                header("Location: index2.php");
                exit();
            } else {
                return "Pogrešna lozinka.";
            }
        } else {
            return "Korisničko ime ne postoji.";
        }

        $upit->close();
        $this->baza->zatvoriKonekciju();
    }
}

$baza = new BazaPodataka($servername, $username, $password, $dbname);

// Provera da li je forma poslala podatke
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $korisnickoIme = $_POST['username'];
    $lozinka = $_POST['password'];

    $autentifikator = new Autentifikator($baza, $korisnickoIme, $lozinka);
    $rezultat = $autentifikator->autentifikuj();

    if ($rezultat) {
        echo $rezultat;
    }
}
?>
