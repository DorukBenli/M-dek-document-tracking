<?php
class RequirementModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createRequirement($type) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO Requirement (type) VALUES (?)");
            $stmt->bind_param('s', $type);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Requirement created successfully'];
            } else {
                return ['success' => false, 'message' => 'Error creating requirement'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function getRequirement($type) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM Requirement WHERE type = ?");
            $stmt->bind_param('s', $type);
            $stmt->execute();
            $result = $stmt->get_result();
            $requirement = $result->fetch_assoc();
            return $requirement;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function updateRequirement($type, $newType) {
        try {
            $stmt = $this->conn->prepare("UPDATE Requirement SET type = ? WHERE type = ?");
            $stmt->bind_param('ss', $newType, $type);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Requirement updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Error updating requirement'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function deleteRequirement($type) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM Requirement WHERE type = ?");
            $stmt->bind_param('s', $type);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Requirement deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Error deleting requirement'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }
}
?>
