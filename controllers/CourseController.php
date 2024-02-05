<?php
require_once 'models/CourseModel.php';

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

    public function createCourseView() {
        include 'views/add_course.php';
    }

    // Associate a course with a requirement
    public function associateCourseWithRequirement($courseCode, $requirementType) {
        $result = $this->courseModel->addCourseRequirement($courseCode, $requirementType);
        if ($result) {
            // Success: Return a success response
            echo json_encode(["message" => "Course associated with the requirement successfully"]);
        } else {
            // Error: Return an error response
            echo json_encode(["message" => "Failed to associate course with the requirement"]);
        }
    }

    // Get requirements associated with a course
    public function getCourseRequirements($courseCode) {
        $requirements = $this->courseModel->getCourseRequirements($courseCode);
        if (!empty($requirements)) {
            // Success: Return the requirements as a JSON response
            echo json_encode($requirements);
        } else {
            // No requirements found: Return an empty response or an error message
            echo json_encode(["message" => "No requirements found for this course"]);
        }
    }

    // Get users who teach a specific course
    public function getUsersTeachingThisCourse($courseCode) {
        $users = $this->courseModel->getUsersTeachingCourse($courseCode);
        if (!empty($users)) {
            // Success: Return the users as a JSON response
            echo json_encode($users);
        } else {
            // No users found: Return an empty response or an error message
            echo json_encode(["message" => "No users found teaching this course"]);
        }
    }

    // Get users who handle a specific course
    public function getUsersHandlingThisCourse($courseCode) {
        $users = $this->courseModel->getUsersTeachingCourse($courseCode);
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