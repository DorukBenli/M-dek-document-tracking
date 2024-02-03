<?php
    include("database.php");
    // Create (Insert) --> will be replaced by cas API
    function createUser($username, $role) {
        global $conn;
        $stmt = mysqli_prepare($conn, "INSERT INTO User (username, role) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $username, $role);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Read (Select)
    function getUser($username) {
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
    function updateUser($username, $role) {
        global $conn;
        $stmt = mysqli_prepare($conn, "UPDATE User SET role = ? WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $role, $username);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Delete
    function deleteUser($username) {
        global $conn;
        $stmt = mysqli_prepare($conn, "DELETE FROM User WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

        // Create (Insert)
    function createCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code) {
        global $conn;
        $stmt = mysqli_prepare($conn, "INSERT INTO Course (course_code, course_name, exam_count, program_code, term, crn, section_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssissss', $course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Read (Select)
    function getCourse($course_code) {
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT * FROM Course WHERE course_code = ?");
        mysqli_stmt_bind_param($stmt, 's', $course_code);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $course = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $course;
    }

    // Update
    function updateCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code) {
        global $conn;
        $stmt = mysqli_prepare($conn, "UPDATE Course SET course_name = ?, exam_count = ?, program_code = ?, term = ?, crn = ?, section_code = ? WHERE course_code = ?");
        mysqli_stmt_bind_param($stmt, 'sisssss', $course_name, $exam_count, $program_code, $term, $crn, $section_code, $course_code);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Delete
    function deleteCourse($course_code) {
        global $conn;
        $stmt = mysqli_prepare($conn, "DELETE FROM Course WHERE course_code = ?");
        mysqli_stmt_bind_param($stmt, 's', $course_code);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    

    $res = createUser("Doruk Benli","Argor");
    echo "{$res}";

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    hello
</body>
</html>