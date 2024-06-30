<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sajt_baza";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veza neuspesna: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recept_ime = $_POST['recept_ime'];

    $sql_select = "SELECT * FROM recenzije WHERE recept_ime = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("s", $recept_ime);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows > 0) {
        // update ako postoji recenzija
        $sql_update = "UPDATE recenzije SET lajk = lajk + 1 WHERE recept_ime = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $recept_ime);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // insert ako ne postoji
        $sql_insert = "INSERT INTO recenzije (recept_ime, lajk) VALUES (?, 1)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("s", $recept_ime);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    
    echo "<script>alert('Vaša recenzija je poslata!'); window.close();</script>";
    exit();
}


$conn->close();
?>
