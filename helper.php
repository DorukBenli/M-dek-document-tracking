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
function getCourse($course_code, $term)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM Course WHERE course_code = ? AND term = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $course_code, $term);
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
function createTeachesRelationship($username, $course_code)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO Teaches (username, course_code) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $username, $course_code);
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


// Delete Teaches relationship
function deleteTeachesRelationship($username, $course_code)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM Teaches WHERE username = ? AND course_code = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $username, $course_code);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function getProfessorsTeachingSameCourseInSection($course_code, $section_code) {
    global $conn; // Assuming $conn is your database connection object
    
    // Prepare the SQL query to select distinct usernames of professors teaching the same course at the same section
    $sql = "SELECT DISTINCT T.username 
            FROM Teaches AS T
            JOIN Course AS C ON T.course_code = C.course_code
            WHERE T.course_code = ? AND C.section_code = ?";
    
    // Prepare and bind parameters to the SQL statement
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $course_code, $section_code);
    
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

function validatePassword($username, $password) {
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

