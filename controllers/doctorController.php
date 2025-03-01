<?php
global $conn;
session_start();
require_once("../config/database.php");

// ✅ KONTROLLO NËSE ËSHTË MJEK
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Doctor") {
    die("❌ Nuk keni leje për këtë faqe.");
}

$doctorID = $_SESSION["userID"];

// ✅ SHFAQ TAKIMET E MJEKUT
function getDoctorAppointments($conn, $doctorID) {
    $query = "SELECT T.TakimID, P.Emri AS Pacienti, P.Mbiemri, T.DataTakimit, T.OraTakimit, T.Statusi, T.Pershkrimi
              FROM Takimet T
              JOIN Pacientet P ON T.PacientID = P.PacientID
              WHERE T.MjekID = ?";
    $params = array($doctorID);
    return sqlsrv_query($conn, $query, $params);
}

// ✅ PËRDITËSIMI I DOSJES MJESËKORE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateHistory"])) {
    $histID = $_POST["histID"];
    $diagnoza = $_POST["diagnoza"];
    $trajtimi = $_POST["trajtimi"];
    $medikamente = $_POST["medikamente"];

    $query = "UPDATE HistorikuMjekesor SET Diagnoza = ?, Trajtimi = ?, Medikamente = ? WHERE HistID = ?";
    $params = array($diagnoza, $trajtimi, $medikamente, $histID);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "✅ Dosja mjekësore u përditësua!";
        header("Location: ../views/doctor_dashboard.php");
        exit();
    } else {
        echo "❌ Gabim gjatë përditësimit.";
    }
}
?>
