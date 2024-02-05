<?php
// Include the database connection and controller
require_once 'database.php';
require_once 'controllers/CourseController.php';

// Instantiate the controller
$controller = new CourseController($conn);

// Parse the URL path
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple routing mechanism
if ($urlPath == '/bitirme/add_course') {
    $controller->createCourseView();
} else {
    // Handle other routes or display a default page
    echo "Welcome to the home page!";
    // You can include a default view or redirect to a specific page here
}
