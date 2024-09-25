<?php
session_start();

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

require_once 'klase/Recept.php'; // Uključi klasu Recept

// Kreira instancu klase Recept
$recept = new Recept();

// uzima korisnicko ime
$napravio = $_SESSION['username'];

// hvata recept id
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

// Novi prozor sa formom
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
    <h2 style="text-align: left; margin-left: 15%;">Izmena recepta</h2>
    <hr class="separator">

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    
    <form action="" method="POST" align="center">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($detaljiRecepta['id']); ?>">
        
        <label for="ime">Ime:</label>
        <input type="text" id="ime" name="ime" value="<?php echo htmlspecialchars($detaljiRecepta['ime']); ?>" required>
        
        <label for="opis">Opis:</label>
        <textarea id="opis" name="opis" required><?php echo htmlspecialchars($detaljiRecepta['opis']); ?></textarea>
        
        <label for="instrukcije">Instrukcije:</label>
        <textarea id="instrukcije" name="instrukcije" required><?php echo htmlspecialchars($detaljiRecepta['instrukcije']); ?></textarea>
        
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

        <label>Ograničenje ishrane:</label>
        <div>
            <input type="radio" id="sve" name="dijeta" value="Sve" <?php echo ($detaljiRecepta['dijetalna_restrikcija'] == 'Sve') ? 'checked' : ''; ?>>
            <label for="sve">Sve</label>

            <input type="radio" id="vegan" name="dijeta" value="Vegan" <?php echo ($detaljiRecepta['dijetalna_restrikcija'] == 'Vegan') ? 'checked' : ''; ?>>
            <label for="vegan">Vegan</label>

            <input type="radio" id="vegeterijanac" name="dijeta" value="Vegeterijanac" <?php echo ($detaljiRecepta['dijetalna_restrikcija'] == 'Vegeterijanac') ? 'checked' : ''; ?>>
            <label for="vegeterijanac">Vegeterijanac</label>
        </div>

        <button type="submit">Izmeni Recept</button>
    </form>
</div>

</body>
</html>
