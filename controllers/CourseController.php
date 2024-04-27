<?php
require_once 'models/CourseModel.php';

use Psr\Http\Message\ResponseInterface as Response;

class CourseController {
    private $courseModel;

    public function __construct($dbConnection) {
        $this->courseModel = new CourseModel($dbConnection);
    }

    public function createCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code) {
        return $this->courseModel->createCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code);
    }

    public function getCourse($course_code) {
        $course = $this->courseModel->getCourse($course_code);
        // You can render the view here or return the course data as needed
        return $course;
    }

    public function updateCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code) {
        return $this->courseModel->updateCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code);
    }

    public function deleteCourse($course_code) {
        return $this->courseModel->deleteCourse($course_code);
    }

    public function createCourseView(Response $response): Response {
         // Start output buffering
        ob_start();
        // Include your view file
        include 'views/add_course.php';
        // Get the contents of the buffer
        $html = ob_get_clean();
        // Write the contents to the response body
        $response->getBody()->write($html);

        return $response;
    }

    // Associate a course with a requirement
    public function associateCourseWithRequirement($term, $crn, $requirementType) {
        $result = $this->courseModel->addCourseRequirement($term, $crn, $requirementType);
        if ($result) {
            // Success: Return a success response
            echo json_encode(["message" => "Course associated with the requirement successfully"]);
        } else {
            // Error: Return an error response
            echo json_encode(["message" => "Failed to associate course with the requirement"]);
        }
    }

    // Get requirements associated with a course
    public function getCourseRequirements($term, $crn) {
        $requirements = $this->courseModel->getCourseRequirements($term, $crn);
        if (!empty($requirements)) {
            // Success: Return the requirements as a JSON response
            echo json_encode($requirements);
        } else {
            // No requirements found: Return an empty response or an error message
            echo json_encode(["message" => "No requirements found for this course"]);
        }
    }

    // Get users who teach a specific course
    public function getUsersTeachingThisCourse($term, $crn) {
        $users = $this->courseModel->getUsersTeachingCourse($term, $crn);
        if (!empty($users)) {
            // Success: Return the users as a JSON response
            echo json_encode($users);
        } else {
            // No users found: Return an empty response or an error message
            echo json_encode(["message" => "No users found teaching this course"]);
        }
    }

    // Get users who handle a specific course
    public function getUsersHandlingThisCourse($term, $crn) {
        $users = $this->courseModel->getUsersHandlingCourse($term, $crn);
        if (!empty($users)) {
            // Success: Return the users as a JSON response
            echo json_encode($users);
        } else {
            // No users found: Return an empty response or an error message
            echo json_encode(["message" => "No users found handling this course"]);
        }
    }
}
?>