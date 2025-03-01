<?php
global $conn;
session_start();
require_once("../config/database.php");
if (isset($_GET["success"])) {
    echo '<div class="alert alert-success text-center">✅ Pacienti u përditësua me sukses!</div>';
}
// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të parë këtë faqe.");
}

// Marr listën e pacientëve nga databaza
$query = "SELECT * FROM Pacientet";
$stmt = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Pacientët</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">🏥 Menaxho Pacientët</h2>

    <div class="text-center mb-3">
        <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu në Panel</a>
        <a href="add_patient.php" class="btn btn-success">➕ Shto Pacient</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Emri</th>
            <th>Mbiemri</th>
            <th>Datëlindja</th>
            <th>Gjinia</th>
            <th>Email</th>
            <th>Veprime</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row["PacientID"]; ?></td>
                <td><?php echo $row["Emri"]; ?></td>
                <td><?php echo $row["Mbiemri"]; ?></td>
                <td><?php echo $row["Datelindja"]->format('Y-m-d'); ?></td>
                <td><?php echo $row["Gjinia"]; ?></td>
                <td><?php echo $row["Email"]; ?></td>
                <td>
                    <a href="edit_patient.php?PacientID=<?php echo $row["PacientID"]; ?>" class="btn btn-warning btn-sm">✏ Modifiko</a>
                    <a href="../controllers/delete_patient.php?PacientID=<?php echo $row["PacientID"]; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('A je i sigurt që dëshiron të fshish këtë pacient?');">🗑 Fshije</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
