<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të parë këtë faqe.");
}

// Kontrollo nëse kemi një ID pacienti të dhënë në URL
if (!isset($_GET["PacientID"])) {
    die("❌ ID e pacientit nuk është dhënë.");
}

$pacientID = $_GET["PacientID"];

// ✅ Marrim të dhënat e pacientit nga databaza
$query = "SELECT * FROM Pacientet WHERE PacientID = ?";
$params = array($pacientID);
$stmt = sqlsrv_query($conn, $query, $params);
$pacient = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$pacient) {
    die("❌ Ky pacient nuk ekziston!");
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifiko Pacientin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">✏ Modifiko Pacientin</h2>

    <form action="../controllers/update_patient.php" method="POST">
        <input type="hidden" name="PacientID" value="<?php echo $pacientID; ?>">

        <div class="mb-3">
            <label for="Emri" class="form-label">Emri:</label>
            <input type="text" class="form-control" id="Emri" name="Emri" value="<?php echo $pacient["Emri"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="Mbiemri" class="form-label">Mbiemri:</label>
            <input type="text" class="form-control" id="Mbiemri" name="Mbiemri" value="<?php echo $pacient["Mbiemri"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="Datelindja" class="form-label">Datëlindja:</label>
            <input type="date" class="form-control" id="Datelindja" name="Datelindja" value="<?php echo $pacient["Datelindja"]->format('Y-m-d'); ?>" required>
        </div>

        <div class="mb-3">
            <label for="Gjinia" class="form-label">Gjinia:</label>
            <select class="form-control" id="Gjinia" name="Gjinia" required>
                <option value="M" <?php echo ($pacient["Gjinia"] == 'M') ? 'selected' : ''; ?>>Mashkull</option>
                <option value="F" <?php echo ($pacient["Gjinia"] == 'F') ? 'selected' : ''; ?>>Femër</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="Email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $pacient["Email"]; ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">💾 Ruaj Ndryshimet</button>
            <a href="manage_patients.php" class="btn btn-secondary">🔙 Kthehu</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
