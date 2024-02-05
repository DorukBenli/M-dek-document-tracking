<?php
class SubmitModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function submitDocument($user_username, $doc_id) {
        $stmt = $this->conn->prepare("INSERT INTO Submit (user_username, doc_id) VALUES (?, ?)");
        $stmt->bind_param('si', $user_username, $doc_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getSubmittedDocumentsByUser($user_username) {
        $stmt = $this->conn->prepare("SELECT * FROM Submit WHERE user_username = ?");
        $stmt->bind_param('s', $user_username);
        $stmt->execute();
        $result = $stmt->get_result();
        $submittedDocuments = [];
        while ($row = $result->fetch_assoc()) {
            $submittedDocuments[] = $row;
        }
        $stmt->close();
        return $submittedDocuments;
    }

    public function deleteSubmission($user_username, $doc_id) {
        $stmt = $this->conn->prepare("DELETE FROM Submit WHERE user_username = ? AND doc_id = ?");
        $stmt->bind_param('si', $user_username, $doc_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>