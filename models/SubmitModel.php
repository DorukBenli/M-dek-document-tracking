<?php
require_once '../database.php'; // Include the database connection details

class SubmitModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function submitDocument($term, $crn, $document_type, $submitted, $pdf_data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Submit (term, crn, document_type, submitted, pdf_data) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siisb', $term, $crn, $document_type, $submitted, $pdf_data);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function insert($term, $crn, $document_type)
    {
        $submitted = 0;
        $pdf_data = NULL;
        $stmt = $this->conn->prepare("INSERT INTO Submit (term, crn, document_type, submitted, pdf_data) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sisib', $term, $crn, $document_type, $submitted, $pdf_data);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function insertOther($term, $crn, $document_type, $name)
    {
        $submitted = 0;
        $pdf_data = NULL;
        $stmt = $this->conn->prepare("INSERT INTO Submit (term, crn, document_type, submitted, pdf_data, name) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sisibs', $term, $crn, $document_type, $submitted, $pdf_data, $name);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getSubmittedDocuments($term, $crn)
    {
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

    public function deleteSubmission($term, $crn, $document_type)
    {
        $stmt = $this->conn->prepare("DELETE FROM Submit WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $document_type);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Function to delete submission rows with "Final" as a substring in the document type
    public function deleteSubmissions($term, $crn, $documentType)
    {
        $pattern = '%' . $documentType . '%'; // Add wildcard characters
        $stmt = $this->conn->prepare("DELETE FROM Submit WHERE term = ? AND crn = ? AND document_type LIKE ?");
        $stmt->bind_param('sis', $term, $crn, $pattern);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


    public function updateSubmission($term, $crn, $documentType, $pdf_data)
    {
        // Update the PDF data
        $stmt = $this->conn->prepare("UPDATE Submit SET pdf_data = ? WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('ssis', $pdf_data, $term, $crn, $documentType);
        $pdfUpdateResult = $stmt->execute();
        $stmt->close();

        // If PDF data update was successful, update the submitted status
        if ($pdfUpdateResult) {
            // Set submitted to 1 for true
            $submitted = 1;
            $stmt = $this->conn->prepare("UPDATE Submit SET submitted = ? WHERE term = ? AND crn = ? AND document_type = ?");
            $stmt->bind_param('isis', $submitted, $term, $crn, $documentType);
            $submittedUpdateResult = $stmt->execute();
            $stmt->close();

            // Return true only if both updates were successful
            return $submittedUpdateResult;
        } else {
            // Return false if PDF data update failed
            return false;
        }
    }

    public function removeSubmission($term, $crn, $documentType)
    {
        $stmt = $this->conn->prepare("UPDATE Submit SET submitted = 0, pdf_data = NULL WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $documentType);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function removeSubmissionsByDocumentType($term, $crn, $document_type)
    {
        // Prepare the SQL query to retrieve rows with matching document types
        $sql = "SELECT document_type FROM Submit WHERE term = ? AND crn = ? AND document_type LIKE ?";

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


    // Function to retrieve PDF BLOB data from the Submit table
    public function getPDFData($term, $crn, $documentType)
    {
        // Prepare the SQL statement
        $stmt = $this->conn->prepare("SELECT pdf_data FROM Submit WHERE term = ? AND crn = ? AND document_type = ?");
        $stmt->bind_param('sis', $term, $crn, $documentType);

        // Execute the query
        if (!$stmt->execute()) {
            // Handle query execution error
            echo "Error executing query: " . $stmt->error;
            return false;
        }

        // Get the result
        $result = $stmt->get_result();

        // Fetch the row
        $row = $result->fetch_assoc();

        // Close the statement
        $stmt->close();

        // Check if a row was fetched
        if (!$row) {
            // Handle no data found
            echo "No PDF data found for the given parameters.";
            return false;
        }

        // Return the PDF BLOB data
        return $row['pdf_data'];
    }

    public function getNumMidtermsForCourse($term, $crn)
    {
        // Initialize $numMidterms with default value 1
        $numMidterms = 1;

        $stmt = $this->conn->prepare("SELECT COUNT(*) AS num_midterms FROM Submit WHERE term = ? AND crn = ? AND document_type LIKE 'Midterm %' AND (SUBSTRING(document_type, -1) REGEXP '^[0-9]+$' OR SUBSTRING(document_type, -2) REGEXP '^[0-9]+$')");
        $stmt->bind_param('si', $term, $crn);

        // Execute the query
        $stmt->execute();

        // Bind the result
        $stmt->bind_result($numMidterms);

        // Fetch the result
        $stmt->fetch();

        // Close the statement and connection
        $stmt->close();

        // If $numMidterms is null, set it to 0
        if ($numMidterms === null) {
            $numMidterms = 0;
        }

        return $numMidterms;
    }

    public function getDocs($document)
    {
        // Prepare the SQL query to retrieve documents containing the specified substring
        $sql = "SELECT type FROM bitirme.Documents WHERE LOWER(type) LIKE ?";

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
}
