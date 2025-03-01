<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse kërkesa është POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = $_POST["role"]; // Admin, Doctor, Patient

    // Kontrollo nëse fusha është bosh
    if (empty($username) || empty($password) || empty($role)) {
        die("❌ Të gjitha fushat janë të detyrueshme!");
    }

    // Kontrollo nëse përdoruesi ekziston tashmë
    $query = "SELECT COUNT(*) AS num FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row['num'] > 0) {
        die("❌ Ky përdorues tashmë ekziston!");
    }

    // Hash fjalëkalimin për siguri
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Ruaj të dhënat në databazë
    $sql = "INSERT INTO Users (Username, Password, Role) VALUES (?, ?, ?)";
    $params = array($username, $hashedPassword, $role);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "✅ Përdoruesi u regjistrua me sukses!";
    } else {
        echo "❌ Gabim gjatë regjistrimit!";
    }
}

?>

