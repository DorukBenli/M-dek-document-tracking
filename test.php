<?php
require_once 'controllers/UserController.php';
require_once 'controllers/CourseController.php';
require_once 'controllers/RequirementController.php';
require_once 'controllers/DocumentController.php';
require_once 'controllers/SubmitController.php';

include 'database.php';

$userController = new UserController($conn);
$courseController = new CourseController($conn);
$requirementController = new RequirementController($conn);
$documentController = new DocumentController($conn);
$submitController = new SubmitController($conn);

$userController->createUser("deneme", "argor");
//$result = $courseController->createCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code);
$userData = $userController->getUser("testuser");
echo($userData);

$conn->close();
?>