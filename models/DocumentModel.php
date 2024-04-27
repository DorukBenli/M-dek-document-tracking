<?php
class DocumentModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function createDocument($type, $exam, $soft)
    {
        $stmt = $this->conn->prepare("INSERT INTO Documents (type, exam, soft) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $type, $exam, $soft);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getDocument($type)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Documents WHERE type = ?");
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();
        $stmt->close();
        return $document;
    }

    public function updateDocument($type, $pdf_data, $soft, $exam)
    {
        $stmt = $this->conn->prepare("UPDATE Documents SET pdf_data = ?, soft = ?, exam = ? WHERE type = ?");
        $stmt->bind_param('biis', $pdf_data, $soft, $exam, $type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteDocument($type)
    {
        $stmt = $this->conn->prepare("DELETE FROM Documents WHERE type = ?");
        $stmt->bind_param('s', $type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function associateWithRequirement($documentType, $requirementType)
    {
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

    public function getAssociatedRequirements($documentType)
    {
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

    public function insertRequiredDocument($requirementType, $documentType)
    {
        $stmt = $this->conn->prepare("INSERT INTO RequiredDocuments (requirement_type, document_type) VALUES (?, ?)");
        if (!$stmt) {
            // Handle the error if the statement preparation fails
            return false;
        }

        // Bind the parameters and execute the statement
        $stmt->bind_param('ss', $requirementType, $documentType);
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        // Return the result of the execution
        return $result;
    }
}
