<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM Users WHERE Username = ?";
        $params = array($username);
        $stmt = sqlsrv_query($this->conn, $query, $params);

        if ($stmt && sqlsrv_fetch($stmt)) {
            $hashedPassword = sqlsrv_get_field($stmt, 2);
            if (password_verify($password, $hashedPassword)) {
                return array(
                    "userID" => sqlsrv_get_field($stmt, 0),
                    "role" => sqlsrv_get_field($stmt, 3)
                );
            }
        }
        return false;
    }
}
?>
