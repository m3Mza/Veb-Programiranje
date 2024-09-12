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
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mirkova Kujna | Nalog</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nalozi.css">
    <link rel="stylesheet" href="forme.css">
    <link rel="icon" href="slike/ikonica.ico" type="image/x-icon">
</head>
<body>

<!-- SVG responzivna kriva-->
<div class="svg-kontejner">
    <svg viewbox="0 0 800 400" class="svg">
        <path id="curve" fill="#50c6d8" d="M 800 300 Q 400 350 0 300 L 0 0 L 800 0 L 800 300 Z"></path>
    </svg>
</div>
  
<header>
    <h1>Vaš Nalog. 🏡</h1>
</header>

<main>

    <!-- Navigacioni meni koji iskace -->
    <section class="fiksirani-meni">
        <nav>
            <ul>
                <li><a href="index2.php">POČETNA</a></li>
                <li><a href="recepti2.php">RECEPTI</a></li>
                <li><a href="kontakt2.php">INFO</a></li>
                <li><a href="nalog.php">NALOG</a></li>
                <li><a href="kreator-recepata.php">VAŠI RECEPTI</a></li>
            </ul>
        </nav>
    </section>

    <div class="nalog-sekcija">
        <div class="user-info">
            <div class="info-predmet">
                <label for="username">Korisničko ime:</label>
                <span id="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>

            <!-- Promena lozinke -->
            <div class="info-predmet">
    <form action="promeni_lozinku.php" method="POST">
        <label for="new-password">Ukucaj novu lozinku:</label>
        <div class="lozinka-kontejner">
            <input type="password" id="new-password" name="new_password" required>
            <span class="toggle-lozinka" onclick="toggleLozinku('new-password')">👁️</span>
        </div>
        <button type="submit" class="promeni-dugme">Promeni lozinku</button>
    </form>
</div>


            <!-- Odjavljivanje i brisanje naloga -->  
            <div class="info-predmet">
                <form action="logout.php" method="POST">
                    <button type="submit" class="promeni-dugme">ODJAVI SE</button>
                </form>
            </div>
            
            <div class="info-predmet">
                <form action="brisanje.php" method="POST">
                    <button type="submit" class="promeni-dugme">OBRIŠI NALOG</button>
                </form>
            </div>
        </div>
        <div class="profilna-slika">
            <img src="slike/logo.png" alt="Profilna slika" class="slicica-nalog-stranica">
        </div>
    </div>
</main>

<footer>
    <div class="footer-content">
        <div class="footer-logo">
            <img src="slike/logo.png" alt="Mirkova Kujna Logo">
        </div>
        <div>
            <br><br><br>
            <div class="footer-linkovi">
                <a href="index.html">POČETNA</a>
                <a href="recepti.html">RECEPTI</a>
                <a href="kontakt.html">INFO</a>
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
<script>
  function toggleLozinku(fieldId) {
        const input = document.getElementById(fieldId);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
    }
</script>   
</body>
</html>
