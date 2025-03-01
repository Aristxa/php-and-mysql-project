<?php
global $conn;
session_start();
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mjekID = $_POST['mjekID'];
    $dataTakimit = $_POST['dataTakimit'];
    $oraTakimit = $_POST['oraTakimit'];
    $pershkrimi = $_POST['pershkrimi'];

    // Nëse është admin, përdor pacientin e zgjedhur nga forma
    if ($_SESSION["role"] == "Admin") {
        $pacientID = $_POST['pacientID'];
    } else {
        $pacientID = $_SESSION["userID"];
    }

    $sql = "{CALL ShtoTakim(?, ?, ?, ?, ?, ?)}";
    $params = array($pacientID, $mjekID, $dataTakimit, $oraTakimit, 'I Planifikuar', $pershkrimi);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "✅ Takimi u rezervua me sukses!";
        header("Location: ../views/view_appointments.php");
        exit();
    } else {
        echo "❌ Gabim gjatë rezervimit të takimit.";
        print_r(sqlsrv_errors()); // Shfaq gabimet nëse ka
    }
}
?>
