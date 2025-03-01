<?php
global $conn;
session_start();
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT UserID, Password, Role FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt && sqlsrv_fetch($stmt)) {
        $userID = sqlsrv_get_field($stmt, 0);
        $hashedPassword = sqlsrv_get_field($stmt, 1);
        $role = sqlsrv_get_field($stmt, 2);

        // Kontrollojmë fjalëkalimin
        if (password_verify($password, $hashedPassword)) {
            $_SESSION["userID"] = $userID;
            $_SESSION["role"] = $role;

            // ✅ Ridrejto përdoruesin në dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            echo "❌ Fjalëkalimi është i gabuar!";
        }
    } else {
        echo "❌ Përdoruesi nuk ekziston!";
    }
}
?>
