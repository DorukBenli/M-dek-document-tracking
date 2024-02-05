<?php
class RequirementModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createRequirement($type) {
        $stmt = $this->conn->prepare("INSERT INTO Requirement (type) VALUES (?)");
        $stmt->bind_param('s', $type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getRequirement($type) {
        $stmt = $this->conn->prepare("SELECT * FROM Requirement WHERE type = ?");
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $requirement = $result->fetch_assoc();
        $stmt->close();
        return $requirement;
    }

    public function updateRequirement($type, $newType) {
        $stmt = $this->conn->prepare("UPDATE Requirement SET type = ? WHERE type = ?");
        $stmt->bind_param('ss', $newType, $type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteRequirement($type) {
        $stmt = $this->conn->prepare("DELETE FROM Requirement WHERE type = ?");
        $stmt->bind_param('s', $type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>