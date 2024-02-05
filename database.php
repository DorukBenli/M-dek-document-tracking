<?php
    $db_server = "localhost";
    $db_user = "ens491";
    $db_pass = "1234";
    $db_name = "ens491";

    // Attempt to connect to the MySQL database
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    // Check connection
    if (!$conn) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
?>
