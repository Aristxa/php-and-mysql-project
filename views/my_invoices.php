<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse përdoruesi është i kyçur dhe është pacient
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Patient") {
    die("❌ Nuk keni qasje në këtë faqe.");
}

$pacientID = $_SESSION["userID"];

// Marr listën e faturave për pacientin e kyçur
$query = "SELECT FatureID, DataFaturimit, Shuma, Statusi FROM Faturat WHERE PacientID = ?";
$params = array($pacientID);
$stmt = sqlsrv_query($conn, $query, $params);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faturat e Mia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center">💳 Faturat e Mia</h2>
        <p class="text-center">Këtu mund të shikoni faturat tuaja.</p>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Data e Faturës</th>
                <th>Shuma</th>
                <th>Statusi</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row["FatureID"]; ?></td>
                    <td><?php echo $row["DataFaturimit"]->format('Y-m-d'); ?></td>
                    <td><?php echo number_format($row["Shuma"], 2) . " €"; ?></td>
                    <td>
                        <?php if ($row["Statusi"] == "E Paguar"): ?>
                            <span class="badge bg-success">✅ E Paguar</span>
                        <?php else: ?>
                            <span class="badge bg-danger">❌ E Papaguar</span>
                        <?php endif; ?>
                    </td>
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
