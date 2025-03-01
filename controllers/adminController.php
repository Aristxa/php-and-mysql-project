<?php
global $conn;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../config/database.php");

// Kontrollo nëse përdoruesi është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të aksesuar këtë faqe.");
}

// ✅ Funksion për të marrë numrin total të pacientëve
function getTotalPatients($conn) {
    $query = "SELECT COUNT(*) AS total FROM Pacientet";
    $stmt = sqlsrv_query($conn, $query);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['total'];
}

// ✅ Funksion për të marrë numrin total të mjekëve
function getTotalDoctors($conn) {
    $query = "SELECT COUNT(*) AS total FROM Mjeket";
    $stmt = sqlsrv_query($conn, $query);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['total'];
}

// ✅ Funksion për të marrë numrin total të faturave të papaguara
function getUnpaidInvoices($conn) {
    $query = "SELECT COUNT(*) AS total FROM Faturat WHERE Statusi = 'E Papaguar'";
    $stmt = sqlsrv_query($conn, $query);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['total'];
}

// ✅ Funksion për të marrë numrin total të takimeve të planifikuara
function getScheduledAppointments($conn) {
    $query = "SELECT COUNT(*) AS total FROM Takimet WHERE Statusi = 'I Planifikuar'";
    $stmt = sqlsrv_query($conn, $query);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['total'];
}

// ✅ Funksion për të marrë listën e përdoruesve
function getUsers($conn) {
    $query = "SELECT UserID, Username, Role FROM Users";
    return sqlsrv_query($conn, $query);
}

// ✅ Funksion për të ndryshuar rolin e një përdoruesi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateRole"])) {
    $userID = $_POST["userID"];
    $newRole = $_POST["newRole"];

    $query = "UPDATE Users SET Role = ? WHERE UserID = ?";
    $params = array($newRole, $userID);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Roli u përditësua me sukses!";
        header("Location: ../views/manage_users.php");
        exit();
    } else {
        echo "❌ Gabim gjatë përditësimit të rolit.";
    }
}
?>
