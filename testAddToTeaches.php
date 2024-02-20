<?php 
require_once __DIR__ . '../database.php';
include('controllers/UserController.php');

$username = 'doruk';
$controller = new UserController($conn);
$crn = 12345;
$result = $controller->addToTeaches($username, $crn);
echo $result;
?>