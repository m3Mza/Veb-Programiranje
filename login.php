<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "sajt_baza";

class BazaPodataka {
    protected $konekcija;

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

class Autentifikator extends BazaPodataka {
    private $korisnickoIme;
    private $lozinka;

    public function __construct($servername, $username, $password, $dbname, $korisnickoIme, $lozinka) {
        parent::__construct($servername, $username, $password, $dbname);
        $this->korisnickoIme = $korisnickoIme;
        $this->lozinka = $lozinka;
    }

    public function autentifikuj() {
        $konekcija = $this->dohvatiKonekciju();
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
        $this->zatvoriKonekciju();
    }
}

// Kreiranje instance autentifikatora
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $korisnickoIme = $_POST['username'];
    $lozinka = $_POST['password'];

    // Kreiranje instance baze podataka
    $baza = new BazaPodataka($servername, $username, $password, $dbname);
    
    // Kreiranje instance autentifikatora
    $autentifikator = new Autentifikator($servername, $username, $password, $dbname, $korisnickoIme, $lozinka);
    $rezultat = $autentifikator->autentifikuj();

    if ($rezultat) {
        echo $rezultat;
    }
}
?>
