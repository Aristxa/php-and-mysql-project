<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të fshirë faturat.");
}

// Fshirja e faturës
if (isset($_GET["FatureID"])) {
    $fatureID = $_GET["FatureID"];
    $query = "DELETE FROM Faturat WHERE FatureID = ?";
    $params = array($fatureID);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Fatura u fshi me sukses!";
        header("Location: ../views/manage_invoices.php");
    } else {
        echo "❌ Gabim gjatë fshirjes së faturës.";
    }
} else {
    echo "❌ Fatura nuk u gjet!";
}
?>
