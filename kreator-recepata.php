<?php
session_start(); // Start the session

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    // Ako nije redirekcija
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
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
    <h1><?php echo htmlspecialchars($_SESSION['username']); ?>, ovde možete kreirati vaše recepte!</h1>
</header>

<main>
    

<!-- ====================================================================================================

  NAV MENI

  ========================================================================================================= -->

    <section class="fiksirani-meni">
        <nav>
            <ul>
                <li><a href="index2.php">POČETNA</a></li>
                <li><a href="recepti2.php">RECEPTI</a></li>
                <li><a href="kontakt2.php">INFO</a></li>
                <li><a href="nalog.php">NALOG</a></li>
                <li><a href="recipe-form-section.php">VAŠI RECEPTI</a></li>
            </ul>
        </nav>
    </section>

   <!-- ====================================================================================================

  MIRKO, THE KREATOR

  ========================================================================================================= -->


    <h2 style="text-align: left; margin-left: 15%;">Kreator recepata</h2>
    <br><br>
    <hr class="separator">
    <br><br>

    <section class="recipe-form-section">
        <form action="submit-recept.php" method="POST">
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
                <a href="kontakt2.php">INFO</a>
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
