<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të përditësuar këtë pacient.");
}

// Marrim të dhënat nga forma
$pacientID = $_POST["PacientID"];
$emri = $_POST["Emri"];
$mbiemri = $_POST["Mbiemri"];
$datelindja = $_POST["Datelindja"];
$gjinia = $_POST["Gjinia"];
$email = $_POST["Email"];

// ✅ Përditëso të dhënat në databazë
$query = "UPDATE Pacientet SET Emri = ?, Mbiemri = ?, Datelindja = ?, Gjinia = ?, Email = ? WHERE PacientID = ?";
$params = array($emri, $mbiemri, $datelindja, $gjinia, $email, $pacientID);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt) {
    header("Location: ../views/manage_patients.php?success=1");
    exit();
} else {
    die("❌ Gabim gjatë përditësimit të pacientit.");
}
?>
