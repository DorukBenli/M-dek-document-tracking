<?php
require_once 'models/SubmitModel.php';

class SubmitController {
    private $submitModel;

    public function __construct($dbConnection) {
        $this->submitModel = new SubmitModel($dbConnection);
    }

    public function submitDocument($user_username, $doc_id) {
        return $this->submitModel->submitDocument($user_username, $doc_id);
    }

    public function getSubmittedDocumentsByUser($user_username) {
        $submittedDocuments = $this->submitModel->getSubmittedDocumentsByUser($user_username);
        // You can render the view here or return the submitted documents as needed
        return $submittedDocuments;
    }

    public function deleteSubmission($user_username, $doc_id) {
        return $this->submitModel->deleteSubmission($user_username, $doc_id);
    }
}
?>