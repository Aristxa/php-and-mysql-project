<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse përdoruesi është i kyçur
if (!isset($_SESSION["userID"])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION["userID"];
$role = $_SESSION["role"];

// Marrja e të dhënave në varësi të rolit
if ($role == "Patient") {
    // Pacienti sheh vetëm takimet e tij
    $query = "SELECT T.TakimID, M.Emri AS Mjeku, T.DataTakimit, T.OraTakimit, T.Statusi, T.Pershkrimi
              FROM Takimet T
              JOIN Mjeket M ON T.MjekID = M.MjekID
              WHERE T.PacientID = ?";
    $params = array($userID);
} elseif ($role == "Doctor") {
    // Mjeku sheh të gjithë pacientët e tij
    $query = "SELECT T.TakimID, P.Emri AS Pacienti, P.Mbiemri, T.DataTakimit, T.OraTakimit, T.Statusi, T.Pershkrimi
              FROM Takimet T
              JOIN Pacientet P ON T.PacientID = P.PacientID
              WHERE T.MjekID = ?";
    $params = array($userID);
} elseif ($role == "Admin") {
    // Admini sheh të gjitha takimet
    $query = "SELECT T.TakimID, P.Emri AS Pacienti, P.Mbiemri, M.Emri AS Mjeku, M.Mbiemri AS MbiemriMjekut, 
                     T.DataTakimit, T.OraTakimit, T.Statusi, T.Pershkrimi
              FROM Takimet T
              JOIN Pacientet P ON T.PacientID = P.PacientID
              JOIN Mjeket M ON T.MjekID = M.MjekID";
    $params = array();
} else {
    die("❌ Nuk keni qasje në këtë faqe.");
}

$stmt = sqlsrv_query($conn, $query, $params);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takimet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center">📅 Takimet</h2>
        <p class="text-center">Lista e takimeve:</p>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <?php if ($role == "Admin" || $role == "Doctor"): ?>
                    <th>Pacienti</th>
                    <th>Mbiemri</th>
                <?php endif; ?>
                <?php if ($role == "Admin" || $role == "Patient"): ?>
                    <th>Mjeku</th>
                    <th>Mbiemri i Mjekut</th>
                <?php endif; ?>
                <th>Data</th>
                <th>Ora</th>
                <th>Statusi</th>
                <th>Përshkrimi</th>
            </tr>
            </thead>
            <tbody>

            <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row["TakimID"]; ?></td>
                    <?php if ($role == "Admin" || $role == "Doctor"): ?>
                        <td><?php echo $row["Pacienti"]; ?></td>
                        <td><?php echo $row["Mbiemri"]; ?></td>
                    <?php endif; ?>
                    <?php if ($role == "Admin" || $role == "Patient"): ?>
                        <td><?php echo $row["Mjeku"]; ?></td>
                        <td><?php echo $row["MbiemriMjekut"]; ?></td>
                    <?php endif; ?>
                    <td><?php echo $row["DataTakimit"]->format('Y-m-d'); ?></td>
                    <td><?php echo $row["OraTakimit"]->format('H:i'); ?></td>
                    <td><?php echo $row["Statusi"]; ?></td>
                    <td><?php echo $row["Pershkrimi"]; ?></td>
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
