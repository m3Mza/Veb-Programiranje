<?php
session_start();

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['username'])) {
    // Ako nije, redirekcija
    header("Location: login.html");
    exit();
}
?>




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
                  <li><a href="kontakt2.php">INFO</a></li>
                  <li><a href="nalog.php">NALOG</a></li>
                  <li><a href="recipe-form-section.php">VAŠI RECEPTI</a></li>

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
            <button type="submit">Pretraga</button>
            <button type="button" class="clear-button" onclick="clearResults()">Očisti rezultate</button>
        </form>
    </div>

    <!-- php povezuje se sa bazom da prikaze rezultate -->

    <div id="searchResults">
        <?php

        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "sajt_baza";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Veza neuspesna: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
            $searchTerm = $_GET['search'];

            $sql = "SELECT * FROM recepti WHERE ime LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";

            // Filterisanje
            if (isset($_GET['vegan'])) {
                $sql .= " AND dijeta = 'Vegan'";
            } elseif (isset($_GET['vegeterijanac'])) {
                $sql .= " AND dijeta = 'Vegeterijanac'";
            } elseif (isset($_GET['sve'])) {
                
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='search-results'>";
                while ($row = $result->fetch_assoc()) {
                   echo "<a href='#' class='recipe-link' data-ime='" . htmlspecialchars($row['ime']) . "' data-opis='" . htmlspecialchars($row['opis']) . "' data-instrukcije='" . htmlspecialchars($row['instrukcije']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['ime']) . "</h3>";
                      echo "</a>";

                }
                echo "</div>";
            } else {
                echo "<p>Nema rezultata za upit: " . htmlspecialchars($searchTerm) . "</p>";
            }

            $result->free_result();
        }

        $conn->close();
        ?>
    </div>

    <!-- js skripte za ciscenje rezultata i js koji prikazuje stranicu kao dinamicku pop-up stranicu -->

    <script>
        // JS ciscenje rezultata
        function clearResults() {
            document.getElementById('searchResults').innerHTML = '';
        }

        // JS za pop-up

        document.addEventListener('DOMContentLoaded', function() {
            var recipeLinks = document.querySelectorAll('.recipe-link');

            recipeLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    var ime = this.getAttribute('data-ime');
                    var opis = this.getAttribute('data-opis');
                    var instrukcije = this.getAttribute('data-instrukcije');
                    
                    // Uzima userID iz sesije
                    var userId = <?php echo json_encode(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null); ?>;
                    
                    openRecipePopup(ime, opis, instrukcije, userId);
                });
            });

            function openRecipePopup(ime, opis, instrukcije, userId) {
                var url = 'dinamicka-stranica.php';
                url += '?ime=' + encodeURIComponent(ime);
                url += '&opis=' + encodeURIComponent(opis);
                url += '&instrukcije=' + encodeURIComponent(instrukcije);
                url += '&user_id=' + encodeURIComponent(userId);
                
                var popupWindow = window.open(url, "RecipePopup", "width=800,height=600");
                
            }
        });
</script>

      <br><br><br><br> <hr class="separator">

   <!-- ====================================================================================================

  Rang lista recepata

  ========================================================================================================= -->

<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veza neuspesna: " . $conn->connect_error);
}

// Uzima recepte po ocenama top 10 lista
$sql = "SELECT recept_ime, lajk FROM recenzije ORDER BY lajk DESC LIMIT 10";
$result = $conn->query($sql);


$conn->close();
?>

<br>
<section class="recepti-ranglista">
<h2 style="text-align: left; margin-left: 15%;">Top recepti</h2>
    <br><br>

    <?php
    if ($result->num_rows > 0) {
        echo '<table class="recepti-tabela-rang">';
        echo '<thead><tr><th>Recept Ime</th><th>Lajkova</th></tr></thead>';
        echo '<tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['recept_ime']) . '</td>';
            echo '<td>' . htmlspecialchars($row['lajk']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "Nema podataka o receptima.";
    }
    ?>
</section>


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