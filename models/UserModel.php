<?php
class UserModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createUser($username, $role) {
        $stmt = $this->conn->prepare("INSERT INTO User (username, role) VALUES (?, ?)");
        $stmt->bind_param('ss', $username, $role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUser($username) {
        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateUser($username, $role) {
        $stmt = $this->conn->prepare("UPDATE User SET role = ? WHERE username = ?");
        $stmt->bind_param('ss', $role, $username);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteUser($username) {
        $stmt = $this->conn->prepare("DELETE FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Associate a user with a course
    public function addTeachingCourse($username, $courseCode) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO Teaches (user_username, course_code) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $username, $courseCode);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Get courses taught by a user
    public function getTeachingCourses($username) {
        $stmt = mysqli_prepare($this->conn, "SELECT c.* FROM Course c JOIN Teaches t ON c.course_code = t.course_code WHERE t.user_username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $courses = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $courses;
    }

    // Associate a user with a course
    public function addHandlingCourse($username, $courseCode) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO Handles (user_username, course_code) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $username, $courseCode);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Get courses handled by a user
    public function getHandlingCourses($username) {
        $stmt = mysqli_prepare($this->conn, "SELECT c.* FROM Course c JOIN Handles t ON c.course_code = t.course_code WHERE t.user_username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $courses = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $courses;
    }
}
?>