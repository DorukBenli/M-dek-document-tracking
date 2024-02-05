<?php
require_once 'models/RequirementModel.php';

class RequirementController {
    private $requirementModel;

    public function __construct($dbConnection) {
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
}
?>