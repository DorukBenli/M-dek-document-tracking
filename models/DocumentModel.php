<?php
class DocumentModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createDocument($doc_type, $pdf_data, $soft_copy, $exam) {
        $stmt = $this->conn->prepare("INSERT INTO Documents (doc_type, pdf_data, soft_copy, exam) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssii', $doc_type, $pdf_data, $soft_copy, $exam);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getDocument($doc_id) {
        $stmt = $this->conn->prepare("SELECT * FROM Documents WHERE doc_id = ?");
        $stmt->bind_param('i', $doc_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();
        $stmt->close();
        return $document;
    }

    public function updateDocument($doc_id, $doc_type, $pdf_data, $soft_copy, $exam) {
        $stmt = $this->conn->prepare("UPDATE Documents SET doc_type = ?, pdf_data = ?, soft_copy = ?, exam = ? WHERE doc_id = ?");
        $stmt->bind_param('ssiis', $doc_type, $pdf_data, $soft_copy, $exam, $doc_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteDocument($doc_id) {
        $stmt = $this->conn->prepare("DELETE FROM Documents WHERE doc_id = ?");
        $stmt->bind_param('i', $doc_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>