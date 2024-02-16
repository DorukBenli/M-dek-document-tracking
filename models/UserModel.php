<?php
require_once '../database.php'; // Include the database connection details
require_once '../helper.php';

class UserModel
{
    private $conn;
    private $courseModel;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
        $this->courseModel = new CourseModel($this->conn); // Initialize CourseModel
    }

    public function createUser($username, $role)
    {
        $stmt = $this->conn->prepare("INSERT INTO User (username, role) VALUES (?, ?)");
        $stmt->bind_param('ss', $username, $role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUser($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateUser($username, $role)
    {
        $stmt = $this->conn->prepare("UPDATE User SET role = ? WHERE username = ?");
        $stmt->bind_param('ss', $role, $username);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteUser($username)
    {
        $stmt = $this->conn->prepare("DELETE FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function addTeachingCourse($username, $term, $crn)
    {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO Teaches (username, term, crn) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssi', $username, $term, $crn);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Get courses taught by a user
    public function getTeachingCourses($username)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT c.* FROM Course c JOIN Teaches t ON c.term = t.term AND c.crn = t.crn WHERE t.username = ?");
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

    // Associate a user with a course for handling
    public function addHandlingCourse($username, $term, $crn)
    {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO Handles (username, term, crn) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssi', $username, $term, $crn);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Get courses handled by a user
    public function getHandlingCourses($username)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT c.* FROM Course c JOIN Handles h ON c.term = h.term AND c.crn = h.crn WHERE h.username = ?");
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

    public function validatePassword($username, $password)
    {
        // Prepare and execute the query to retrieve the password for the given username
        $stmt = mysqli_prepare($this->conn, "SELECT password FROM Users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashed_password);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Check if a hashed password was retrieved
        if (!$hashed_password) {
            // No password found for the given username
            echo "No password found for the username: " . $username;
            return false;
        }
        // Check if the retrieved hashed password matches the entered password
        if ($password === $hashed_password) {
            return true; // Password is valid
        } else {
            return false; // Password is invalid
        }
    }

    public function getCourseDetails($courseCode, $sectionCode)
    {
        // Get course details from the CourseModel
        return $this->courseModel->getCourse($courseCode);
    }

    // Method to get required documents and their corresponding requirement for a course
    // Method to get required documents and their corresponding requirement for a course
    public function getRequiredDocumentsForCourse($courseCode)
    {
        // Prepare the SQL query to fetch required documents and their corresponding requirements for the given course
        $sql = "SELECT cr.requirement_type, rd.document_type
            FROM bitirme.CourseRequirements cr
            JOIN bitirme.RequiredDocuments rd ON cr.requirement_type = rd.requirement_type
            WHERE cr.course_code = ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle the error if the statement preparation fails
            return false;
        }

        // Bind the course code parameter
        $stmt->bind_param('s', $courseCode);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Fetch the rows into an associative array
        $documents = [];
        while ($row = $result->fetch_assoc()) {
            // Add the requirement type and document type to the documents array
            $documents[] = [
                'requirement_type' => $row['requirement_type'],
                'document_type' => $row['document_type']
            ];
        }

        // Close the statement
        $stmt->close();

        // Return the fetched documents
        return $documents;
    }

    public function getSubmittedStatusForCourseDocuments($term, $courseCode)
    {
        // Prepare the SQL query to retrieve the submitted status for all documents for the given course and term
        $sql = "SELECT document_type, submitted FROM bitirme.Submit WHERE term = ? AND course_code = ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle the error if the statement preparation fails
            return false;
        }

        // Bind the parameters
        $stmt->bind_param('ss', $term, $courseCode);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Fetch the rows into an associative array
        $submittedDocuments = [];
        while ($row = $result->fetch_assoc()) {
            // Add the document type and submitted status to the array
            $submittedDocuments[$row['document_type']] = $row['submitted'];
        }

        // Close the statement
        $stmt->close();

        // Return the submitted status for all documents
        return $submittedDocuments;
    }
}
