<?php
session_start();
if (isset($_GET['user_id'])) {
    $_SESSION['user_id'] = $_GET['user_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_GET['ime']) ? htmlspecialchars($_GET['ime']) : ''; ?></title>
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
    <h1 id="recipeTitle"><?php echo isset($_GET['ime']) ? htmlspecialchars($_GET['ime']) : ''; ?></h1>
</header>

<main>
    <!-- Dinamički prikaz recepta -->
    <section class="brzi-recept-sekcija">
        <div class="brzi-recept-detalji">
            <h2 id="recipeName"><?php echo isset($_GET['ime']) ? htmlspecialchars($_GET['ime']) : ''; ?></h2>
            <h3 class="brzi-recept-opis"><?php echo isset($_GET['opis']) ? htmlspecialchars($_GET['opis']) : ''; ?></h3>
        </div>
        <div class="brzi-recept-instrukcije">
            <p><?php echo isset($_GET['instrukcije']) ? htmlspecialchars($_GET['instrukcije']) : ''; ?></p>
        </div>
    </section>

    <form action="recenzija.php" method="post">
        <input type="hidden" name="recept_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
        <input type="hidden" name="recept_ime" value="<?php echo isset($_GET['ime']) ? htmlspecialchars($_GET['ime']) : ''; ?>">

        <div class="svidja-se-recept">
            <p>Sviđa vam se ovaj recept?</p>
            <br>
        </div>

        <div class="posalji">
            <button type="submit" name="svidja" value="da">Da! Klik na dugme šalje lajk!</button>
        </div>
    </form>
</main>




<script src="script.js"></script>
</body>
</html>