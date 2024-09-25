<?php
require_once 'klase/BazaPodataka.php'; // Uključi klasu BazaPodataka

class Recept extends BazaPodataka {
    
    public function __construct() {
        parent::__construct();
    }

    // Funkcija hvata recept kako bi znala koji recept da izmeni
    public function dohvatiRecept($id, $autor) {
        $sql = "SELECT * FROM recepti WHERE id = ? AND napravio = ?";
        $stmt = $this->konekcija->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("is", $id, $autor);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $result; 
        } else {
            throw new Exception("Greška pri pripremi upita za dohvatanje recepta.");
        }
    }

    // Azuriranje recepta (IZMENA)
    public function azurirajRecept($id, $ime, $opis, $instrukcije, $kategorija, $dijeta) {
        $sql = "UPDATE recepti SET ime = ?, opis = ?, instrukcije = ?, kategorija = ?, dijeta = ? WHERE id = ?";
        $stmt = $this->konekcija->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("sssssi", $ime, $opis, $instrukcije, $kategorija, $dijeta, $id);
            $success = $stmt->execute();
            $stmt->close();
            return $success; 
        } else {
            throw new Exception("Greška pri pripremi upita za ažuriranje recepta.");
        }
    }

    // Funkcija za brisanje recepta
    public function obrisiRecept($id, $napravio) {
        $sql = "DELETE FROM recepti WHERE id = ? AND napravio = ?";
        $stmt = $this->konekcija->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("is", $id, $napravio); 
            $stmt->execute();
            $affected_rows = $stmt->affected_rows; 
            $stmt->close(); 
    
            return $affected_rows > 0; // Provera da li je recept obrisan
        } else {
            throw new Exception("Greška pri pripremi upita za brisanje recepta.");
        }
    }

    // Da li recept postoji za brisanje (Debuggovanje)
    public function receptPostoji($id, $napravio) {
        $sql = "SELECT * FROM recepti WHERE id = ? AND napravio = ?";
        $stmt = $this->konekcija->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("is", $id, $napravio);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            return $result->num_rows > 0; // Vraća true ako recept postoji
        } else {
            throw new Exception("Greška pri pripremi upita za proveru recepta.");
        }
    }
    
    
    // Dohvatanje recepata koje je korisnik kreirao
    public function dohvatiRecepteKorisnika($napravio) {
        $sql = "SELECT * FROM recepti WHERE napravio = ?";
        $stmt = $this->konekcija->prepare($sql);
        $stmt->bind_param("s", $napravio);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Metoda za dodavanje recepata (INSERT sa procedurom)

    public function dodajRecept($ime, $opis, $instrukcije, $dijeta, $napravio, $kategorija) {
        $sql = "CALL DodajRecept(?, ?, ?, ?, ?, ?)";
        $stmt = $this->konekcija->prepare($sql); 
    
        if ($stmt) {
            $stmt->bind_param("ssssss", $ime, $opis, $instrukcije, $dijeta, $napravio, $kategorija);
    
            // Izvrši izjavu
            if ($stmt->execute()) {
                return "Novi recept je uspešno dodat!";
            } else {
                return "Greška prilikom izvršavanja: " . $stmt->error;
            }
        } else {
            return "Greška sa pripremom izjave: " . $this->konekcija->error;
        }
    }
    

}




?>
