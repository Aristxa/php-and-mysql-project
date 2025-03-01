<?php
global $conn, $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse përdoruesi është i kyçur dhe ka qasje
if (!isset($_SESSION["userID"]) || ($_SESSION["role"] != "Doctor" && $_SESSION["role"] != "Admin")) {
    die("❌ Nuk keni akses në këtë faqe.");
}

$userID = $_SESSION["userID"];
$role = $_SESSION["role"];

// Nëse përdoruesi është mjek, shfaq vetëm pacientët e tij
if ($role == "Doctor") {
    $query = "SELECT H.HistID, P.Emri AS Pacienti, P.Mbiemri, H.DataVizites, H.Diagnoza, H.Trajtimi, H.Medikamente
              FROM HistorikuMjekesor H
              JOIN Pacientet P ON H.PacientID = P.PacientID
              WHERE H.MjekID = ?";
    $params = array($userID);
} else {
    // Admini sheh të gjitha dosjet mjekësore
    $query = "SELECT H.HistID, P.Emri AS Pacienti, P.Mbiemri, H.DataVizites, H.Diagnoza, H.Trajtimi, H.Medikamente
              FROM HistorikuMjekesor H
              JOIN Pacientet P ON H.PacientID = P.PacientID";
    $params = array();
}

$stmt = sqlsrv_query($conn, $query, $params);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dosjet e Pacientëve</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center">📂 Dosjet e Pacientëve</h2>
        <p class="text-center">Lista e të dhënave mjekësore:</p>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Pacienti</th>
                <th>Mbiemri</th>
                <th>Data e Vizitës</th>
                <th>Diagnoza</th>
                <th>Trajtimi</th>
                <th>Medikamentet</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row["HistID"]; ?></td>
                    <td><?php echo $row["Pacienti"]; ?></td>
                    <td><?php echo $row["Mbiemri"]; ?></td>
                    <td><?php echo $row["DataVizites"]->format('Y-m-d'); ?></td>
                    <td><?php echo $row["Diagnoza"]; ?></td>
                    <td><?php echo $row["Trajtimi"]; ?></td>
                    <td><?php echo $row["Medikamente"]; ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-center">
            <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu në Panel</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
