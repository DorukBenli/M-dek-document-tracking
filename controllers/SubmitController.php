<?php
require_once 'models/SubmitModel.php';

class SubmitController {
    private $submitModel;

    public function __construct($dbConnection) {
        $this->submitModel = new SubmitModel($dbConnection);
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
}
?>