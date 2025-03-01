<?php
global $conn;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../config/database.php");
require_once("../controllers/adminController.php");

// Kontrollo nëse është admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] != "Admin") {
    die("❌ Nuk keni leje për të parë këtë faqe.");
}

$stmt = getUsers($conn);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Përdoruesit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">👤 Menaxho Përdoruesit</h2>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Përdoruesi</th>
            <th>Roli</th>
            <th>Ndrysho Rolin</th>
            <th>Veprime</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row["UserID"]; ?></td>
                <td><?php echo $row["Username"]; ?></td>
                <td><?php echo $row["Role"]; ?></td>
                <td>
                    <form method="POST" action="../controllers/adminController.php">
                        <input type="hidden" name="userID" value="<?php echo $row["UserID"]; ?>">
                        <select name="newRole" class="form-control">
                            <option value="Admin">Admin</option>
                            <option value="Doctor">Mjek</option>
                            <option value="Patient">Pacient</option>
                        </select>
                        <button type="submit" name="updateRole" class="btn btn-primary btn-sm mt-2">💾 Ruaj</button>
                    </form>
                </td>
                <td>
                    <a href="../controllers/delete_user.php?UserID=<?php echo $row["UserID"]; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('A je i sigurt që dëshiron të fshish këtë përdorues?');">
                        🗑 Fshije
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </tbody>
    </table>
</div>
</body>
</html>
