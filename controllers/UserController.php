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
}
?>