<?php
global $conn;
session_start();
require_once("../config/database.php");

// Kontrollo nëse përdoruesi është i kyçur
if (!isset($_SESSION["userID"])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION["userID"];
$role = $_SESSION["role"];

// Marr listën e mjekëve
$queryMjeket = "SELECT MjekID, Emri, Mbiemri FROM Mjeket";
$stmtMjeket = sqlsrv_query($conn, $queryMjeket);

// Nëse përdoruesi është admin, ai duhet të zgjedhë pacientin
$pacientDropdown = "";
if ($role == "Admin") {
    $queryPacientet = "SELECT PacientID, Emri, Mbiemri FROM Pacientet";
    $stmtPacientet = sqlsrv_query($conn, $queryPacientet);
    $pacientDropdown = '<label>Pacienti</label><select name="pacientID" class="form-control" required>';
    while ($row = sqlsrv_fetch_array($stmtPacientet, SQLSRV_FETCH_ASSOC)) {
        $pacientDropdown .= '<option value="'.$row["PacientID"].'">'.$row["Emri"].' '.$row["Mbiemri"].'</option>';
    }
    $pacientDropdown .= '</select>';
}

// Marr PacientID për pacientët e kyçur
if ($role == "Patient") {
    $pacientID = $userID;
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervo Takim</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">📅 Rezervo Takim</h2>

    <form method="POST" action="../controllers/appointmentController.php">
        <?php if ($role == "Admin") echo $pacientDropdown; ?>

        <div class="mb-3">
            <label>Mjeku</label>
            <select name="mjekID" class="form-control" required>
                <?php while ($row = sqlsrv_fetch_array($stmtMjeket, SQLSRV_FETCH_ASSOC)): ?>
                    <option value="<?php echo $row["MjekID"]; ?>">
                        <?php echo $row["Emri"]." ".$row["Mbiemri"]; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Data e Takimit</label>
            <input type="date" name="dataTakimit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ora e Takimit</label>
            <input type="time" name="oraTakimit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Përshkrimi</label>
            <textarea name="pershkrimi" class="form-control" placeholder="Shkruaj detajet e takimit"></textarea>
        </div>

        <button type="submit" class="btn btn-success">📥 Rezervo</button>
        <a href="../dashboard.php" class="btn btn-secondary">🔙 Kthehu</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

