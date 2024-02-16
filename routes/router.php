<?php
require_once '../controllers/UserController.php';
require_once '../controllers/SubmitController.php';
require_once '../database.php'; // Include the database connection details
require_once '../helper.php';
session_start(); // Start the session if not already started

// Get the requested action from the URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Create an instance of the UserController
$userController = new UserController($conn);
$submitController = new SubmitController($conn);

// Route requests to controller methods based on the action
switch ($action) {
    case 'login':
        // Check if username and password are provided in the request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['logintype'])) {
            // Retrieve username and password from the POST data
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role=$_POST['logintype'];

            // Call the loginUser method with the provided username and password
            $userController->loginUser($username, $password, $role);
        } else {
            // If username or password is not provided, handle the error appropriately
            echo "Username and password are required.";
        }
        break;
    case 'logout':
        // Call the logoutUser method to log the user out
        $userController->logoutUser();
        break;
    case 'showcourse':
        // Check if course_code and section_code are provided in the URL parameters
        if (isset($_GET['course_code']) && isset($_GET['section_code'])) {
            // Retrieve course_code and section_code from the URL parameters
            $courseCode = $_GET['course_code'];
            $sectionCode = $_GET['section_code'];

            // Call the method to show the course page with the provided codes
            // Adjust this method name and parameters according to your application logic
            $userController->showCoursePage($courseCode, $sectionCode);
        } else {
            // If course_code or section_code is not provided, handle the error appropriately
            echo "Course code and section code are required.";
        }
        break;
    case 'uploadFile':
        // Check if the action is uploadFile
        if ($_GET['action'] === 'uploadFile') {
            // Extract the document type from the form data
            $documentType = $_POST['document_type'];

            // Check if a file was uploaded
            if ($_FILES['file_' . $documentType]['error'] === UPLOAD_ERR_OK) {
                // File was uploaded successfully
                $fileName = $_FILES['file_' . $documentType]['name'];
                $fileTmpPath = $_FILES['file_' . $documentType]['tmp_name'];
                $term = $_POST['term'];
                $courseCode = $_POST['course_code'];
                $sectionCode = $_POST['section_code'];

                // Read the file content
                $pdfData = file_get_contents($fileTmpPath);

                $result = $submitController->updateDocument($term, $courseCode, $documentType, $pdfData);

                if ($result) {
                    // Redirect to the course page
                    $courseDetails = $userController->getCourseDetails($courseCode, $sectionCode);
                    $requirements = $userController->getRequiredDocumentsForCourse($courseCode);
                    $status = $userController->getSubmittedStatusForCourseDocuments($term, $courseCode);

                    $encodedCourseDetails = json_encode(['courseDetails' => $courseDetails, 'requirements' => $requirements, 'status' => $status]);

                    // Encode the JSON string for URL
                    $encodedCourseDetails = urlencode($encodedCourseDetails);

                    // Construct the URL with encoded course details as a parameter
                    $url = "../views/course.php?course_details=$encodedCourseDetails";

                    // Redirect to the constructed URL
                    header("Location: $url");
                    exit; // Stop script execution after redirecting
                }
            } else {
                // File upload failed, handle the error
                echo "File upload failed for document type: $documentType. Error code: {$_FILES['file_' .$documentType]['error']}";
            }
        }
        break;
    case 'removeFile':
        // Handle file removal action
        // You can implement the file removal logic here
        if ($_GET['action'] === 'removeFile') {
            $documentType = $_POST['document_type'];
            $term = $_POST['term'];
            $courseCode = $_POST['course_code'];
            $sectionCode = $_POST['section_code'];
            // Ensure to securely delete the file from the database or filesystem
            // Redirect back to the course page after handling the removal

            $result = $submitController->removeDocument($term, $courseCode, $documentType);

            if ($result) {
                // Redirect to the course page
                $courseDetails = $userController->getCourseDetails($courseCode, $sectionCode);
                $requirements = $userController->getRequiredDocumentsForCourse($courseCode);
                $status = $userController->getSubmittedStatusForCourseDocuments($term, $courseCode);

                $encodedCourseDetails = json_encode(['courseDetails' => $courseDetails, 'requirements' => $requirements, 'status' => $status]);

                // Encode the JSON string for URL
                $encodedCourseDetails = urlencode($encodedCourseDetails);

                // Construct the URL with encoded course details as a parameter
                $url = "../views/course.php?course_details=$encodedCourseDetails";

                // Redirect to the constructed URL
                header("Location: $url");
                exit; // Stop script execution after redirecting
            }
        }
        break;
    case 'downloadFile':
        // Check if the action is downloadFile
        if ($_GET['action'] === 'downloadFile') {
            // Extract parameters from the URL
            $term = $_GET['term'];
            $courseCode = $_GET['course_code'];
            $documentType = $_GET['document_type'];

            // Perform any necessary validation or authentication checks here

            // Get the PDF data from the database based on the provided parameters
            // Assuming you have a method to fetch PDF data from the database in your model
            $pdfData = $submitController->getPDFData($term, $courseCode, $documentType);

            // Check if PDF data was retrieved successfully
            if ($pdfData) {
                // Set the appropriate headers for PDF file download
                header('Content-Type: application/pdf');
                // Set filename based on course code, term, and document type
                $filename = $courseCode . '_' . $term . '_' . $documentType . '.pdf';
                header("Content-Disposition: attachment; filename=\"$filename\"");

                // Output the PDF data to the browser
                echo $pdfData;

                // Stop script execution after sending the file
                exit;
            } else {
                // Handle the case where PDF data retrieval fails
                echo "Failed to retrieve PDF data.";
                exit; // Stop script execution
            }
        }
        break;
    case 'store_term_session':
        // Check if the term parameter is set in the URL
        if (isset($_GET['term'])) {
            // Retrieve the selected term from the URL
            $selectedTerm = $_GET['term'];

            // Store the selected term in a session variable
            $_SESSION['selected_term'] = $selectedTerm;

            // Redirect the user to the show.php page
            header("Location: ../views/show.php");
            exit; // Stop script execution after redirecting
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hframe_term']) ) {
            // Retrieve the selected term from the URL
            $selectedTerm = $_POST['hframe_term'];

            // Store the selected term in a session variable
            $_SESSION['selected_term'] = $selectedTerm;

            // Redirect the user to the show.php page
            header("Location: ../views/show.php");
        } else {
            // Handle the case where the term parameter is not set in the URL
            echo "Error: Term parameter not found in URL.";
            exit; // Stop script execution
        }
        break;

    default:
        // Handle unknown routes
        echo '404 Not Found';
        break;
}
