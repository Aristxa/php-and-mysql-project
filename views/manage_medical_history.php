<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse përdoruesi është mjek
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Doctor") {
    die("❌ Nuk keni leje për të aksesuar këtë faqe.");
}

$mjekID = $_SESSION["userID"];

// ✅ Mjeku shikon të gjithë pacientët që ka trajtuar
$query = "SELECT H.HistID, P.Emri, P.Mbiemri, H.DataVizites, H.Diagnoza, H.Trajtimi, H.Medikamente 
          FROM HistorikuMjekesor H
          JOIN Pacientet P ON H.PacientID = P.PacientID
          WHERE H.MjekID = ?";
$params = array($mjekID);
$stmt = sqlsrv_query($conn, $query, $params);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Historikun Mjekësor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">📄 Menaxho Historikun Mjekësor</h2>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Pacienti</th>
            <th>Data Vizitës</th>
            <th>Diagnoza</th>
            <th>Trajtimi</th>
            <th>Medikamentet</th>
            <th>Veprime</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row["Emri"] . " " . $row["Mbiemri"]; ?></td>
                <td><?php echo $row["DataVizites"]->format('Y-m-d'); ?></td>
                <td><?php echo $row["Diagnoza"]; ?></td>
                <td><?php echo $row["Trajtimi"]; ?></td>
                <td><?php echo $row["Medikamente"]; ?></td>
                <td>
                    <a href="update_medical_history.php?id=<?php echo $row["HistID"]; ?>" class="btn btn-warning btn-sm">✏️ Ndrysho</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-3">
        <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu në Panel</a>
    </div>
</div>
</body>
</html>
