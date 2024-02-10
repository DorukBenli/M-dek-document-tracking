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

    public function addCourseRequirement($term, $crn, $requirementType) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO CourseRequirements (term, crn, requirement_type) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sis', $term, $crn, $requirementType);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function getCourseRequirements($term, $crn) {
        $stmt = mysqli_prepare($this->conn, "SELECT r.* FROM Requirement r JOIN CourseRequirements cr ON r.type = cr.requirement_type WHERE cr.term = ? AND cr.crn = ?");
        mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $requirements = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $requirements[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $requirements;
    }

    public function getUsersTeachingCourse($term, $crn) {
        $stmt = mysqli_prepare($this->conn, "SELECT u.* FROM User u JOIN Teaches t ON u.username = t.username WHERE t.term = ? AND t.crn = ?");
        mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $users;
    }

    public function getUsersHandlingCourse($term, $crn) {
        $stmt = mysqli_prepare($this->conn, "SELECT u.* FROM User u JOIN Handles h ON u.username = h.username WHERE h.term = ? AND h.crn = ?");
        mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $users;
    }
}
?>