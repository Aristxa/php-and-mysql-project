<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të fshirë përdoruesit.");
}

// Kontrollo nëse UserID është marrë nga URL
if (isset($_GET["UserID"])) {
    $userID = $_GET["UserID"];

    // Mos lejo adminin të fshijë veten
    if ($userID == $_SESSION["userID"]) {
        die("❌ Nuk mund të fshini llogarinë tuaj!");
    }

    // Fshirja e përdoruesit nga databaza
    $query = "DELETE FROM Users WHERE UserID = ?";
    $params = array($userID);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Përdoruesi u fshi me sukses!";
        header("Location: ../views/manage_users.php");
        exit();
    } else {
        die("❌ Gabim gjatë fshirjes së përdoruesit.");
    }
} else {
    die("❌ ID e përdoruesit nuk u gjet!");
}
?>
