<?php
global $conn;
session_start();
require_once("../config/database.php");

// ✅ HYRJA NË SISTEM
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $query = "SELECT UserID, Password, Role FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt && sqlsrv_fetch($stmt)) {
        $userID = sqlsrv_get_field($stmt, 0);
        $hashedPassword = sqlsrv_get_field($stmt, 1);
        $role = sqlsrv_get_field($stmt, 2);

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["userID"] = $userID;
            $_SESSION["role"] = $role;
            header("Location: ../dashboard.php");
            exit();
        } else {
            echo "❌ Fjalëkalimi është i gabuar!";
        }
    } else {
        echo "❌ Përdoruesi nuk ekziston!";
    }
}

// ✅ REGJISTRIMI I PËRDORUESIT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = $_POST["role"];

    if (empty($username) || empty($password) || empty($role)) {
        die("❌ Të gjitha fushat janë të detyrueshme!");
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO Users (Username, Password, Role) VALUES (?, ?, ?)";
    $params = array($username, $hashedPassword, $role);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        $_SESSION["userID"] = sqlsrv_fetch_field($stmt, 0);
        $_SESSION["role"] = $role;
        header("Location: ../dashboard.php");
        exit();
    } else {
        echo "❌ Gabim gjatë regjistrimit!";
    }
}

// ✅ DALJA NGA SISTEMI
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>
