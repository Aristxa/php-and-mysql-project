<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të shtuar fatura.");
}

// Marr listën e pacientëve për dropdown
$query = "SELECT PacientID, Emri, Mbiemri FROM Pacientet";
$stmt = sqlsrv_query($conn, $query);

// Shtimi i faturës
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pacientID = $_POST["pacientID"];
    $shuma = $_POST["shuma"];
    $statusi = $_POST["statusi"];
    $dataFaturimit = $_POST["dataFaturimit"]; // Marrim datën nga inputi

    $query = "INSERT INTO Faturat (PacientID, DataFaturimit, Shuma, Statusi) VALUES (?, ?, ?, ?)";
    $params = array($pacientID, $dataFaturimit, $shuma, $statusi);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Fatura u shtua me sukses!";
        header("Location: manage_invoices.php");
        exit();
    } else {
        echo "❌ Gabim gjatë shtimit të faturës.";
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shto Faturë</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">➕ Shto Faturë të Re</h2>

    <form method="POST">
        <div class="mb-3">
            <label>Pacienti</label>
            <select name="pacientID" class="form-control" required>
                <option value="">-- Zgjidh Pacientin --</option>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <option value="<?php echo $row["PacientID"]; ?>">
                        <?php echo $row["Emri"] . " " . $row["Mbiemri"]; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Data e Faturës</label>
            <input type="date" name="dataFaturimit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Shuma (€)</label>
            <input type="number" name="shuma" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Statusi</label>
            <select name="statusi" class="form-control" required>
                <option value="E Papaguar">E Papaguar</option>
                <option value="E Paguar">E Paguar</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">📥 Shto Faturë</button>
        <a href="manage_invoices.php" class="btn btn-secondary">🔙 Kthehu</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

