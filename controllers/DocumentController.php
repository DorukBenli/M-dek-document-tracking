<?php
require_once 'models/DocumentModel.php';

class DocumentController {
    private $documentModel;

    public function __construct($dbConnection) {
        $this->documentModel = new DocumentModel($dbConnection);
    }

    public function createDocument($doc_type, $pdf_data, $soft_copy, $exam) {
        return $this->documentModel->createDocument($doc_type, $pdf_data, $soft_copy, $exam);
    }

    public function getDocument($doc_id) {
        $document = $this->documentModel->getDocument($doc_id);
        // You can render the view here or return the document data as needed
        return $document;
    }

    public function updateDocument($doc_id, $doc_type, $pdf_data, $soft_copy, $exam) {
        return $this->documentModel->updateDocument($doc_id, $doc_type, $pdf_data, $soft_copy, $exam);
    }

    public function deleteDocument($doc_id) {
        return $this->documentModel->deleteDocument($doc_id);
    }
}
?>