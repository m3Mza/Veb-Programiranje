<?php
require_once 'klase/BazaPodataka.php'; // Uključi klasu BazaPodataka

class Korisnik extends BazaPodataka {
    private $username;

    public function __construct() {
        parent::__construct(); // Poziva konstruktor BazaPodataka bez parametara
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function registrujKorisnika($korisnickoIme, $lozinka) {
        // Priprema SQL upita za poziv stored procedure
        $stmt = $this->konekcija->prepare("CALL RegistracijaKorisnika(?, ?)");

        if ($stmt) {
            $stmt->bind_param("ss", $korisnickoIme, $lozinka);
            
            if ($stmt->execute()) {
                header("Location: index2.php"); // Redirekcija na drugu stranicu
                exit();
            } else {
                echo "Greška: " . $stmt->error; 
            }

            $stmt->close();
        } else {
            echo "Greška: " . $this->konekcija->error;
        }
    }

    public function promeniLozinku($newPassword) {
        // Ažuriraj lozinku u bazi
        $stmt = $this->konekcija->prepare("UPDATE korisnici SET lozinka = ? WHERE korisnicko_ime = ?");
        $stmt->bind_param("ss", $newPassword, $this->username);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function obrisiNalog() {
        // Proverite da li je korisničko ime postavljeno
        if (empty($this->username)) {
            echo "Greška: Korisničko ime nije postavljeno.";
            return;
        }
    
        $stmt = $this->konekcija->prepare("DELETE FROM korisnici WHERE korisnicko_ime = ?");
        if ($stmt === false) {
            echo "Greška pri pripremi SQL upita: " . $this->konekcija->error;
            return;
        }
    
        $stmt->bind_param("s", $this->username);
    
        if ($stmt->execute()) {
            // Uništavanje sesije nakon brisanja naloga
            session_destroy();
            echo '<script>alert("Vaš nalog je uspešno obrisan."); window.location.href = "login.html";</script>';
            exit();
        } else {
            echo "Greška pri brisanju: " . $stmt->error;
        }
    
        $stmt->close();
    }
    
}
?>
