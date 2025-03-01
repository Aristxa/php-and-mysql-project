<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të parë këtë faqe.");
}

// Marr listën e mjekëve nga databaza
$query = "SELECT * FROM Mjeket";
$stmt = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Mjekët</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">👨‍⚕️ Menaxho Mjekët</h2>

    <!-- Mesazh suksesi -->
    <?php if (isset($_GET["success"])): ?>
        <div class="alert alert-success text-center">✅ Veprimi u krye me sukses!</div>
    <?php endif; ?>

    <div class="text-center mb-3">
        <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu në Panel</a>
        <a href="add_doctor.php" class="btn btn-success">➕ Shto Mjek</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Emri</th>
            <th>Mbiemri</th>
            <th>Specializimi</th>
            <th>Tel</th>
            <th>Email</th>
            <th>Veprime</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row["MjekID"]; ?></td>
                <td><?php echo $row["Emri"]; ?></td>
                <td><?php echo $row["Mbiemri"]; ?></td>
                <td><?php echo $row["Specializimi"]; ?></td>
                <td><?php echo $row["Tel"]; ?></td>
                <td><?php echo $row["Email"]; ?></td>
                <td>
                    <!-- Modifikimi -->
                    <a href="../views/edit_doctor.php?MjekID=<?php echo $row["MjekID"]; ?>" class="btn btn-warning btn-sm">✏ Modifiko</a>

                    <!-- Fshirja me konfirmim -->
                    <a href="../controllers/delete_doctor.php?MjekID=<?php echo $row["MjekID"]; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('A je i sigurt që dëshiron të fshish këtë mjek?');">🗑 Fshije</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
