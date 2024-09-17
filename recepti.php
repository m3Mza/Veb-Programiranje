
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
                  <li><a href="index.php">POČETNA</a></li>
                  <li><a href="recepti.php">RECEPTI</a></li>
                  <li><a href="kontakt.php">INFO</a></li>
                  <li><a href="login.html">PRIJAVA</a></li>
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
    <form method="GET" action="recepti.php">
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
            <option value="Meso">Meso</option>
            <option value="Dezerti">Dezerti</option>
            <option value="Pasta">Pasta</option>
            <option value="Testo">Testo</option>
            <option value="Piće">Piće</option>
            <option value="Riba">Riba</option>
            <option value="Salate">Salate</option>
            <option value="Grickalice">Grickalice</option>
            <option value="Sosevi">Sosevi</option>
            <option value="Kuvano">Kuvano</option>
        </select>

        <button type="submit">Pretraga</button>
        <button type="button" class="clear-button" onclick="clearResults()">Očisti rezultate</button>
    </form>
</div>

<!-- PHP povezuje se sa bazom da prikaze rezultate -->

<div id="searchResults">
    <?php
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

    // Klasa za pretragu recepata koja nasledjuje BazaPodataka
    class PretragaRecepata extends BazaPodataka {
        public function __construct($servername, $username, $password, $dbname) {
            parent::__construct($servername, $username, $password, $dbname);
        }

        public function pretraziRecepte($searchTerm, $filter, $kategorija) {
            $conn = $this->getConnection();
            $sql = "SELECT * FROM recepti WHERE ime LIKE ?";
            $params = ["%$searchTerm%"];
            $types = "s";

            if ($filter === 'Vegan') {
                $sql .= " AND dijeta = 'Vegan'";
            } elseif ($filter === 'Vegeterijanac') {
                $sql .= " AND dijeta = 'Vegeterijanac'";
            }

            if (!empty($kategorija)) {
                $sql .= " AND kategorija = ?";
                $params[] = $kategorija;
                $types .= "s";
            }

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Greška sa pripremom upita: " . $conn->error);
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return $stmt->get_result();
        }
    }

    // Povezivanje sa bazom
    $baza = new PretragaRecepata("localhost", "root", "root", "sajt_baza");

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $filter = isset($_GET['vegan']) ? 'Vegan' : (isset($_GET['vegeterijanac']) ? 'Vegeterijanac' : 'Sve');
        $kategorija = $_GET['kategorija'] ?? '';

        $result = $baza->pretraziRecepte($searchTerm, $filter, $kategorija);

        if ($result->num_rows > 0) {
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
                echo "<td><a href='printer-friendly.php?ime=" . urlencode($row['ime']) . "' target='_blank'>Štampaj</a></td>"; // Link za štampanje
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



 <!-- ====================================================================================================

 Galerija kategorija

  ========================================================================================================= -->
  
<div class="galerija">
  <a href="link-to-meso-category.html" class="galerija-slika mala tilt-levo">
    <img src="slike/mesoKategorija.webp" alt="Meso">
    <div class="ime-kategorije">Meso</div>
  </a>
  <a href="link-to-dezerti-category.html" class="galerija-slika velika tilt-desno">
    <img src="slike/dezertiKategorija.webp" alt="Dezerti">
    <div class="ime-kategorije">Dezerti</div>
  </a>
  <a href="link-to-pasta-category.html" class="galerija-slika srednja">
    <img src="slike/pastaKategorija.webp" alt="Pasta">
    <div class="ime-kategorije">Pasta</div>
  </a>
  <a href="link-to-testo-category.html" class="galerija-slika default tilt-levo">
    <img src="slike/testoKategorija.webp" alt="Testo">
    <div class="ime-kategorije">Testo</div>
  </a>
  <a href="link-to-pice-category.html" class="galerija-slika mala">
    <img src="slike/piceKategorija.webp" alt="Piće">
    <div class="ime-kategorije">Piće</div>
  </a>
  <a href="link-to-riba-category.html" class="galerija-slika velika tilt-desno">
    <img src="slike/ribaKategorija.webp" alt="Riba i Morski Plodovi">
    <div class="ime-kategorije">Riba i Morski Plodovi</div>
  </a>
  <a href="link-to-salata-category.html" class="galerija-slika srednja">
    <img src="slike/salataKategorija.webp" alt="Voće i Povrće">
    <div class="ime-kategorije">Voće i Povrće</div>
  </a>
  <a href="link-to-grickalice-category.html" class="galerija-slika default tilt-levo">
    <img src="slike/grickaliceKategorija.webp" alt="Grickalice">
    <div class="ime-kategorije">Grickalice</div>
  </a>
  <a href="link-to-sos-category.html" class="galerija-slika mala tilt-desno">
    <img src="slike/sosKategorija.webp" alt="Sosevi">
    <div class="ime-kategorije">Sosevi</div>
  </a>
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
              <a href="index.php">POČETNA</a>
              <a href="recepti.php">RECEPTI</a>
              <a href="kontakt.php">INFO</a>
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