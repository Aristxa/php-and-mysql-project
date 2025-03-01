<?php
global $conn;
session_start();
require_once("../config/database.php");
require_once("../controllers/adminController.php");

// Kontrollo nëse përdoruesi është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni qasje në këtë faqe.");
}

$totalPatients = getTotalPatients($conn);
$totalDoctors = getTotalDoctors($conn);
$unpaidInvoices = getUnpaidInvoices($conn);
$scheduledAppointments = getScheduledAppointments($conn);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paneli i Administratorit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">⚙️ Paneli i Administratorit</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3><?php echo $totalPatients; ?></h3>
                    <p>Pacientë</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?php echo $totalDoctors; ?></h3>
                    <p>Mjekë</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3><?php echo $unpaidInvoices; ?></h3>
                    <p>Fatura të Papaguara</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3><?php echo $scheduledAppointments; ?></h3>
                    <p>Takime të Planifikuara</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu në Panel</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
