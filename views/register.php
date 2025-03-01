<h2>Regjistrohu</h2>

<form method="POST" action="../controllers/registerController.php">
    <label>Përdoruesi:</label>
    <input type="text" name="username" required>

    <label>Fjalëkalimi:</label>
    <input type="password" name="password" required>

    <label>Roli:</label>
    <select name="role" required>
        <option value="Admin">Administrator</option>
        <option value="Doctor">Mjek</option>
        <option value="Patient">Pacient</option>
    </select>

    <button type="submit">Regjistrohu</button>
</form>
