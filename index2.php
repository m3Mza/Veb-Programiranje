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
    <title>Mirkova Kujna | Pocetna</title>
    <link rel="stylesheet" href="styles.css">
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
    <h1>Dobrodošli, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
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

  Slajder

  ========================================================================================================= -->

       <div class="slajder">
        <div class="slajdovi">
          <div class="slajd">
            <div class="slajd-sadrzaj">
              <a href="https://example.com"><img src="slike/dorucakRecepti.webp" alt="Tajlandski omlet sa ljutim čili papričicama."></a>
              <div class="opis-blok">
                <h2>Tajlandski Omlet</h2>
                <p>sa ljutim čili papričicama.</p>
              </div>
            </div>
          </div>
          <div class="slajd">
            <div class="slajd-sadrzaj">
              <a href="https://example.com"><img src="slike/mesoRecepti.webp" alt="Teleći odrezak sa karamelizovanim povrćem."></a>
              <div class="opis-blok">
                <h2>Teleći odrezak</h2>
                <p>sa karamelizovanim povrćem.</p>
              </div>
            </div>
          </div>
          <div class="slajd">
            <div class="slajd-sadrzaj">
              <a href="https://example.com"><img src="slike/pastaRecepti.webp" alt="Pasta sa škampima u sosu od paradajza"></a>
              <div class="opis-blok">
                <h2>Pasta sa škampima</h2>
                <p>u marinara sosu.</p>
              </div>
            </div>
          </div>
          <div class="slajd">
            <div class="slajd-sadrzaj">
              <a href="https://example.com"><img src="slike/pita.webp" alt="Američka pita sa šumskim voćem."></a>
              <div class="opis-blok">
                <h2>Američka pita</h2>
                <p>sa šljivama.</p>
              </div>
            </div>
          </div>
          <!-- Dodaj vise slajdova po potrebi -->
        </div>
        <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next" onclick="moveSlide(1)">&#10095;</button>
      </div>

      <br><br><br><br><br>
      <hr class="separator">
      <br><br>
      
      <!-- ====================================================================================================

  Iskustva

  ========================================================================================================= -->


      <h2 align="left">Iskustva Autora</h2>
      <section class="tri-kolumne">
        <div class="iskustva-kolumna">
            <img src="slike/banatbar.jpeg" alt="Banat Bar">
            <h3>BanatBar, Kikinda</h3>
            <p>2  0  2  1</p>
        </div>
        <div class="iskustva-kolumna">
            <img src="slike/monza.jpeg" alt="Monza" >
            <h3>Monza, Kikinda</h3>
            <p>2  0  2  2</p>
        </div>
        <div class="iskustva-kolumna">
            <img src="slike/viagra.jpeg" alt="Viagra">
            <h3>Viagra R, Promajna</h3>
            <p>2  0  2  3</p>
        </div>
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