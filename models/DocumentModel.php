<?php
class DocumentModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createDocument($type, $pdf_data, $soft, $exam) {
        $stmt = $this->conn->prepare("INSERT INTO Documents (type, pdf_data, soft, exam) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sbii', $type, $pdf_data, $soft, $exam);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getDocument($type) {
        $stmt = $this->conn->prepare("SELECT * FROM Documents WHERE type = ?");
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();
        $stmt->close();
        return $document;
    }

    public function updateDocument($type, $pdf_data, $soft, $exam) {
        $stmt = $this->conn->prepare("UPDATE Documents SET pdf_data = ?, soft = ?, exam = ? WHERE type = ?");
        $stmt->bind_param('biis', $pdf_data, $soft, $exam, $type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteDocument($type) {
        $stmt = $this->conn->prepare("DELETE FROM Documents WHERE type = ?");
        $stmt->bind_param('s', $type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function associateWithRequirement($documentType, $requirementType) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO RequiredDocuments (document_type, requirement_type) VALUES (?, ?)");
            $stmt->bind_param('ss', $documentType, $requirementType);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            // Handle exception
            return false;
        }
    }

    public function getAssociatedRequirements($documentType) {
        try {
            $stmt = $this->conn->prepare("SELECT r.* FROM Requirement r JOIN RequiredDocuments rd ON r.type = rd.requirement_type WHERE rd.document_type = ?");
            $stmt->bind_param('s', $documentType);
            $stmt->execute();
            $result = $stmt->get_result();
            $requirements = [];
            while ($row = $result->fetch_assoc()) {
                $requirements[] = $row;
            }
            $stmt->close();
            return $requirements;
        } catch (Exception $e) {
            // Handle exception
            return [];
        }
    }
}
?>