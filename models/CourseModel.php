<?php
class CourseModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code) {
        $stmt = $this->conn->prepare("INSERT INTO course (course_code, course_name, exam_count, program_code, term, crn, section_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssissss', $course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getCourse($course_code) {
        $stmt = $this->conn->prepare("SELECT * FROM Course WHERE course_code = ?");
        $stmt->bind_param('s', $course_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();
        $stmt->close();
        return $course;
    }

    public function updateCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code) {
        $stmt = $this->conn->prepare("UPDATE Course SET course_name = ?, exam_count = ?, program_code = ?, term = ?, crn = ?, section_code = ? WHERE course_code = ?");
        $stmt->bind_param('sisssss', $course_name, $exam_count, $program_code, $term, $crn, $section_code, $course_code);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteCourse($course_code) {
        $stmt = $this->conn->prepare("DELETE FROM Course WHERE course_code = ?");
        $stmt->bind_param('s', $course_code);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>