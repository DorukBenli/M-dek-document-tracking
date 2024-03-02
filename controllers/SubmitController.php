<?php
require_once '../database.php'; // Include the database connection details
require_once '../models/SubmitModel.php';

class SubmitController
{
    private $submitModel;
    private $conn; // Add this property to store the connection


    public function __construct($conn)
    {
        $this->conn = $conn; // Store the connection
        $this->submitModel = new SubmitModel($conn);
    }

    public function submitDocument($term, $crn, $document_type, $submitted, $pdf_data)
    {
        return $this->submitModel->submitDocument($term, $crn, $document_type, $submitted, $pdf_data);
    }

    public function getSubmittedDocuments($term, $crn)
    {
        return $this->submitModel->getSubmittedDocuments($term, $crn);
    }

    public function deleteSubmission($term, $crn, $document_type)
    {
        return $this->submitModel->deleteSubmission($term, $crn, $document_type);
    }

    public function updateDocument($term, $crn, $document_type, $pdf_data)
    {
        return $this->submitModel->updateSubmission($term, $crn, $document_type, $pdf_data);
    }

    public function removeDocument($term, $crn, $document_type)
    {
        return $this->submitModel->removeSubmission($term, $crn, $document_type);
    }

    public function getPDFData($term, $courseCode, $documentType)
    {
        return $this->submitModel->getPDFData($term, $courseCode, $documentType);
    }

    public function removeExamFromCourse($term, $crn, $document)
    {
        $status = $this->submitModel->removeSubmissionsByDocumentType($term, $crn, $document);
        $status = $this->submitModel->deleteSubmissions($term, $crn, $document);
        return $status;
    }

    public function getNumMidtermsForCourse($term, $crn)
    {
        return $this->submitModel->getNumMidtermsForCourse($term, $crn);
    }

    public function add($term, $crn, $document)
    {
        $result=true;
        $documents = $this->submitModel->getDocs($document);
        // Iterate through each retrieved document
        foreach ($documents as $doc) {
            // Call the insert function for the Submit table
            $result = $this->submitModel->insert($term, $crn, $doc);
        }
        return $result;
    }
}
