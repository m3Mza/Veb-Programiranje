<?php
require_once 'klase/BazaPodataka.php'; // Uključivanje fajla sa klasom BazaPodataka

// Povezivanje sa bazom podataka
$baza = new BazaPodataka();

$imeRecepta = $_GET['ime'] ?? '';

if ($imeRecepta) {
    $conn = $baza->dohvatiKonekciju();
    $sql = "SELECT * FROM recepti WHERE ime = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $imeRecepta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $recept = $result->fetch_assoc();
    } else {
        die("Recept nije pronađen.");
    }
} else {
    die("Ime recepta nije prosleđeno.");
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recept['ime']); ?> - Štampaj recept</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
        }
        h1 {
            font-size: 24px;
        }
        h2 {
            font-size: 20px;
        }
        .instructions {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($recept['ime']); ?></h1>
    <h2>Opis:</h2>
    <p><?php echo htmlspecialchars($recept['opis']); ?></p>
    <h2>Instrukcije:</h2>
    <div class="instructions">
        <p><?php echo nl2br(htmlspecialchars($recept['instrukcije'])); ?></p>
    </div>
    <h2>Dijetalna restrikcija:</h2>
    <p><?php echo htmlspecialchars($recept['dijeta']); ?></p>
    <h2>Kategorija:</h2>
    <p><?php echo htmlspecialchars($recept['kategorija']); ?></p>
    <button onclick="window.print()">Štampaj ovaj recept</button>
</body>
</html>
