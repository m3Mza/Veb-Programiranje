<?php
session_start();

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// ucitavanje klasa
require_once 'klase/BazaPodataka.php';
require_once 'klase/Recept.php';

// nova instanca recepta
$recept = new Recept("localhost", "root", "root", "sajt_baza");

// Uzimanje korisničkog imena iz sesije
$napravio = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mirkova Kujna | Vaši Recepti</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="forme.css">
    <link rel="icon" href="slike/ikonica.ico" type="image/x-icon">
</head>
<body>

<!-- SVG responzivna kriva -->
<div class="svg-kontejner">
    <svg viewbox="0 0 800 400" class="svg">
        <path id="curve" fill="#50c6d8" d="M 800 300 Q 400 350 0 300 L 0 0 L 800 0 L 800 300 Z"></path>
    </svg>
</div>

<header>
    <h1><?php echo htmlspecialchars($_SESSION['username']); ?>, ovde imate pristup vašim receptima!</h1>
</header>

<main>
    <section class="fiksirani-meni">
        <nav>
            <ul>
                <li><a href="index2.php">POČETNA</a></li>
                <li><a href="recepti2.php">RECEPTI</a></li>
                <li><a href="nalog.php">NALOG</a></li>
                <li><a href="kreator-recepata.php">VAŠI RECEPTI</a></li>
            </ul>
        </nav>
    </section>

    <h2 style="text-align: left; margin-left: 15%;">Kreator recepata</h2>
    <br><br>
    <hr class="separator">
    <br><br>

    <section class="recipe-form-section">
        <form action="submit-recept.php" method="POST">
            <!-- Forma za kreiranje novog recepta -->
            <label for="ime">Ime:</label>
            <input type="text" id="ime" name="ime" required>

            <label for="opis">Opis:</label>
            <textarea id="opis" name="opis" required></textarea>

            <label for="instrukcije">Instrukcije:</label>
            <textarea id="instrukcije" name="instrukcije" required></textarea>

            <label for="kategorija">Kategorija:</label>
            <select id="kategorija" name="kategorija" required>
                <option value="Meso">Meso</option>
                <option value="Dezerti">Dezerti</option>
                <option value="Pasta">Pasta</option>
                <option value="Testo">Testo</option>
                <option value="Pice">Piće</option>
                <option value="Riba">Riba</option>
                <option value="Salate">Salate</option>
                <option value="Grickalice">Grickalice</option>
                <option value="Sosevi">Sosevi</option>
                <option value="Kuvano">Čorbe i Kuvano</option>
            </select>

            <label>Ograničenje ishrane:</label>
            <div>
                <input type="radio" id="sve" name="dijeta" value="Sve" checked>
                <label for="sve">Sve</label>

                <input type="radio" id="vegan" name="dijeta" value="Vegan">
                <label for="vegan">Vegan</label>

                <input type="radio" id="vegeterijanac" name="dijeta" value="Vegeterijanac">
                <label for="vegeterijanac">Vegeterijanac</label>
            </div>

            <button type="submit">Kreiraj Recept</button>
        </form>
    </section>

    <br><br><br><br> 
    <hr class="separator">
    <br>

    <!-- Tabela sa receptima koje je korisnik kreirao -->
    <section class="user-recipes-section">
        <h2 align="left">Vaši Recepti</h2>
        <br><hr class="separator"><br><br>
        <table border="1" cellpadding="5" cellspacing="0" style="margin: 0 auto; text-align: center; width: 80%;">
            <thead>
                <tr>
                    <th>Ime Recepta</th>
                    <th>Izmeni</th>
                    <th>Obriši</th>
                </tr>
            </thead>
            <tbody>
            <?php

// metoda dohvatiRecepteKorisnika
$recepti = $recept->dohvatiRecepteKorisnika($napravio);

if ($recepti && $recepti->num_rows > 0) {
    while ($row = $recepti->fetch_assoc()) {
        $recept_id = $row['id'];
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ime']) . "</td>";
        echo "<td><a href='izmeni-recept.php?id=" . $recept_id . "'><button class='btn-edit'>Izmeni</button></a></td>";
        
        // Deo za brisanje
        echo "<td>";
        echo "<form action='obrisi-recept.php' method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='id' value='" . $recept_id . "'>";
        echo "<button type='submit' class='btn-delete' onclick='return confirm(\"Da li ste sigurni da želite da obrišete recept?\")'>Obriši</button>";
        echo "</form>";
        echo "</td>";
        
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>Nema kreiranih recepata.</td></tr>";
}
?>

            </tbody>
        </table>
    </section>

</main>

<footer>
    <div class="footer-content">
        <div class="footer-logo">
            <img src="slike/logo.png" alt="Mirkova Kujna Logo">
        </div>
        <div>
            <br><br><br>
            <div class="footer-linkovi">
                <a href="index2.php">POČETNA</a>
                <a href="recepti2.php">RECEPTI</a>
            </div>
        </div>
        <div class="footer-info">
            <br><br><br>
            <small>Mirkova Kujna, 2024.</small>
            <small>Lokacija: Zrenjanin</small>
            <small>EMAIL: mimamirkop@gmail.com</small>
            <a href="https://www.instagram.com/nikonigde21/"><small>Instagram</small></a>
        </div>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>
