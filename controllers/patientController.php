<?php
session_start();
require_once("../config/database.php");

// ✅ KONTROLLO NËSE ËSHTË PACIENT
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Patient") {
    die("❌ Nuk keni leje për këtë faqe.");
}

$pacientID = $_SESSION["userID"];

// ✅ SHFAQ FAKTURAT E PACIENTIT
function getPatientInvoices($conn, $pacientID) {
    $query = "SELECT FatureID, DataFaturimit, Shuma, Statusi FROM Faturat WHERE PacientID = ?";
    $params = array($pacientID);
    return sqlsrv_query($conn, $query, $params);
}

// ✅ SHFAQ TAKIMET E PACIENTIT
function getPatientAppointments($conn, $pacientID) {
    $query = "SELECT T.TakimID, M.Emri AS Mjeku, T.DataTakimit, T.OraTakimit, T.Statusi, T.Pershkrimi
              FROM Takimet T
              JOIN Mjeket M ON T.MjekID = M.MjekID
              WHERE T.PacientID = ?";
    $params = array($pacientID);
    return sqlsrv_query($conn, $query, $params);
}
?>
