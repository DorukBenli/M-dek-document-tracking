<?php
// Include the database functions file
include '../helper.php';
include '../controllers/UserController.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if username is not set
if (!isset($_SESSION['username'])) {
    // Redirect to index.php
    header("Location: index.php");
    exit(); // Make sure to exit after redirection
}

if (isset($_SESSION['role']) && $_SESSION['role'] != 'argor') {
    if ($_SESSION['role'] == 'professor' || $_SESSION['role'] == 'TA') {
        header("Location: show.php");
    }
    exit();
}

// Assuming "Canberk" is the username of the professor
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} elseif (isset($_GET['username'])) {
    $username = $_GET['username'];
}

//$encodedCourseDetails = $_GET['course_details'] ?? '';
//$decodedCourseDetails = urldecode($encodedCourseDetails);
//$courseData = json_decode($decodedCourseDetails, true);

// Extract course details and requirements
$course = $_SESSION['course_details'];
//$requirements = $courseData['requirements'] ?? [];
//$status = $courseData['status'] ?? [];
//$softDocuments = $courseData['softDocuments'] ?? [];
$softDocuments = $_SESSION['softDocuments'];

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
} else {
    $role = getRole($username)["role"];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        header {
            height: 50px;
            /* Height of the header */
        }

        main {
            display: flex;
            flex-direction: column;
            height: calc(100% - 50px);
            /* Subtract header height from the viewport height */
        }

        .container {
            display: flex;
            flex-grow: 1;
            /* Allow container to grow and fill remaining space */
        }

        .hframe {
            flex: 1;
            /* Expand to fill available space */
            overflow: auto;
            /* Enable vertical scrolling if content exceeds height */
            border-right: 1px solid #ccc;
            /* Optional: Add border between frames */
            margin-bottom: 40px;
            /* Add margin to create space between hframe and card */

        }

        .course_card {
            margin-bottom: 400px;
        }
    </style>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <?php include_once 'header.php'; ?>
    </header>
    <main>
        <div class="container">
            <div class="hframe">
                <?php include 'hframe.php'; ?>
            </div>
        </div>
        <div class="course_card">
            <?php include 'course_details_card_argor.php'; ?>
        </div>
    </main>
</body>


</html>