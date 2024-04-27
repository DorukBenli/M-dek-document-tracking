<?php
require_once '../models/SoftSubmitModel.php';
require_once '../database.php';

class SoftSubmitController
{
    private $submitModel;
    private $conn; // Add this property to store the connection

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection; // Store the connection
        $this->submitModel = new SoftSubmitModel($dbConnection);
    }

    public function submitSoftDocument($term, $crn, $document_type, $submitted_prof, $submitted_arg)
    {
        return $this->submitModel->submitSoftDocument($term, $crn, $document_type, $submitted_prof, $submitted_arg);
    }

    public function getSoftSubmittedDocuments($term, $crn)
    {
        return $this->submitModel->getSoftSubmittedDocuments($term, $crn);
    }

    public function deleteSoftSubmission($term, $crn, $document_type)
    {
        return $this->submitModel->deleteSoftSubmission($term, $crn, $document_type);
    }

    public function updateDocumentArg($term, $crn, $document_type)
    {
        return $this->submitModel->updateSoftSubmittedArg($term, $crn, $document_type);
    }

    public function undoDocumentArg($term, $crn, $document_type)
    {
        return $this->submitModel->undoSoftSubmittedArg($term, $crn, $document_type);
    }

    public function removeExamFromCourse($term, $crn, $document)
    {
        $status = $this->submitModel->deleteSubmissions($term, $crn, $document);
        return $status;
    }

    public function add($term, $crn, $document)
    {
        $result = true;
        $documents = $this->submitModel->getDocs($document);
        // Iterate through each retrieved document
        foreach ($documents as $doc) {
            // Call the insert function for the Submit table
            if (strpos($doc, '- Best') === false && strpos($doc, '- Average') === false && strpos($doc, '- Worst') === false && strpos($doc, 'Makeup') === false) {
                // Call the insert function for the Submit table
                $result = $this->submitModel->insert($term, $crn, $doc);
            }
        }
        return $result;
    }

    public function addOther($term, $crn, $document, $name)
    {
        $result = true;
        $documents = $this->submitModel->getDocs($document);
        // Iterate through each retrieved document
        foreach ($documents as $doc) {
            // Call the insert function for the Submit table
            if (strpos($doc, '- Best') === false && strpos($doc, '- Average') === false && strpos($doc, '- Worst') === false && strpos($doc, 'Makeup') === false) {
                // Call the insert function for the Submit table
                $result = $this->submitModel->insertOther($term, $crn, $doc, $name);
            }
        }
        return $result;
    }

    public function addMakeup($term, $crn, $document)
    {
        $result = true;
        $documents = $this->submitModel->getDocs($document);
        // Iterate through each retrieved document
        foreach ($documents as $doc) {
            // Call the insert function for the Submit table
            if (strpos($doc, '- Best') === false && strpos($doc, '- Average') === false && strpos($doc, '- Worst') === false) {
                // Call the insert function for the Submit table
                $result = $this->submitModel->insert($term, $crn, $doc);
            }
        }
        return $result;
    }
}
