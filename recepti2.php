
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mirkova Kujna | Recepti</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="forme.css">
    <link rel="stylesheet" href="lajkovi.css">
    <link rel="icon" href="slike/ikonica.ico" type="image/x-icon">
    
</head>
<body>

<!-- SVG responzivna kriva-->

    <div class="svg-kontejner">
      <svg viewbox="0 0 800 400" class="svg">
        <path id="curve" fill="#50c6d8" d="M 800 300 Q 400 350 0 300 L 0 0 L 800 0 L 800 300 Z">
        </path>
      </svg>
    </div>
  
<header>
    <h1>Recepti.</h1>
</header>

    <main>
        
         <!-- Navigacioni meni koji iskace -->
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
      
  <!-- ====================================================================================================

  Brza pretraga recepata

  ========================================================================================================= -->

  <h2 style="text-align: left; margin-left: 15%;">Daj nešto nabrzaka!</h2>
<br><br>
<hr class="separator">
<br><br>

<div class="search-container">
    <form method="GET" action="recepti2.php">
        <input type="text" name="search" placeholder="Pretraži recepte...">

        <label>
            <input type="checkbox" name="vegan"> Vegan
        </label>
        <label>
            <input type="checkbox" name="vegeterijanac"> Vegeterijanac
        </label>
        <label>
            <input type="checkbox" name="sve"> Sve
        </label>

        <!-- Dropdown lista za kategorije -->
        <label for="kategorija">Izaberi kategoriju:</label>
        <select name="kategorija" id="kategorija">
            <option value="">Sve kategorije</option>
            <?php
            // Povezivanje sa bazom i dobijanje kategorija
            require_once 'klase/PretragaRecepata.php';
            $baza = new PretragaRecepata();
            $kategorije = $baza->izvuciKategorije();

            if ($kategorije) {
                while ($row = $kategorije->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['naziv']) . "'>" . htmlspecialchars($row['naziv']) . "</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Pretraga</button>
        <button type="button" class="clear-button" onclick="clearResults()">Očisti rezultate</button>
    </form>
</div>


<!-- PHP povezuje se sa bazom da prikaze rezultate -->

<div id="searchResults">
<?php
session_start();

require_once 'klase/PretragaRecepata.php'; // UKLJUCUJE SE PRETRAGA RECEPATA

// Kreiranje instance pretrage recepata
$baza = new PretragaRecepata();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $filter = isset($_GET['vegan']) ? 'Vegan' : (isset($_GET['vegeterijanac']) ? 'Vegeterijanac' : 'Sve');
    $kategorija = $_GET['kategorija'] ?? '';

    // Pozivanje metode za pretragu recepata
    $result = $baza->pretraziRecepte($searchTerm, $filter, $kategorija);

    if ($result && $result->num_rows > 0) {
        echo "<br><br>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Ime</th><th>Opis</th><th>Instrukcije</th><th>Dijetalna restrikcija</th><th>Kategorija</th><th>Štampaj recept</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a href='#' class='recipe-link' data-ime='" . htmlspecialchars($row['ime']) . "' data-opis='" . htmlspecialchars($row['opis']) . "' data-instrukcije='" . htmlspecialchars($row['instrukcije']) . "'>";
            echo htmlspecialchars($row['ime']) . "</a></td>";
            echo "<td>" . htmlspecialchars($row['opis']) . "</td>";
            echo "<td>" . htmlspecialchars($row['instrukcije']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dijeta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategorija']) . "</td>";
            echo "<td><a href='printer-friendly.php?ime=" . urlencode($row['ime']) . "' target='_blank'>Štampaj</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Nema rezultata za upit: " . htmlspecialchars($searchTerm) . "</p>";
    }
}
?>

</div>







<!-- js skripte za ciscenje rezultata i js koji prikazuje stranicu kao dinamicku pop-up stranicu -->

    <script>
        // JS ciscenje rezultata
        function clearResults() {
            document.getElementById('searchResults').innerHTML = '';
        }
</script>

<br><br><br><br> <hr class="separator">

  

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