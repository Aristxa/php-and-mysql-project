<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të parë këtë faqe.");
}

// Marr listën e faturave nga databaza
$query = "SELECT F.FatureID, P.Emri AS Pacienti, P.Mbiemri, F.DataFaturimit, F.Shuma, F.Statusi
          FROM Faturat F
          JOIN Pacientet P ON F.PacientID = P.PacientID";
$stmt = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Faturat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">💰 Menaxho Faturat</h2>

    <div class="text-center mb-3">
        <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu në Panel</a>
        <a href="add_invoice.php" class="btn btn-success">➕ Shto Faturë</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Pacienti</th>
            <th>Data</th>
            <th>Shuma</th>
            <th>Statusi</th>
            <th>Veprime</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row["FatureID"]; ?></td>
                <td><?php echo $row["Pacienti"] . " " . $row["Mbiemri"]; ?></td>
                <td><?php echo $row["DataFaturimit"]->format('Y-m-d'); ?></td>
                <td><?php echo number_format($row["Shuma"], 2) . " €"; ?></td>
                <td><?php echo $row["Statusi"]; ?></td>
                <td>
                    <a href="edit_invoice.php?FatureID=<?php echo $row["FatureID"]; ?>" class="btn btn-warning btn-sm">✏ Modifiko</a>
                    <a href="../controllers/delete_invoice.php?FatureID=<?php echo $row["FatureID"]; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('A je i sigurt që dëshiron të fshish këtë faturë?');">🗑 Fshije</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
