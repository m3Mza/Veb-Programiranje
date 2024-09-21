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

    // Dohvati recept po ID-u
    public function dohvatiRecept($id, $autor) {
        $sql = "SELECT * FROM recepti WHERE id = ? AND napravio = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("is", $id, $autor);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Ažuriraj recept
    public function azurirajRecept($id, $ime, $opis, $instrukcije, $kategorija, $dijeta) {
        $sql = "UPDATE recepti SET ime = ?, opis = ?, instrukcije = ?, kategorija = ?, dijeta = ? WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("sssssi", $ime, $opis, $instrukcije, $kategorija, $dijeta, $id);
        return $stmt->execute();
    }
}

// Povezivanje sa bazom
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";
$baza = new BazaPodataka($servername, $username, $password, $dbname);
$recept = new Recept($baza);

// Uzimanje korisničkog imena iz sesije
$napravio = $_SESSION['username'];

// Provera da li je prosleđen ID recepta
if (isset($_GET['id'])) {
    $receptId = intval($_GET['id']);
    $detaljiRecepta = $recept->dohvatiRecept($receptId, $napravio);
}

// Ažuriranje recepta ako je forma poslata
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $ime = $_POST['ime'];
    $opis = $_POST['opis'];
    $instrukcije = $_POST['instrukcije'];
    $kategorija = $_POST['kategorija'];
    $dijeta = $_POST['dijeta'];
    
    if ($recept->azurirajRecept($_POST['id'], $ime, $opis, $instrukcije, $kategorija, $dijeta)) {
        header("Location: kreator-recepata.php?message=Recept uspešno ažuriran");
        exit();
    } else {
        $error = "Greška pri ažuriranju recepta.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izmeni Recept</title>
    <link rel="stylesheet" href="forme.css">
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>

<!-- Forma za izmenu recepta -->
<div class="recipe-form-section">
    <br>
    <h2 style="text-align: left; margin-left: 15%;">Izmena recepta</h2>
<br><br>
<hr class="separator">
<br><br>
    <br><br><br>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <form action="" method="POST" align="center">
        <input type="hidden" name="id" value="<?php echo $detaljiRecepta['id']; ?>">
        
        <label for="ime">Ime:</label>
        <input type="text" id="ime" name="ime" value="<?php echo htmlspecialchars($detaljiRecepta['ime']); ?>" required>
        <br><br>

        <label for="opis">Opis:</label>
        <textarea id="opis" name="opis" required><?php echo htmlspecialchars($detaljiRecepta['opis']); ?></textarea>
        <br><br>

        <label for="instrukcije">Instrukcije:</label>
        <textarea id="instrukcije" name="instrukcije" required><?php echo htmlspecialchars($detaljiRecepta['instrukcije']); ?></textarea>
        <br><br>

        <label for="kategorija">Kategorija:</label>
        <select id="kategorija" name="kategorija" required>
            <option value="Meso" <?php echo ($detaljiRecepta['kategorija'] == 'Meso') ? 'selected' : ''; ?>>Meso</option>
            <option value="Dezerti" <?php echo ($detaljiRecepta['kategorija'] == 'Dezerti') ? 'selected' : ''; ?>>Dezerti</option>
            <option value="Pasta" <?php echo ($detaljiRecepta['kategorija'] == 'Pasta') ? 'selected' : ''; ?>>Pasta</option>
            <option value="Testo" <?php echo ($detaljiRecepta['kategorija'] == 'Testo') ? 'selected' : ''; ?>>Testo</option>
            <option value="Pice" <?php echo ($detaljiRecepta['kategorija'] == 'Pice') ? 'selected' : ''; ?>>Piće</option>
            <option value="Riba" <?php echo ($detaljiRecepta['kategorija'] == 'Riba') ? 'selected' : ''; ?>>Riba</option>
            <option value="Salate" <?php echo ($detaljiRecepta['kategorija'] == 'Salate') ? 'selected' : ''; ?>>Salate</option>
            <option value="Grickalice" <?php echo ($detaljiRecepta['kategorija'] == 'Grickalice') ? 'selected' : ''; ?>>Grickalice</option>
            <option value="Sosevi" <?php echo ($detaljiRecepta['kategorija'] == 'Sosevi') ? 'selected' : ''; ?>>Sosevi</option>
            <option value="Kuvano" <?php echo ($detaljiRecepta['kategorija'] == 'Kuvano') ? 'selected' : ''; ?>>Čorbe i Kuvano</option>
        </select>
        <br><br>

        <label>Ograničenje ishrane:</label>
        <div>
            <input type="radio" id="sve" name="dijeta" value="Sve" <?php echo ($detaljiRecepta['dijetalna_restrikcija'] == 'Sve') ? 'checked' : ''; ?>>
            <label for="sve">Sve</label>

            <input type="radio" id="vegan" name="dijeta" value="Vegan" <?php echo ($detaljiRecepta['dijetalna_restrikcija'] == 'Vegan') ? 'checked' : ''; ?>>
            <label for="vegan">Vegan</label>

            <input type="radio" id="vegeterijanac" name="dijeta" value="Vegeterijanac" <?php echo ($detaljiRecepta['dijetalna_restrikcija'] == 'Vegeterijanac') ? 'checked' : ''; ?>>
            <label for="vegeterijanac">Vegeterijanac</label>
        </div>
        <br><br>

        <button type="submit">Izmeni Recept</button>
    </form>
</div>


</body>
</html>
