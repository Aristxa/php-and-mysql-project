<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të fshirë mjekët.");
}

// Fshirja e mjekut
if (isset($_GET["MjekID"])) {
    $mjekID = $_GET["MjekID"];
    $query = "DELETE FROM Mjeket WHERE MjekID = ?";
    $params = array($mjekID);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Mjeku u fshi me sukses!";
        header("Location: ../views/manage_doctors.php");
    } else {
        echo "❌ Gabim gjatë fshirjes së mjekut.";
    }
} else {
    echo "❌ Mjeku nuk u gjet!";
}
?>
