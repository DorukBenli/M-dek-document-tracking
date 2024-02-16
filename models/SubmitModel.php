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

    public function updateSubmission($term, $courseCode, $documentType, $pdf_data)
    {
        // Update the PDF data
        $stmt = $this->conn->prepare("UPDATE Submit SET pdf_data = ? WHERE term = ? AND course_code = ? AND document_type = ?");
        $stmt->bind_param('ssss', $pdf_data, $term, $courseCode, $documentType);
        $pdfUpdateResult = $stmt->execute();
        $stmt->close();

        // If PDF data update was successful, update the submitted status
        if ($pdfUpdateResult) {
            // Set submitted to 1 for true
            $submitted = 1;
            $stmt = $this->conn->prepare("UPDATE Submit SET submitted = ? WHERE term = ? AND course_code = ? AND document_type = ?");
            $stmt->bind_param('isss', $submitted, $term, $courseCode, $documentType);
            $submittedUpdateResult = $stmt->execute();
            $stmt->close();

            // Return true only if both updates were successful
            return $submittedUpdateResult;
        } else {
            // Return false if PDF data update failed
            return false;
        }
    }

    public function removeSubmission($term, $courseCode, $documentType)
    {
        $stmt = $this->conn->prepare("UPDATE Submit SET submitted = 0, pdf_data = NULL WHERE term = ? AND course_code = ? AND document_type = ?");
        $stmt->bind_param('sss', $term, $courseCode, $documentType);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Function to retrieve PDF BLOB data from the Submit table
    public function getPDFData($term, $courseCode, $documentType)
    {
        // Prepare the SQL statement
        $stmt = $this->conn->prepare("SELECT pdf_data FROM Submit WHERE term = ? AND course_code = ? AND document_type = ?");
        $stmt->bind_param('sss', $term, $courseCode, $documentType);

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
}
