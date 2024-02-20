<?php

include('database.php');
include('controllers/UserController.php');
require_once __DIR__ .'../helper.php';

$userController = new UserController($conn);

$username = 'doruk';
$crn = '12345';

// Call the function
$requirements  = $userController->getCourseRequirementsByCrn($username, $crn);
if (is_array($requirements)) {
    echo "Requirements for course with CRN $crn:";
    foreach ($requirements as $requirement) {
        echo "<li>$requirement</li>";
    }
} else {
    // If the return value is not an array, it's an error message.
    echo $requirements;
}

?>
