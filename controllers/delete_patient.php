<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të fshirë pacientët.");
}

// Kontrollo nëse ID e pacientit është e dhënë në URL
if (!isset($_GET["PacientID"])) {
    die("❌ ID e pacientit mungon!");
}

$pacientID = $_GET["PacientID"];

// Fshij të dhënat e pacientit nga tabelat e lidhura para fshirjes në tabelën kryesore
$queries = [
    "DELETE FROM Faturat WHERE PacientID = ?",
    "DELETE FROM HistorikuMjekesor WHERE PacientID = ?",
    "DELETE FROM Takimet WHERE PacientID = ?",
    "DELETE FROM TakimetPartition WHERE PacientID = ?",
    "DELETE FROM Pacientet WHERE PacientID = ?"
];

foreach ($queries as $query) {
    $stmt = sqlsrv_query($conn, $query, array($pacientID));

    if (!$stmt) {
        die("❌ Gabim gjatë fshirjes: " . print_r(sqlsrv_errors(), true));
    }
}

// Nëse fshirja u krye me sukses
header("Location: ../views/manage_patients.php?success=1");
exit();
?>