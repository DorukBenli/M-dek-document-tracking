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

    public function associateDocumentWithRequirement($documentType, $requirementType) {
        $result = $this->documentModel->associateWithRequirement($documentType, $requirementType);
        if ($result) {
            echo json_encode(["message" => "Requirement associated with document successfully"]);
        } else {
            echo json_encode(["message" => "Failed to associate requirement with document"]);
        }
    }

    public function getDocumentRequirements($documentType) {
        $requirements = $this->documentModel->getAssociatedRequirements($documentType);
        if (!empty($requirements)) {
            echo json_encode($requirements);
        } else {
            echo json_encode(["message" => "No requirements found for this document"]);
        }
    }
}
?>