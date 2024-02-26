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

    public function getCourseDetails($crn)
    {
        // Get course details from the CourseModel
        return $this->courseModel->getCourse($crn);
    }

    // Method to get required documents and their corresponding requirement for a course
    public function getRequiredDocumentsForCourse($crn, $term)
    {
        // Prepare the SQL query to fetch required documents and their corresponding requirements for the given course
        $sql = "SELECT cr.requirement_type, rd.document_type
            FROM bitirme.CourseRequirements cr
            JOIN bitirme.RequiredDocuments rd ON cr.requirement_type = rd.requirement_type
            WHERE cr.crn = ? AND cr.term = ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle the error if the statement preparation fails
            return false;
        }

        // Bind the course code parameter
        $stmt->bind_param('is', $crn, $term);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Initialize an empty array to store the documents
        $documents = [];

        // Fetch the rows into an associative array
        while ($row = $result->fetch_assoc()) {
            // Extract requirement type and document type from the row
            $requirementType = $row['requirement_type'];
            $documentType = $row['document_type'];

            // Check if the requirement type already exists as a key in the documents array
            if (!isset($documents[$requirementType])) {
                // If not, create a new array for the requirement type
                $documents[$requirementType] = [];
            }

            // Add the document type to the corresponding requirement type array
            $documents[$requirementType][] = $documentType;
        }

        // Close the statement
        $stmt->close();

        // Return the fetched documents
        return $documents;
    }

    public function getDocumentTypes()
    {
        // Prepare the SQL query to retrieve all document types
        $sql = "SELECT type FROM bitirme.Requirement";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle the error if the statement preparation fails
            return false;
        }

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Fetch the rows into an associative array
        $documentTypes = []; // Corrected variable name
        while ($row = $result->fetch_assoc()) {
            // Add the document type to the array
            $documentTypes[] = $row['type'];
        }

        // Close the statement
        $stmt->close();

        // Return the document types
        return $documentTypes; // Corrected return variable
    }



    public function getSubmittedStatusForCourseDocuments($term, $crn)
    {
        // Prepare the SQL query to retrieve the submitted status for all documents for the given course and term
        $sql = "SELECT document_type, submitted FROM bitirme.Submit WHERE term = ? AND crn = ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle the error if the statement preparation fails
            return false;
        }

        // Bind the parameters
        $stmt->bind_param('si', $term, $crn);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Initialize an array to store submitted status for each document type
        $submittedDocuments = [];

        // Iterate over the fetched rows
        while ($row = $result->fetch_assoc()) {
            // Extract document type and submitted status from the row
            $documentType = $row['document_type'];
            $submittedStatus = $row['submitted'];

            // Check if the document type already exists in the submitted documents array
            if (array_key_exists($documentType, $submittedDocuments)) {
                // Append the submitted status to the existing document type
                $submittedDocuments[$documentType] = $submittedStatus;
            } else {
                // Create a new entry for the document type
                $submittedDocuments[$documentType] = $submittedStatus;
            }
        }

        // Close the statement
        $stmt->close();

        // Return the submitted status for all documents
        return $submittedDocuments;
    }





    public function getTAsForCourse($crn, $term)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT U.username FROM User U JOIN Teaches T ON U.username = T.username JOIN Course C ON T.crn = C.crn WHERE T.crn = ? AND U.role = 'TA' AND C.term = ?");
        mysqli_stmt_bind_param($stmt, 'is', $crn, $term);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $tas[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $tas;
    }


    public function getTAsNotAssignedToCourse($crn, $term)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT U.username FROM User U WHERE U.role = 'TA' AND U.username NOT IN (SELECT T.username FROM Teaches T JOIN Course C ON T.crn = C.crn WHERE T.crn = ? AND C.term = ?)");
        mysqli_stmt_bind_param($stmt, 'is', $crn, $term);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $tas[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $tas;
    }

    public function removeTAFromCourse($username, $crn)
    {
        // Prepare the SQL statement to delete the row from the Teaches table
        $stmt = mysqli_prepare($this->conn, "DELETE FROM Teaches WHERE username = ? AND crn = ?");
        mysqli_stmt_bind_param($stmt, 'si', $username, $crn);

        // Execute the prepared statement
        $success = mysqli_stmt_execute($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return true if deletion was successful, false otherwise
        return $success;
    }

    public function addTAtoCourse($username, $crn)
    {
        // Prepare the SQL statement to insert a new row into the Teaches table
        $stmt = mysqli_prepare($this->conn, "INSERT INTO Teaches (username, crn) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'si', $username, $crn);

        // Execute the prepared statement
        $success = mysqli_stmt_execute($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return true if insertion was successful, false otherwise
        return $success;
    }

    // Function to check if a TA with the given username exists
    public function checkIfTAAvailableByUsername($username)
    {
        // Prepare the SQL statement to check if a TA with the given username exists
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) FROM User U WHERE username = ? AND  U.role = 'TA'");
        mysqli_stmt_bind_param($stmt, 's', $username);

        // Execute the prepared statement
        mysqli_stmt_execute($stmt);

        // Bind the result variable
        mysqli_stmt_bind_result($stmt, $count);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return true if count is greater than 0 (TA exists), false otherwise
        return $count > 0;
    }
}
