<?php
require_once 'models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct($dbConnection) {
        $this->userModel = new UserModel($dbConnection);
    }

    public function createUser($username, $role) {
        return $this->userModel->createUser($username, $role);
    }

    public function getUser($username) {
        $user = $this->userModel->getUser($username);
        // You can render the view here or return the user data as needed
        return $user;
    }

    public function updateUser($username, $role) {
        return $this->userModel->updateUser($username, $role);
    }

    public function deleteUser($username) {
        return $this->userModel->deleteUser($username);
    }

    public function createUserView() {
        // Include the "User Create" view
        include 'views/user_create.php';
    }

    // Associate a user with a course
    public function associateUserWithCourse($username, $courseCode) {
        $result = $this->userModel->addTeachingCourse($username, $courseCode);
        if ($result) {
            // Success: Return a success response
            echo json_encode(["message" => "User associated with the course (teach) successfully"]);
        } else {
            // Error: Return an error response
            echo json_encode(["message" => "Failed to associate user with the course (teach)"]);
        }
    }

    // Get courses taught by a user
    public function getCoursesTaughtByUser($username) {
        $courses = $this->userModel->getTeachingCourses($username);
        if (!empty($courses)) {
            // Success: Return the courses as a JSON response
            echo json_encode($courses);
        } else {
            // No courses found: Return an empty response or an error message
            echo json_encode(["message" => "No courses are taught by this user"]);
        }
    }

    // Associate a user with a course
    public function associateUserWithCourseHandle($username, $courseCode) {
        $result = $this->userModel->addHandlingCourse($username, $courseCode);
        if ($result) {
            // Success: Return a success response
            echo json_encode(["message" => "User associated with the course (handle) successfully"]);
        } else {
            // Error: Return an error response
            echo json_encode(["message" => "Failed to associate user with the course (handle)"]);
        }
    }

    // Get courses taught by a user
    public function getCoursesHandledByUser($username) {
        $courses = $this->userModel->getHandlingCourses($username);
        if (!empty($courses)) {
            // Success: Return the courses as a JSON response
            echo json_encode($courses);
        } else {
            // No courses found: Return an empty response or an error message
            echo json_encode(["message" => "No courses are handled by this user"]);
        }
    }
}
?>