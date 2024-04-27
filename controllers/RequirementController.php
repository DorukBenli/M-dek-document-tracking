<?php
require_once '../models/RequirementModel.php';
require_once '../database.php';

class RequirementController {
    private $requirementModel;
    private $conn; // Add this property to store the connection

    public function __construct($dbConnection) {
        $this->conn = $dbConnection; // Store the connection
        $this->requirementModel = new RequirementModel($dbConnection);
    }

    public function createRequirement($type) {
        return $this->requirementModel->createRequirement($type);
    }

    public function getRequirement($type) {
        $requirement = $this->requirementModel->getRequirement($type);
        // You can render the view here or return the requirement data as needed
        return $requirement;
    }

    public function updateRequirement($type, $newType) {
        return $this->requirementModel->updateRequirement($type, $newType);
    }

    public function deleteRequirement($type) {
        return $this->requirementModel->deleteRequirement($type);
    }

    public function associateRequirementWithDocument($requirementType, $documentType) {
        $result = $this->requirementModel->associateWithDocument($requirementType, $documentType);
        if ($result) {
            echo json_encode(["message" => "Document associated with requirement successfully"]);
        } else {
            echo json_encode(["message" => "Failed to associate document with requirement"]);
        }
    }

    public function getRequirementDocuments($requirementType) {
        $documents = $this->requirementModel->getAssociatedDocuments($requirementType);
        if (!empty($documents)) {
            echo json_encode($documents);
        } else {
            echo json_encode(["message" => "No documents found for this requirement"]);
        }
    }
}
?>