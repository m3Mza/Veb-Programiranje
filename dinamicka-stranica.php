<?php
session_start();

class Recept
{
    private $ime;
    private $opis;
    private $instrukcije;

    public function __construct($ime, $opis, $instrukcije)
    {
        $this->ime = $ime;
        $this->opis = $opis;
        $this->instrukcije = $instrukcije;
    }

    public function getIme()
    {
        return htmlspecialchars($this->ime);
    }

    public function getOpis()
    {
        return htmlspecialchars($this->opis);
    }

    public function getInstrukcije()
    {
        return htmlspecialchars($this->instrukcije);
    }

    public function prikaziRecept()
    {
        echo "<h1>Recept: " . $this->getIme() . "</h1>";
        echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
        echo "<tr><td>Ime recepta</td><td>" . $this->getIme() . "</td></tr>";
        echo "<tr><td>Opis</td><td>" . $this->getOpis() . "</td></tr>";
        echo "<tr><td>Instrukcije</td><td>" . $this->getInstrukcije() . "</td></tr>";
        echo "</table>";
    }

    public function prikaziZaStampu()
    {
        // Generišemo HTML sadržaj za štampanje
        $sadrzaj = "<html><head><title>Štampanje recepta</title></head><body>";
        $sadrzaj .= "<h1>Recept: " . $this->getIme() . "</h1>";
        $sadrzaj .= "<table border='1' style='width:100%; border-collapse: collapse;'>";
        $sadrzaj .= "<tr><td>Ime recepta</td><td>" . $this->getIme() . "</td></tr>";
        $sadrzaj .= "<tr><td>Opis</td><td>" . $this->getOpis() . "</td></tr>";
        $sadrzaj .= "<tr><td>Instrukcije</td><td>" . $this->getInstrukcije() . "</td></tr>";
        $sadrzaj .= "</table>";
        $sadrzaj .= "<br><button onclick='window.print()'>Štampaj</button>";
        $sadrzaj .= "</body></html>";

        return $sadrzaj;
    }
}

// Provera da li je `user_id` postavljen u URL-u
if (isset($_GET['user_id'])) {
    $_SESSION['user_id'] = $_GET['user_id'];
}

// Provera i setovanje parametara recepta iz URL-a
$ime = isset($_GET['ime']) ? $_GET['ime'] : '';
$opis = isset($_GET['opis']) ? $_GET['opis'] : '';
$instrukcije = isset($_GET['instrukcije']) ? $_GET['instrukcije'] : '';

// Kreiranje objekta Recept
$recept = new Recept($ime, $opis, $instrukcije);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recept->getIme(); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="forme.css">
    <link rel="icon" href="slike/ikonica.ico" type="image/x-icon">
</head>
<body>

<!-- SVG responsive curve -->
<div class="svg-kontejner">
    <svg viewBox="0 0 800 400" class="svg">
        <path id="curve" fill="#50c6d8" d="M 800 300 Q 400 350 0 300 L 0 0 L 800 0 L 800 300 Z"></path>
    </svg>
</div>

<header>
    <h1 id="recipeTitle"><?php echo $recept->getIme(); ?></h1>
</header>

<main>
    <!-- Dinamički prikaz recepta -->
    <section class="brzi-recept-sekcija">
        <div class="brzi-recept-detalji">
            <h2 id="recipeName"><?php echo $recept->getIme(); ?></h2>
            <h3 class="brzi-recept-opis"><?php echo $recept->getOpis(); ?></h3>
        </div>
        <div class="brzi-recept-instrukcije">
            <p><?php echo $recept->getInstrukcije(); ?></p>
        </div>
    </section>

    <!-- Dugme za štampu -->
    <div class="dugme-stampa">
        <button onclick="otvoriStranicuZaStampu()">Štampaj recept</button>
    </div>
</main>

<script>
function otvoriStranicuZaStampu() {
    // Kreiramo novi prozor
    var prozor = window.open("", "Štampanje recepta", "width=600,height=400");

    // Generišemo sadržaj za štampanje koristeći PHP klasu
    prozor.document.write(`<?php echo $recept->prikaziZaStampu(); ?>`);
    
    // Zatvaranje dokumenta da se primene promene
    prozor.document.close();
}
</script>

<script src="script.js"></script>
</body>
</html>
