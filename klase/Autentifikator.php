<?php

require_once 'BazaPodataka.php'; // Uključivanje baze podataka

class Autentifikator extends BazaPodataka {
    private $korisnickoIme;
    private $lozinka;

    public function __construct($korisnickoIme, $lozinka) {
        parent::__construct();  // Pozivanje konstruktora iz BazaPodataka klase
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
?>
