<?php
class SubmitModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function submitDocument($term, $crn, $document_type, $submitted, $pdf_data) {
        $stmt = $this->conn->prepare("INSERT INTO Submit (term, crn, document_type, submitted, pdf_data) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siisb', $term, $crn, $document_type, $submitted, $pdf_data);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getSubmittedDocuments($term, $crn) {
        $stmt = $this->conn->prepare("SELECT * FROM Submit WHERE term = ? AND crn = ?");
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

    public function deleteSubmission($term, $crn, $document_type) {
        $stmt = $this->conn->prepare("DELETE FROM Submit WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $document_type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
