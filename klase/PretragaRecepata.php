<?php
require 'BazaPodataka.php'; // Uključivanje BazaPodataka klase

class PretragaRecepata extends BazaPodataka {
    public function __construct() {
        parent::__construct(); // Pozivanje konstruktora BazaPodataka
    }

    public function pretraziRecepte($searchTerm, $filter, $kategorija) {
        $conn = $this->dohvatiKonekciju(); // Dobijanje konekcije
        $sql = "SELECT * FROM view_recepti WHERE ime LIKE ?";
        $params = ["%$searchTerm%"];
        $types = "s";
    
        // Dodavanje uslova za dijetalne restrikcije
        if ($filter === 'Vegan') {
            $sql .= " AND dijeta = 'Vegan'";
        } elseif ($filter === 'Vegeterijanac') {
            $sql .= " AND dijeta = 'Vegeterijanac'";
        }
    
        // Dodavanje uslova za kategoriju
        if (!empty($kategorija)) {
            $sql .= " AND kategorija = ?";
            $params[] = $kategorija;
            $types .= "s";
        }
    
        // Priprema upita
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Greška sa pripremom upita: " . $conn->error);
        }
    
        // Bindovanje parametara
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
    
        // Vraćanje rezultata
        return $stmt->get_result();
    }
    



    public function izvuciKategorije() {
        // SQL upit za dobijanje svih kategorija
        $sql = "SELECT naziv FROM kategorije";
        $result = $this->konekcija->query($sql);

        if ($result) {
            return $result; // Vraća rezultat koji može biti ispražnjen
        } else {
            return null; // Vraća null ako je došlo do greške
        }
    }
}
?>

<?php