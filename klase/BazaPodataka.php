<?php
class BazaPodataka {
    protected $konekcija;

    public function __construct() {
        $servername = "localhost";
        $username = "root";         
        $password = "root";         
        $dbname = "sajt_baza";      

        // Konekcija sa bazom podataka
        $this->konekcija = new mysqli($servername, $username, $password, $dbname);

        // Provera konekcije
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
?>
