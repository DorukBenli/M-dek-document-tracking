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
}
?>