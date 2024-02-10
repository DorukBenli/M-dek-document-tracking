<?php
require_once '../models/UserModel.php';
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

    public function loginUser($username, $password)
    {
        // Validate the password
        $isValid = $this->userModel->validatePassword($username, $password);

        if ($isValid) {
            // Password is valid, set session variables
            session_start();
            $_SESSION['username'] = $username;

            // Redirect to show.php or any other desired page
            header("Location: ../views/show.php");
            exit;
        } else {
            // Password is invalid, return error message or redirect to login page
            // For simplicity, let's redirect to index.php
            header("Location: ../views/index.php");
            exit;
        }
    }
}
