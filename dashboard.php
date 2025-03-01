<?php
session_start();
require_once("config/database.php");


// Kontrollo nëse përdoruesi është i kyçur
if (!isset($_SESSION["userID"])) {
    header("Location: views/login.php");
    exit();
}

$username = $_SESSION["userID"];
$role = $_SESSION["role"]; // Merr rolin e përdoruesit

?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paneli Kryesor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center">Mirësevini në Panelin Kryesor</h2>
        <p class="text-center">Përdoruesi: <strong><?php echo $username; ?></strong></p>
        <p class="text-center">Roli: <strong><?php echo ucfirst($role); ?></strong></p>

        <hr>

        <div class="text-center">
            <a href="index.php" class="btn btn-secondary m-2">🏠 Kthehu te Faqja Kryesore</a>
            <a href="logout.php" class="btn btn-danger m-2">🚪 Dil</a>
        </div>

        <hr>

        <!-- Opsione për secilin rol -->
        <?php if ($role == "Admin"): ?>
            <h4 class="text-center">⚙️ Paneli i Administratorit</h4>
            <div class="d-flex flex-wrap justify-content-center">
                <a href="views/manage_users.php" class="btn btn-dark m-2">🔧 Menaxho Përdoruesit</a>
                <a href="views/manage_patients.php" class="btn btn-dark m-2">👨‍⚕️ Menaxho Pacientët</a>
                <a href="views/manage_doctors.php" class="btn btn-dark m-2">👩‍⚕️ Menaxho Mjekët</a>
                <a href="views/manage_invoices.php" class="btn btn-dark m-2">💰 Menaxho Faturat</a>
                <a href="views/reports.php" class="btn btn-dark m-2">📊 Shiko Raportet</a>  <!-- Butoni për raportet -->
            </div>

        <?php elseif ($role == "Doctor"): ?>
            <h4 class="text-center">🩺 Paneli i Mjekut</h4>
            <div class="d-flex flex-wrap justify-content-center">
                <a href="views/view_appointments.php" class="btn btn-primary m-2">📅 Shiko Takimet</a>
                <a href="views/patient_records.php" class="btn btn-info m-2">📄 Shiko Dosjet e Pacientëve</a>
                <a href="views/manage_medical_history.php" class="btn btn-info m-2">📄 Menaxho Dosjet Mjekësore</a>
            </div>

        <?php elseif ($role == "Patient"): ?>
            <h4 class="text-center">🏥 Paneli i Pacientit</h4>
            <div class="d-flex flex-wrap justify-content-center">
                <a href="views/appointment.php" class="btn btn-success m-2">📅 Rezervo Takim</a>
                <a href="views/view_appointments.php" class="btn btn-warning m-2">🔍 Shiko Takimet e Mia</a>
                <a href="views/my_invoices.php" class="btn btn-secondary m-2">💳 Shiko Faturat</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
