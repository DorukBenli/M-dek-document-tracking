<?php
class SoftSubmitModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function submitSoftDocument($term, $crn, $document_type, $submitted_prof, $submitted_arg)
    {
        $stmt = $this->conn->prepare("INSERT INTO Soft_Submit (term, crn, document_type, submitted_prof, submitted_arg) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiss', $term, $crn, $document_type, $submitted_prof, $submitted_arg);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getSoftSubmittedDocuments($term, $crn)
    {
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

    public function deleteSoftSubmission($term, $crn, $document_type)
    {
        $stmt = $this->conn->prepare("DELETE FROM Soft_Submit WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $document_type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateSoftSubmittedArg($term, $crn, $document_type)
    {
        $stmt = $this->conn->prepare("UPDATE Soft_Submit SET submitted_arg = 1 WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $document_type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function undoSoftSubmittedArg($term, $crn, $document_type)
    {
        $stmt = $this->conn->prepare("UPDATE Soft_Submit SET submitted_arg = 0 WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $document_type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function removeSubmission($term, $crn, $documentType)
    {
        $stmt = $this->conn->prepare("UPDATE Soft_Submit SET submitted_prof = 0, submitted_arg = 0 WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $documentType);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function removeSubmissionsByDocumentType($term, $crn, $document_type)
    {
        // Prepare the SQL query to retrieve rows with matching document types
        $sql = "SELECT document_type FROM bitirme.soft_submit WHERE term = ? AND crn = ? AND document_type LIKE ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle the error if the statement preparation fails
            return false;
        }

        // Bind the parameters
        $Pattern = '%' . $document_type . '%'; // Add wildcard characters
        $stmt->bind_param('sis', $term, $crn, $Pattern);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Loop through the results
        while ($row = $result->fetch_assoc()) {
            // Get the document_type
            $documentType = $row['document_type'];

            // Remove submission for each matching document_type
            $this->removeSubmission($term, $crn, $documentType);
        }

        // Close the statement
        $stmt->close();

        return true;
    }

    // Function to delete submission rows with "Final" as a substring in the document type
    public function deleteSubmissions($term, $crn, $documentType)
    {
        $pattern = '%' . $documentType . '%'; // Add wildcard characters
        $stmt = $this->conn->prepare("DELETE FROM Soft_Submit WHERE term = ? AND crn = ? AND document_type LIKE ?");
        $stmt->bind_param('sis', $term, $crn, $pattern);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getDocs($document)
    {
        // Prepare the SQL query to retrieve documents containing the specified substring
        $sql = "SELECT type FROM bitirme.Documents WHERE type LIKE ?";

        // Bind the search term with wildcards to the SQL query
        $searchTerm = '%' . strtolower($document) . '%';

        // Prepare and execute the SQL query
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Fetch the documents into an array
        $documents = [];
        while ($row = $result->fetch_assoc()) {
            $documents[] = $row['type'];
        }

        // Close the statement
        $stmt->close();

        // Return the retrieved documents
        return $documents;
    }

    public function insert($term, $crn, $document_type)
    {
        $submitted_prof = 0;
        $submitted_arg = 0;
        $stmt = $this->conn->prepare("INSERT INTO Soft_Submit (term, crn, document_type, submitted_prof, submitted_arg) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sisii', $term, $crn, $document_type, $submitted_prof, $submitted_arg);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function insertOther($term, $crn, $document_type, $name)
    {
        $submitted_prof = 0;
        $submitted_arg = 0;
        $stmt = $this->conn->prepare("INSERT INTO Soft_Submit (term, crn, document_type, submitted_prof, submitted_arg, name) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sisiis', $term, $crn, $document_type, $submitted_prof, $submitted_arg, $name);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
