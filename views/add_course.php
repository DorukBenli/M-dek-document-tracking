<?php
// Start the PHP session
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Include the database connection and the CourseController
include("database.php"); //idk including it like this fixed
require_once __DIR__ . '/../controllers/CourseController.php';


$courseController = new CourseController($conn); // $conn comes from database.php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $course_code = $_POST['course_code'] ?? '';
    $course_name = $_POST['course_name'] ?? '';
    $exam_count = $_POST['exam_count'] ?? '';
    $program_code = $_POST['program_code'] ?? '';
    $term = $_POST['term'] ?? '';
    $crn = $_POST['crn'] ?? '';
    $section_code = $_POST['section_code'] ?? '';

    // Perform validation and sanitization here

    // Call the createCourse method from the controller
    $result = $courseController->createCourse($course_code, $course_name, $exam_count, $program_code, $term, $crn, $section_code);

    // Handle the result of the operation
    if ($result) {
        // Success
        $_SESSION['message'] = "Course added successfully!";
    } else {
        // Error
        $_SESSION['error_message'] = "Failed to add course.";
    }

    // Redirect or include a view to show success/error message
    header("Location: add_course.php");
    exit();
}

// Include the form view
include 'course_form.php';
