<?php
require_once '../database.php'; // Include the database connection details
require_once '../models/SubmitModel.php';

class SubmitController {
    private $submitModel;
    private $conn; // Add this property to store the connection


    public function __construct($conn) {
        $this->conn = $conn; // Store the connection
        $this->submitModel = new SubmitModel($conn);
    }

    public function submitDocument($term, $crn, $document_type, $submitted, $pdf_data) {
        return $this->submitModel->submitDocument($term, $crn, $document_type, $submitted, $pdf_data);
    }

    public function getSubmittedDocuments($term, $crn) {
        return $this->submitModel->getSubmittedDocuments($term, $crn);
    }

    public function deleteSubmission($term, $crn, $document_type) {
        return $this->submitModel->deleteSubmission($term, $crn, $document_type);
    }

    public function updateDocument($term, $crn, $document_type, $pdf_data) {
        return $this->submitModel->updateSubmission($term, $crn, $document_type, $pdf_data);
    }

    public function removeDocument($term, $crn, $document_type) {
        return $this->submitModel->removeSubmission($term, $crn, $document_type);
    }

    public function getPDFData($term, $courseCode, $documentType) {
        return $this->submitModel->getPDFData($term, $courseCode, $documentType);
    }

}
?>