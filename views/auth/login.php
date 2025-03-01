<?php
global $conn;
session_start();
include("../../config/database.php");
include("../../models/User.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);
    $loginData = $user->login($_POST['username'], $_POST['password']);

    if ($loginData) {
        $_SESSION["userID"] = $loginData["userID"];
        $_SESSION["role"] = $loginData["role"];
        header("Location: ../../dashboard.php");
    } else {
        echo "Kredencialet janë të pasakta!";
    }
}

?>
<form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Përdoruesi" required>
    <input type="password" name="password" placeholder="Fjalëkalimi" required>
    <button type="submit">Hyr</button>
</form>
