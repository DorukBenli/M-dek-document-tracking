<?php
require_once 'models/SoftSubmitModel.php';

class SoftSubmitController {
    private $submitModel;

    public function __construct($dbConnection) {
        $this->submitModel = new SoftSubmitModel($dbConnection);
    }

    public function submitSoftDocument($term, $crn, $document_type, $submitted_prof, $submitted_arg) {
        return $this->submitModel->submitSoftDocument($term, $crn, $document_type, $submitted_prof, $submitted_arg);
    }

    public function getSoftSubmittedDocuments($term, $crn) {
        return $this->submitModel->getSoftSubmittedDocuments($term, $crn);
    }

    public function deleteSoftSubmission($term, $crn, $document_type) {
        return $this->submitModel->deleteSoftSubmission($term, $crn, $document_type);
    }
}
?>