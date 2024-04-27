<?php
include("database.php");

// Create (Insert) --> will be replaced by CAS API
function createUser($username, $role)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO User (username, role) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $username, $role);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Read (Select)
function getUser($username)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM User WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $user;
}

function getRole($username)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT role FROM User WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $role = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $role;
}

// Update
function updateUser($username, $role)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE User SET role = ? WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $role, $username);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Delete
function deleteUser($username)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM User WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Create (Insert) Course
function createCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO Course (course_code, course_name, exam_count, program_code, term, crn, section_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssissss', $course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Read (Select) Course
function getCourse($crn, $term)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM Course WHERE crn = ? AND term = ?");
    mysqli_stmt_bind_param($stmt, 'is', $crn, $term);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $course = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $course;
}

// Update Course
function updateCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE Course SET course_name = ?, exam_count = ?, program_code = ?, term = ?, crn = ?, section_code = ? WHERE course_code = ?");
    mysqli_stmt_bind_param($stmt, 'sisssss', $course_name, $exam_count, $program_code, $term, $crn, $section_code, $course_code);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Delete Course
function deleteCourse($course_code)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM Course WHERE course_code = ?");
    mysqli_stmt_bind_param($stmt, 's', $course_code);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Create (Insert) Teaches relationship
function createTeachesRelationship($username, $crn)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO Teaches (username, crn) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'si', $username, $crn);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Read (Select) Teaches relationship
function getTeachesRelationship($username)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM Teaches WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    if (!$stmt) {
        return false;
    }
    $result = mysqli_stmt_get_result($stmt);
    $teaches = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $teaches[] = $row;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $teaches;
}

// Read (Select) Teaches relationship
function getHandlesRelationship($username)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM Handles WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    if (!$stmt) {
        return false;
    }
    $result = mysqli_stmt_get_result($stmt);
    $teaches = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $teaches[] = $row;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $teaches;
}


// Delete Teaches relationship
function deleteTeachesRelationship($username, $crn)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM Teaches WHERE username = ? AND crn = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $username, $course_code);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function getProfessorsTeachingSameCourseInSection($crn, $section_code)
{
    global $conn; // Assuming $conn is your database connection object

    // Prepare the SQL query to select distinct usernames of professors teaching the same course at the same section
    $sql = "SELECT DISTINCT T.username 
            FROM Teaches AS T
            JOIN Course AS C ON T.crn = C.crn
            WHERE T.crn = ? AND C.section_code = ?";

    // Prepare and bind parameters to the SQL statement
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'is', $crn, $section_code);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Bind the result variables
    mysqli_stmt_bind_result($stmt, $username);

    // Initialize an array to store the usernames of professors
    $professors = array();

    // Fetch the results into the array
    while (mysqli_stmt_fetch($stmt)) {
        $professors[] = $username;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Return the array of professor usernames
    return $professors;
}

function validatePassword($username, $password)
{
    global $conn;

    // Prepare and execute the query to retrieve the password for the given username
    $stmt = mysqli_prepare($conn, "SELECT password FROM Users WHERE username = ?");
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
    if (strcasecmp($password, $hashed_password) === 0) {
        return true; // Password is valid
    } else {
        return false; // Password is invalid
    }
}

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}

// Read (Select) latest term
function getTerm()
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT term FROM Course LIMIT 1");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $term = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $term;
}

function getName($term, $crn, $document) {
    global $conn;

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "SELECT name FROM bitirme.Submit WHERE term = ? AND crn = ? AND document_type = ?");

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, 'sis', $term, $crn, $document);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $name = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $name["name"];
}


function getTAs($crn, $term)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT U.username FROM User U JOIN Teaches T ON U.username = T.username JOIN Course C ON T.crn = C.crn WHERE T.crn = ? AND U.role = 'TA' AND C.term = ?");
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

function getNumMidtermsForCourse($term, $crn)
{
    global $conn;

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS num_midterms FROM bitirme.Submit WHERE term = ? AND crn = ? AND document_type LIKE 'Midterm %' AND (SUBSTRING(document_type, -1) REGEXP '^[0-9]+$' OR SUBSTRING(document_type, -2) REGEXP '^[0-9]+$')");

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the result (assuming you want to fetch the data, adjust as needed)
    $row = mysqli_fetch_assoc($result);
    $numMidterms = ($row['num_midterms'] !== null) ? $row['num_midterms'] : 0;

    // Free the result and close the statement
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    // Return the result (assuming you want to return something, adjust as needed)
    return $numMidterms;
}

