<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse përdoruesi është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të parë këtë raport.");
}

// Marrim raportet nga databaza
$query = "SELECT * FROM Raporti_Admin";
$stmt = sqlsrv_query($conn, $query);
$raporti = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$numriPacientet = $raporti["NumriPacientet"];
$takimeTePlanifikuara = $raporti["TakimeTePlanifikuara"];
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raportet e Spitalit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">📊 Raportet e Spitalit</h2>
    <p class="text-center">Informacion mbi pacientët dhe takimet</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3><?php echo $numriPacientet; ?></h3>
                    <p>Pacientë të regjistruar</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?php echo $takimeTePlanifikuara; ?></h3>
                    <p>Takime të planifikuara</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu në Panel</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
