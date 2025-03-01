<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të aksesuar këtë faqe.");
}

// Kontrollo nëse kemi një ID të faturës
if (!isset($_GET["FatureID"])) {
    die("❌ ID e faturës nuk është dhënë.");
}

$fatureID = $_GET["FatureID"];

// ✅ Marrim të dhënat aktuale të faturës nga databaza
$query = "SELECT * FROM Faturat WHERE FatureID = ?";
$params = array($fatureID);
$stmt = sqlsrv_query($conn, $query, $params);
$fatura = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$fatura) {
    die("❌ Fatura nuk u gjet!");
}

// ✅ Kur admini klikon "Ruaj Ndryshimet", përditësojmë statusin në databazë
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $statusi = $_POST["statusi"];

    $updateQuery = "UPDATE Faturat SET Statusi = ? WHERE FatureID = ?";
    $paramsUpdate = array($statusi, $fatureID);
    $stmtUpdate = sqlsrv_query($conn, $updateQuery, $paramsUpdate);

    if ($stmtUpdate) {
        header("Location: manage_invoices.php?success=1");
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
    <title>Ndrysho Statusin e Faturës</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">✏ Ndrysho Statusin e Faturës</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Pacienti</label>
            <input type="text" class="form-control" value="<?php echo $fatura["PacientID"]; ?>" disabled>
        </div>

        <div class="mb-3">
            <label>Shuma (€)</label>
            <input type="text" class="form-control" value="<?php echo number_format($fatura["Shuma"], 2); ?> €" disabled>
        </div>

        <div class="mb-3">
            <label>Statusi i Faturës</label>
            <select name="statusi" class="form-control">
                <option value="E Papaguar" <?php echo ($fatura["Statusi"] == "E Papaguar") ? "selected" : ""; ?>>E Papaguar</option>
                <option value="E Paguar" <?php echo ($fatura["Statusi"] == "E Paguar") ? "selected" : ""; ?>>E Paguar</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 Ruaj Ndryshimet</button>
        <a href="manage_invoices.php" class="btn btn-secondary">🔙 Kthehu</a>
    </form>
</div>
</body>
</html>
