<?php
require_once '../models/DocumentModel.php';
require_once '../database.php';

class DocumentController
{
    private $documentModel;
    private $conn; // Add this property to store the connection


    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection; // Store the connection
        $this->documentModel = new DocumentModel($dbConnection);
    }

    public function createDocument($type, $pdf_data, $soft, $exam)
    {
        return $this->documentModel->createDocument($type, $pdf_data, $soft, $exam);
    }

    public function getDocument($type)
    {
        $document = $this->documentModel->getDocument($type);
        // You can render the view here or return the document data as needed
        return $document;
    }

    public function updateDocument($type, $pdf_data, $soft, $exam)
    {
        return $this->documentModel->updateDocument($type, $pdf_data, $soft, $exam);
    }

    public function deleteDocument($type)
    {
        return $this->documentModel->deleteDocument($type);
    }

    public function associateDocumentWithRequirement($documentType, $requirementType)
    {
        $result = $this->documentModel->associateWithRequirement($documentType, $requirementType);
        if ($result) {
            echo json_encode(["message" => "Requirement associated with document successfully"]);
        } else {
            echo json_encode(["message" => "Failed to associate requirement with document"]);
        }
    }

    public function getDocumentRequirements($documentType)
    {
        $requirements = $this->documentModel->getAssociatedRequirements($documentType);
        if (!empty($requirements)) {
            echo json_encode($requirements);
        } else {
            echo json_encode(["message" => "No requirements found for this document"]);
        }
    }

    public function add($documentTypes)
    {
        foreach ($documentTypes as $type) {
            $exam = 0;
            $soft = 0;
            if (stripos($type, 'Attendance') === false) {
                $exam = 1;
            } else {
                $soft = 1;
            }

            // Call the insert function with the appropriate parameters
            $result = $this->documentModel->createDocument($type, $exam, $soft);
            if (!$result) {
                // Handle the failure as needed
                return false;
            }
        }

        // Return true if all documents were successfully inserted
        return true;
    }


    public function addRequiredDocuments($documentTypes)
    {
        // Determine the base document type
        $baseDocument = $documentTypes[0];
        $baseDocumentLength = strlen($baseDocument);

        foreach ($documentTypes as $document) {
            // Extract the substring of the document type up to the length of the base document
            $compareString = substr($document, 0, $baseDocumentLength);

            // Determine the requirement type based on whether the extracted substring matches the base document
            $requirementType = ($compareString === $baseDocument) ? 'YÖK' : 'MUDEK';

            // Call the insert function for RequiredDocuments table
            $result = $this->documentModel->insertRequiredDocument($requirementType, $document);
            if (!$result) {
                // Handle the failure as needed
                return false;
            }
        }

        // Return true if all documents were successfully inserted
        return true;
    }
}
