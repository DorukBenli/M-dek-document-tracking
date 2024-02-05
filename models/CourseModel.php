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

    // Associate a course with a requirement
    public function addCourseRequirement($courseCode, $requirementType) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO CourseRequirements (course_code, requirement_type) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $courseCode, $requirementType);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Get requirements associated with a course
    public function getCourseRequirements($courseCode) {
        $stmt = mysqli_prepare($this->conn, "SELECT r.* FROM Requirement r JOIN CourseRequirements cr ON r.type = cr.requirement_type WHERE cr.course_code = ?");
        mysqli_stmt_bind_param($stmt, 's', $courseCode);
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

    // Get users who teach a specific course
    public function getUsersTeachingCourse($courseCode) {
        $stmt = mysqli_prepare($this->conn, "SELECT u.* FROM User u JOIN Teaches t ON u.username = t.user_username WHERE t.course_code = ?");
        mysqli_stmt_bind_param($stmt, 's', $courseCode);
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

    // Get users who teach a specific course
    public function getUsersHandlingCourse($courseCode) {
        $stmt = mysqli_prepare($this->conn, "SELECT u.* FROM User u JOIN Handles t ON u.username = t.user_username WHERE t.course_code = ?");
        mysqli_stmt_bind_param($stmt, 's', $courseCode);
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