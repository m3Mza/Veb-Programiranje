<?php
session_start();
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
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const saveButton = document.getElementById("saveRecipeButton");
    const saveStatusMessage = document.getElementById("saveStatusMessage");
    const recipeId = <?php echo json_encode($_GET['recept_id']); ?>;
    const recipeName = <?php echo json_encode($_GET['ime']); ?>;
    const userId = <?php echo json_encode(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null); ?>;

    saveButton.addEventListener("click", function() {
        if (!userId) {
            window.location.href = "login.html";
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "sacuvaj-recept.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status === 200) {
                saveStatusMessage.textContent = xhr.responseText;
            } else {
                saveStatusMessage.textContent = "Došlo je do greške prilikom čuvanja recepta.";
            }
        };

        xhr.send("user_id=" + encodeURIComponent(userId) + 
                 "&recipe_id=" + encodeURIComponent(recipeId) + 
                 "&recept_ime=" + encodeURIComponent(recipeName));
    });
});
</script>

<script src="script.js"></script>
</body>
</html>