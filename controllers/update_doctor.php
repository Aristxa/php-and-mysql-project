<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të përditësuar këtë mjek.");
}

// Marrim të dhënat nga forma
$mjekID = $_POST["MjekID"];
$emri = $_POST["Emri"];
$mbiemri = $_POST["Mbiemri"];
$specializimi = $_POST["Specializimi"];
$tel = $_POST["Tel"];
$email = $_POST["Email"];

// ✅ Përditëso të dhënat në databazë
$query = "UPDATE Mjeket SET Emri = ?, Mbiemri = ?, Specializimi = ?, Tel = ?, Email = ? WHERE MjekID = ?";
$params = array($emri, $mbiemri, $specializimi, $tel, $email, $mjekID);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt) {
    header("Location: ../views/manage_doctors.php?success=1");
    exit();
} else {
    die("❌ Gabim gjatë përditësimit të mjekut.");
}
?>
