<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të parë këtë faqe.");
}

// Kontrollo nëse kemi një ID mjeku të dhënë në URL
if (!isset($_GET["MjekID"])) {
    die("❌ ID e mjekut nuk është dhënë.");
}

$mjekID = $_GET["MjekID"];

// ✅ Marrim të dhënat e mjekut nga databaza
$query = "SELECT * FROM Mjeket WHERE MjekID = ?";
$params = array($mjekID);
$stmt = sqlsrv_query($conn, $query, $params);
$mjek = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$mjek) {
    die("❌ Ky mjek nuk ekziston!");
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifiko Mjekun</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">✏ Modifiko Mjekun</h2>

    <form action="../controllers/update_doctor.php" method="POST">
        <input type="hidden" name="MjekID" value="<?php echo $mjekID; ?>">

        <div class="mb-3">
            <label for="Emri" class="form-label">Emri:</label>
            <input type="text" class="form-control" id="Emri" name="Emri" value="<?php echo $mjek["Emri"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="Mbiemri" class="form-label">Mbiemri:</label>
            <input type="text" class="form-control" id="Mbiemri" name="Mbiemri" value="<?php echo $mjek["Mbiemri"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="Specializimi" class="form-label">Specializimi:</label>
            <input type="text" class="form-control" id="Specializimi" name="Specializimi" value="<?php echo $mjek["Specializimi"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="Tel" class="form-label">Telefoni:</label>
            <input type="text" class="form-control" id="Tel" name="Tel" value="<?php echo $mjek["Tel"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="Email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $mjek["Email"]; ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">💾 Ruaj Ndryshimet</button>
            <a href="manage_doctors.php" class="btn btn-secondary">🔙 Kthehu</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
