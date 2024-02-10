<?php
class SoftSubmitModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function submitSoftDocument($term, $crn, $document_type, $submitted_prof, $submitted_arg) {
        $stmt = $this->conn->prepare("INSERT INTO Soft_Submit (term, crn, document_type, submitted_prof, submitted_arg) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiss', $term, $crn, $document_type, $submitted_prof, $submitted_arg);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getSoftSubmittedDocuments($term, $crn) {
        $stmt = $this->conn->prepare("SELECT * FROM Soft_Submit WHERE term = ? AND crn = ?");
        $stmt->bind_param('si', $term, $crn);
        $stmt->execute();
        $result = $stmt->get_result();
        $submittedDocuments = [];
        while ($row = $result->fetch_assoc()) {
            $submittedDocuments[] = $row;
        }
        $stmt->close();
        return $submittedDocuments;
    }

    public function deleteSoftSubmission($term, $crn, $document_type) {
        $stmt = $this->conn->prepare("DELETE FROM Soft_Submit WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $document_type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
