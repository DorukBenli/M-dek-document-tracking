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

    public function associateWithDocument($requirementType, $documentType) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO RequiredDocuments (requirement_type, document_type) VALUES (?, ?)");
            $stmt->bind_param('ss', $requirementType, $documentType);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            // Handle exception
            return false;
        }
    }

    public function getAssociatedDocuments($requirementType) {
        try {
            $stmt = $this->conn->prepare("SELECT d.* FROM Documents d JOIN RequiredDocuments rd ON d.type = rd.document_type WHERE rd.requirement_type = ?");
            $stmt->bind_param('s', $requirementType);
            $stmt->execute();
            $result = $stmt->get_result();
            $documents = [];
            while ($row = $result->fetch_assoc()) {
                $documents[] = $row;
            }
            $stmt->close();
            return $documents;
        } catch (Exception $e) {
            // Handle exception
            return [];
        }
    }
}
?>
