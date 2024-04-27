<?php
include_once '../helper.php';
// Start session to check for error messages
session_start();
$_SESSION['selected_term']=getTerm()["term"];

// Unset $_SESSION['username'] if it's set
if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Other head elements -->
    <title>Web for MUDEK Document Tracking System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        header {
            height: 55px;
            /* Height of the header */
        }

        .content {
            position: absolute;
            top: 100px;
            /* Adjust top position as needed */
            left: 50%;
            /* Align to center horizontally */
            transform: translateX(-50%);
            /* Adjust for center alignment */
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <header>
        <?php include_once 'header.php'; ?>
    </header>
    <main>
        <div class="content">
            <?php include 'login.php'; ?>
            <?php
            // Check if there is an error message in the session
            if (isset($_SESSION['error_message'])) {
                echo '<div class="error">' . $_SESSION['error_message'] . '</div>';
                // Unset the error message from the session
                unset($_SESSION['error_message']);
            }
            ?>
        </div>
    </main>
</body>

</html>