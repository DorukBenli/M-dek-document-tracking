<?php
require_once 'models/DocumentModel.php';

class DocumentController {
    private $documentModel;

    public function __construct($dbConnection) {
        $this->documentModel = new DocumentModel($dbConnection);
    }

    public function createDocument($type, $pdf_data, $soft, $exam) {
        return $this->documentModel->createDocument($type, $pdf_data, $soft, $exam);
    }

    public function getDocument($type) {
        $document = $this->documentModel->getDocument($type);
        // You can render the view here or return the document data as needed
        return $document;
    }

    public function updateDocument($type, $pdf_data, $soft, $exam) {
        return $this->documentModel->updateDocument($type, $pdf_data, $soft, $exam);
    }

    public function deleteDocument($type) {
        return $this->documentModel->deleteDocument($type);
    }
}
?>