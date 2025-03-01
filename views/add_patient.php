<<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të shtuar pacientë.");
}

// Shtimi i pacientit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emri = $_POST["emri"];
    $mbiemri = $_POST["mbiemri"];
    $datelindja = $_POST["datelindja"];
    $gjinia = $_POST["gjinia"];
    $adresa = $_POST["adresa"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $nrSigurimi = $_POST["nr_sigurimi"];

    // Sigurohemi që të dhënat nuk janë bosh
    if (empty($emri) || empty($mbiemri) || empty($datelindja) || empty($gjinia) || empty($email) || empty($nrSigurimi)) {
        die("❌ Të gjitha fushat janë të detyrueshme!");
    }

    // Kontrollo nëse emaili ose numri i sigurimit ekziston tashmë në databazë
    $checkQuery = "SELECT COUNT(*) AS num FROM Pacientet WHERE Email = ? OR NrSigurimi = ?";
    $checkParams = array($email, $nrSigurimi);
    $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);
    $checkRow = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);

    if ($checkRow['num'] > 0) {
        die("❌ Ky email ose numri i sigurimit tashmë ekziston!");
    }

    // Shto pacientin në databazë
    $query = "INSERT INTO Pacientet (Emri, Mbiemri, Datelindja, Gjinia, Adresa, Tel, Email, NrSigurimi) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($emri, $mbiemri, $datelindja, $gjinia, $adresa, $tel, $email, $nrSigurimi);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Pacienti u shtua me sukses!";
        header("Location: manage_patients.php");
        exit();
    } else {
        echo "❌ Numri i Sigurimit duhet të ketë saktësisht 10 shifra!";
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shto Pacient</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">➕ Shto Pacient të Ri</h2>

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
            <label>Datëlindja</label>
            <input type="date" name="datelindja" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Gjinia</label>
            <select name="gjinia" class="form-control" required>
                <option value="M">Mashkull</option>
                <option value="F">Femër</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Adresa</label>
            <input type="text" name="adresa" class="form-control">
        </div>

        <div class="mb-3">
            <label>Numri i Telefonit</label>
            <input type="text" name="tel" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Numri i Sigurimit</label>
            <input type="text" name="nr_sigurimi" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">📥 Shto Pacient</button>
        <a href="manage_patients.php" class="btn btn-secondary">🔙 Kthehu</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

