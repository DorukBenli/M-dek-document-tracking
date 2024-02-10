<?php
require_once '../controllers/UserController.php';
require_once '../database.php'; // Include the database connection details
require_once '../helper.php';


// Get the requested action from the URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Create an instance of the UserController
$userController = new UserController($conn);

// Route requests to controller methods based on the action
switch ($action) {
    case 'login':
        // Check if username and password are provided in the request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
            // Retrieve username and password from the POST data
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Call the loginUser method with the provided username and password
            $userController->loginUser($username, $password);
        } else {
            // If username or password is not provided, handle the error appropriately
            echo "Username and password are required.";
        }
        break;
    default:
        // Handle unknown routes
        echo '404 Not Found';
        break;
}
?>
