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
    </style>
</head>

<body>
    <header>
        <?php include_once 'header.php'; ?>
    </header>
    <main>
        <div class="content">
            <?php include 'login.php'; ?>
        </div>
    </main>
</body>

</html>