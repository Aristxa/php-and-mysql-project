<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse përdoruesi është mjek
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Doctor") {
    die("❌ Nuk keni leje për të aksesuar këtë faqe.");
}

if (!isset($_GET["id"])) {
    die("❌ Nuk ka ID të zgjedhur.");
}

$histID = $_GET["id"];

// ✅ Marrim të dhënat ekzistuese
$query = "SELECT * FROM HistorikuMjekesor WHERE HistID = ?";
$params = array($histID);
$stmt = sqlsrv_query($conn, $query, $params);
$record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$record) {
    die("❌ Nuk u gjet asnjë e dhënë.");
}

// ✅ Nëse forma është dërguar, përditëso të dhënat
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diagnoza = $_POST["diagnoza"];
    $trajtimi = $_POST["trajtimi"];
    $medikamente = $_POST["medikamente"];

    $updateQuery = "UPDATE HistorikuMjekesor SET Diagnoza = ?, Trajtimi = ?, Medikamente = ? WHERE HistID = ?";
    $paramsUpdate = array($diagnoza, $trajtimi, $medikamente, $histID);
    $stmtUpdate = sqlsrv_query($conn, $updateQuery, $paramsUpdate);

    if ($stmtUpdate) {
        header("Location: manage_medical_history.php");
        exit();
    } else {
        echo "❌ Gabim gjatë përditësimit!";
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Përditëso Dosjen Mjekësore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">✏️ Përditëso Dosjen Mjekësore</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Diagnoza</label>
            <input type="text" name="diagnoza" class="form-control" value="<?php echo $record["Diagnoza"]; ?>" required>
        </div>

        <div class="mb-3">
            <label>Trajtimi</label>
            <textarea name="trajtimi" class="form-control"><?php echo $record["Trajtimi"]; ?></textarea>
        </div>

        <div class="mb-3">
            <label>Medikamentet</label>
            <textarea name="medikamente" class="form-control"><?php echo $record["Medikamente"]; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">💾 Ruaj Ndryshimet</button>
        <a href="manage_medical_history.php" class="btn btn-secondary">🔙 Kthehu</a>
    </form>
</div>
</body>
</html>