function getNumHWsForCourse($term, $crn)
{
    global $conn;

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS num_hws FROM bitirme.Submit WHERE term = ? AND crn = ? AND document_type LIKE 'Take Home Exam %' AND (SUBSTRING(document_type, -1) REGEXP '^[0-9]+$' OR SUBSTRING(document_type, -2) REGEXP '^[0-9]+$')");

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the result (assuming you want to fetch the data, adjust as needed)
    $row = mysqli_fetch_assoc($result);
    $numHws = ($row['num_hws'] !== null) ? $row['num_hws'] : 0;

    // Free the result and close the statement
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    // Return the result (assuming you want to return something, adjust as needed)
    return $numHws;
}

function getNumQuizzesForCourse($term, $crn)
{
    global $conn;

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS num_quizzes FROM bitirme.Submit WHERE term = ? AND crn = ? AND document_type LIKE 'Quiz %' AND (SUBSTRING(document_type, -1) REGEXP '^[0-9]+$' OR SUBSTRING(document_type, -2) REGEXP '^[0-9]+$')");

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the result (assuming you want to fetch the data, adjust as needed)
    $row = mysqli_fetch_assoc($result);
    $numQuizzes = ($row['num_quizzes'] !== null) ? $row['num_quizzes'] : 0;

    // Free the result and close the statement
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    // Return the result (assuming you want to return something, adjust as needed)
    return $numQuizzes;
}

function getNumOthersForCourse($term, $crn)
{
    global $conn;

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS num_others FROM bitirme.Submit WHERE term = ? AND crn = ? AND document_type LIKE 'Other %' AND (SUBSTRING(document_type, -1) REGEXP '^[0-9]+$' OR SUBSTRING(document_type, -2) REGEXP '^[0-9]+$')");

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the result (assuming you want to fetch the data, adjust as needed)
    $row = mysqli_fetch_assoc($result);
    $numQuizzes = ($row['num_others'] !== null) ? $row['num_others'] : 0;

    // Free the result and close the statement
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    // Return the result (assuming you want to return something, adjust as needed)
    return $numQuizzes;
}

// Define a function to check if a course has a final exam
function hasFinalExam($term, $crn)
{
    global $conn; // Assuming $conn is your database connection

    // Prepare and execute the SQL query to check for a final exam
    $sql = "SELECT COUNT(*) AS num_final FROM bitirme.Submit WHERE term = ? AND crn = ? AND document_type = 'Final'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $term, $crn);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $numFinal);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Return true if the course has a final exam, false otherwise
    return ($numFinal > 0);
}

function isSoft($document)
{
    global $conn;

    // Prepare and execute the SQL query to check for a final exam
    $sql = "SELECT soft FROM bitirme.documents WHERE type = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $document);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $soft);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Return true if the course has a final exam, false otherwise
    return $soft == 1;
}

function checkArgor($term, $crn, $document)
{
    global $conn;

    // Prepare and execute the SQL query to check for a final exam
    $sql = "SELECT submitted_arg FROM bitirme.soft_submit WHERE term = ? AND crn = ? AND document_type = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sis', $term, $crn, $document);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $profsent);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Return true if the course has a final exam, false otherwise
    return $profsent == 1;
}


function isnotsoftSubmit($term, $crn, $document)
{
    global $conn;

    // Prepare and execute the SQL query to check for a final exam
    $sql = "SELECT count(*) FROM bitirme.soft_submit WHERE term = ? AND crn = ? AND document_type = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sis', $term, $crn, $document);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $soft);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Return true if the course has a final exam, false otherwise
    return $soft == 0;
}

function isSubmit($term, $crn, $document)
{
    global $conn;

    // Prepare and execute the SQL query to check for a final exam
    $sql = "SELECT count(*) FROM bitirme.submit WHERE term = ? AND crn = ? AND document_type = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sis', $term, $crn, $document);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $makeup);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Return true if the course has a final exam, false otherwise
    return $makeup == 1;
}
