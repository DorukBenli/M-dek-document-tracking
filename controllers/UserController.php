<?php
require_once '../models/UserModel.php';
require_once '../models/CourseModel.php';
require_once '../database.php'; // Include the database connection details
require_once '../helper.php';

class UserController
{
    private $userModel;
    private $conn; // Add this property to store the connection

    public function __construct($conn)
    {
        $this->conn = $conn; // Store the connection
        $this->userModel = new UserModel($conn);
    }

    public function createUser($username, $role)
    {
        return $this->userModel->createUser($username, $role);
    }

    public function getUser($username)
    {
        $user = $this->userModel->getUser($username);
        // You can render the view here or return the user data as needed
        return $user;
    }

    public function updateUser($username, $role)
    {
        return $this->userModel->updateUser($username, $role);
    }

    public function deleteUser($username)
    {
        return $this->userModel->deleteUser($username);
    }

    public function createUserView()
    {
        // Include the "User Create" view
        include '../views/user_create.php';
    }

    public function associateUserWithTeachingCourse($username, $term, $crn)
    {
        $result = $this->userModel->addTeachingCourse($username, $term, $crn);
        if ($result) {
            echo json_encode(["message" => "User associated with the course for teaching successfully"]);
        } else {
            echo json_encode(["message" => "Failed to associate user with the course for teaching"]);
        }
    }

    public function getCoursesTaughtByUser($username)
    {
        $courses = $this->userModel->getTeachingCourses($username);
        if (!empty($courses)) {
            echo json_encode($courses);
        } else {
            echo json_encode(["message" => "No courses are taught by this user"]);
        }
    }

    public function associateUserWithHandlingCourse($username, $term, $crn)
    {
        $result = $this->userModel->addHandlingCourse($username, $term, $crn);
        if ($result) {
            echo json_encode(["message" => "User associated with the course for handling successfully"]);
        } else {
            echo json_encode(["message" => "Failed to associate user with the course for handling"]);
        }
    }

    public function getCoursesHandledByUser($username)
    {
        $courses = $this->userModel->getHandlingCourses($username);
        if (!empty($courses)) {
            echo json_encode($courses);
        } else {
            echo json_encode(["message" => "No courses are handled by this user"]);
        }
    }

    public function loginUser($username, $password, $role)
    {
        // Validate the password
        $isValid = $this->userModel->validatePassword($username, $password);

        // Retrieve the true role from the database
        $trueRole = strtolower($this->getUser($username)['role']);

        // Convert the provided role to lowercase
        $role = strtolower($role);


        if ($isValid && $trueRole==$role) {
            // Password is valid, set session variables
            session_start();
            $_SESSION['username'] = $username;

            // Redirect to show.php or any other desired page
            header("Location: ../views/show.php");
            exit;
        } else {
            /// Password or username is invalid, notify the user
            $error_message = "Invalid username or password or role. Please try again.";
            // Set error message in session to display it on the login page
            session_start();
            $_SESSION['error_message'] = $error_message;

            // Redirect to login page
            header("Location: ../views/index.php");
            exit;
        }
    }

    public function logoutUser()
    {
        // Start the session
        session_start();

        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        header("Location: ../views/index.php");
        exit;
    }

    public function getCourseDetails($courseCode, $sectionCode)
    {
        $courseDetails = $this->userModel->getCourseDetails($courseCode, $sectionCode);
        return $courseDetails;
    }

    public function getSubmittedStatusForCourseDocuments($term, $courseCode)
    {
        $status = $this->userModel->getSubmittedStatusForCourseDocuments($term, $courseCode);
        return $status;
    }



    public function showCoursePage($courseCode, $sectionCode)
    {
        // Fetch course details and requirements from the database
        $courseDetails = $this->userModel->getCourseDetails($courseCode, $sectionCode);
        $requirements = $this->userModel->getRequiredDocumentsForCourse($courseCode);
        $status = $this->userModel->getSubmittedStatusForCourseDocuments($courseDetails['term'], $courseCode);

        if ($courseDetails) {
            // JSON encode the course details and requirements
            $encodedCourseDetails = json_encode(['courseDetails' => $courseDetails, 'requirements' => $requirements, 'status' => $status]);

            // Encode the JSON string for URL
            $encodedCourseDetails = urlencode($encodedCourseDetails);

            // Construct the URL with encoded course details as a parameter
            $url = "../views/course.php?course_details=$encodedCourseDetails";

            // Redirect to the course page with course details and requirements as parameters
            header("Location: $url");
            exit;
        } else {
            // If course details are not found, display an error message or redirect to an error page
            echo "Course details not found.";
        }
    }

    // Method to get required documents for a course
    public function getRequiredDocumentsForCourse($courseCode)
    {
        // Call the UserModel's method to get required documents
        return $this->userModel->getRequiredDocumentsForCourse($courseCode);
    }
}
