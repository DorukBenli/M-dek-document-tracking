<?php
require_once '../controllers/UserController.php';
require_once '../controllers/SubmitController.php';
require_once '../controllers/DocumentController.php';
require_once '../database.php'; // Include the database connection details
require_once '../helper.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get the requested action from the URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Create an instance of the UserController
$userController = new UserController($conn);
$submitController = new SubmitController($conn);
$documentController = new DocumentController($conn);

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
    case 'logout':
        // Call the logoutUser method to log the user out
        $userController->logoutUser();
        break;
    case 'showcourse':
        // Check if course_code and section_code are provided in the URL parameters
        if (isset($_GET['crn']) && isset($_GET['term'])) {
            // Retrieve course_code and section_code from the URL parameters
            $crn = $_GET['crn'];
            $term = $_GET['term'];

            // Call the method to show the course page with the provided codes
            // Adjust this method name and parameters according to your application logic
            $userController->showCoursePage($crn, $term);
        } else {
            // If course_code or section_code is not provided, handle the error appropriately
            echo "crn and term are required.";
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
                $crn = $_POST['crn'];
                $courseCode = $_POST['course_code'];
                $sectionCode = $_POST['section_code'];

                // Read the file content
                $pdfData = file_get_contents($fileTmpPath);

                // Extract the document type from the form data and replace hyphens with spaces
                $document = str_replace('_', ' ', $documentType);

                $result = $submitController->updateDocument($term, $crn, $document, $pdfData);

                if ($result) {
                    $userController->showCoursePage($crn, $term);
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
            $crn = $_POST['crn'];
            $courseCode = $_POST['course_code'];
            $sectionCode = $_POST['section_code'];
            // Ensure to securely delete the file from the database or filesystem
            // Redirect back to the course page after handling the removal


            $result = $submitController->removeDocument($term, $crn, $documentType);

            if ($result) {
                $userController->showCoursePage($crn, $term);
                exit; // Stop script execution after redirecting
            }
        }
        break;
    case 'downloadFile':
        // Check if the action is downloadFile
        if ($_GET['action'] === 'downloadFile') {
            // Extract parameters from the URL
            $term = $_GET['term'];
            $crn = $_GET['crn'];
            $documentType = $_GET['document_type'];

            $document = str_replace('_', ' ', $documentType);


            // Perform any necessary validation or authentication checks here

            // Get the PDF data from the database based on the provided parameters
            // Assuming you have a method to fetch PDF data from the database in your model
            $pdfData = $submitController->getPDFData($term, $crn, $document);

            // Check if PDF data was retrieved successfully
            if ($pdfData) {
                // Set the appropriate headers for PDF file download
                header('Content-Type: application/pdf');
                // Set filename based on course code, term, and document type
                $filename = $crn . '_' . $term . '_' . $documentType . '.pdf';
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
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hframe_term'])) {
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
    case 'addTA':
        // Check if the necessary POST data is received
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['term']) && isset($_POST['course_code']) && isset($_POST['section_code']) && isset($_POST['taUsername']) && isset($_POST['crn'])) {
            // Retrieve the data from the form
            $term = $_POST['term'];
            $crn = $_POST['crn'];
            $course_code = $_POST['course_code'];
            $section_code = $_POST['section_code'];
            $taUsername = $_POST['taUsername'];

            // Get the array of TAs for the course and term
            $tas = $userController->getTAs($crn, $term);

            // Check if the TA already exists for the course
            $taExistsForCourse = false;
            foreach ($tas as $ta) {
                if ($ta['username'] == $taUsername) {
                    $taExistsForCourse = true;
                    break;
                }
            }
            // Check if the TA exists in the database
            $taExistsInDatabase = $userController->checkIfTAAvailableByUsername($taUsername);

            // If TA already exists for the course, return an error message
            if ($taExistsForCourse) {
                $_SESSION['error'] = "TA is already assigned to the course.";
            }
            // If TA doesn't exist in the database, return an error message
            elseif (!$taExistsInDatabase) {
                $_SESSION['error'] = "TA does not exist in the database.";
            } else {

                $result = $userController->addTAtoCourse($taUsername, $crn);

                if ($result) {
                    // Redirect the user to the appropriate page after adding TA
                    $_SESSION['success'] = "TA is assigned to the course.";
                    $userController->showCoursePage($crn, $term);
                } else {
                    $_SESSION['error'] = "TA assignment failed.";
                    $userController->showCoursePage($crn, $term);
                }
                exit;
            }

            // Redirect back to the form page
            $userController->showCoursePage($crn, $term);
            exit; // Stop script execution after redirecting
        } else {
            // Handle the case where the required POST data is missing
            $_SESSION['error'] = "Missing data for adding TA.";
            $userController->showCoursePage($crn, $term);
            exit; // Stop script execution after redirecting
        }
        break;
    case 'removeTA':
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['term']) && isset($_POST['course_code']) && isset($_POST['section_code']) && isset($_POST['username']) && isset($_POST['crn'])) {
            // Retrieve the data from the form
            $term = $_POST['term'];
            $crn = $_POST['crn'];
            $course_code = $_POST['course_code'];
            $section_code = $_POST['section_code'];
            $username = $_POST['username'];

            $result = $userController->removeTAFromCourse($username, $crn);

            if ($result) {
                $_SESSION['success'] = "TA is removed from the course.";
                $userController->showCoursePage($crn, $term);
                exit; // Stop script execution after redirecting
            }
        } else {
            // Handle the case where the required POST data is missing
            echo "Error: Missing data for removing TA.";
            exit; // Stop script execution
        }

        break;
    case 'removeExam':
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['term']) && isset($_POST['course_code']) && isset($_POST['section_code']) && isset($_POST['document_type']) && isset($_POST['crn'])) {
            $term = $_POST['term'];
            $course_code = $_POST['course_code'];
            $section_code = $_POST['section_code'];
            $document_type = $_POST['document_type'];
            $crn = $_POST['crn'];

            $document = str_replace('_', ' ', $document_type);

            $result = $submitController->removeExamFromCourse($term, $crn, $document);

            if ($result) {
                $userController->showCoursePage($crn, $term);
                exit; // Stop script execution after redirecting
            } else {
                echo "Failed remove operation";
            }
        } else {
            // Handle the case where the required POST data is missing
            echo "Error: Missing data for removing $document.";
            exit; // Stop script execution
        }
        break;
    case 'addExam':
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['term']) && isset($_POST['crn']) && isset($_POST['exam'])) {
            $term = $_POST['term'];
            $crn = $_POST['crn'];
            $exam = $_POST['exam'];

            // Check if the document exists
            $documentExists = $documentController->getDocument($exam);

            if (!$documentExists) {

                // Array of document types
                $documentTypes = array(
                    $exam,
                    "$exam Attendance",
                    "$exam Answer Key",
                    "Best $exam",
                    "Average $exam",
                    "Worst $exam"
                );

                $documentController->add($documentTypes);
                $documentController->addRequiredDocuments($documentTypes);
            }

            $result = $submitController->add($term, $crn, $exam);
            if ($result) {
                $userController->showCoursePage($crn, $term);
                exit; // Stop script execution after redirecting
            } else {
                echo "Failed add operation";
            }
        }


    default:
        // Handle unknown routes
        echo '404 Not Found';
        break;
}
