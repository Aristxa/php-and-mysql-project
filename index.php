<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxhimi i Spitalit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container text-center mt-5">
    <h1 class="mb-4">Sistemi i Menaxhimit të Spitalit</h1>
    <p class="lead">Mirësevini! Zgjidhni një nga opsionet më poshtë:</p>

    <!-- Butonat për vizitorët (nëse përdoruesi nuk është i kyçur) -->
    <?php if (!isset($_SESSION["userID"])): ?>
        <div class="mt-4">
            <a href="views/auth/login.php" class="btn btn-primary btn-lg m-2">Hyr</a>
            <a href="views/register.php" class="btn btn-success btn-lg m-2">Regjistrohu</a>
        </div>
    <?php endif; ?>

    <!-- Butonat për përdoruesit e kyçur -->
    <?php if (isset($_SESSION["userID"])): ?>
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-warning btn-lg m-2">Paneli i Përdoruesit</a>

            <!-- ✅ Butoni "Rezervo Takim" do të shfaqet vetëm për pacientët dhe adminët -->
            <?php if ($_SESSION["role"] == "Patient" || $_SESSION["role"] == "Admin"): ?>
                <a href="views/appointment.php" class="btn btn-info btn-lg m-2">Rezervo Takim</a>
            <?php endif; ?>

            <a href="views/view_appointments.php" class="btn btn-secondary btn-lg m-2">Shiko Takimet</a>

            <!-- Opsionet për adminin -->
            <?php if ($_SESSION["role"] == "Admin"): ?>
                <a href="views/manage_patients.php" class="btn btn-dark btn-lg m-2">Menaxho Pacientët</a>
                <a href="views/manage_doctors.php" class="btn btn-dark btn-lg m-2">Menaxho Mjekët</a>
                <a href="views/manage_invoices.php" class="btn btn-dark btn-lg m-2">Menaxho Faturat</a>
                <a href="views/manage_users.php" class="btn btn-dark btn-lg m-2">🔧 Menaxho Përdoruesit</a>
                <a href="views/reports.php" class="btn btn-dark btn-lg m-2">📊 Shiko Raportet</a>
            <?php endif; ?>

            <!-- Butoni për dalje nga sistemi -->
            <a href="logout.php" class="btn btn-danger btn-lg m-2">Dil</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

