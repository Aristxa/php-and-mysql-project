<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të shtuar mjekë.");
}

// Shtimi i mjekut
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emri = $_POST["emri"];
    $mbiemri = $_POST["mbiemri"];
    $specializimi = $_POST["specializimi"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];

    $query = "INSERT INTO Mjeket (Emri, Mbiemri, Specializimi,Tel, Email) VALUES (?, ?, ?,?, ?)";
    $params = array($emri, $mbiemri, $specializimi,$tel, $email);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Mjeku u shtua me sukses!";
        header("Location: manage_doctors.php");
    } else {
        echo "❌ Gabim gjatë shtimit të mjekut.";
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shto Mjek</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">➕ Shto Mjek të Ri</h2>

    <form method="POST">
        <div class="mb-3">
            <label>Emri</label>
            <input type="text" name="emri" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mbiemri</label>
            <input type="text" name="mbiemri" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Specializimi</label>
            <input type="text" name="specializimi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tel</label>
            <input type="text" name="Tel" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-success">📥 Shto Mjek</button>
        <a href="manage_doctors.php" class="btn btn-secondary">🔙 Kthehu</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
