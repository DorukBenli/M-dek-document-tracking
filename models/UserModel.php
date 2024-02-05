<?php
class UserModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createUser($username, $role) {
        $stmt = $this->conn->prepare("INSERT INTO User (username, role) VALUES (?, ?)");
        $stmt->bind_param('ss', $username, $role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUser($username) {
        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateUser($username, $role) {
        $stmt = $this->conn->prepare("UPDATE User SET role = ? WHERE username = ?");
        $stmt->bind_param('ss', $role, $username);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteUser($username) {
        $stmt = $this->conn->prepare("DELETE FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>