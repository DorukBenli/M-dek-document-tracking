<?php
include '../helper.php';
include 'header.php';
include 'hframe.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['selected_term']=getTerm()["term"];
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}
else {
    $username = null;
}
$search = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
}

// Fetch courses from the database
$query = "SELECT * FROM course WHERE course_name LIKE '%{$search}%' OR course_code LIKE '%{$search}%'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add link to your CSS file -->
</head>
<body>
    <div class="upload-link-container">
        <button onclick="location.href='../views/admin_upload.php';">Go to Upload Page</button>
    </div>

    <div class="search-container">
        <form action="" method="post">
            <input type="text" placeholder="Search for courses..." name="search" value="<?php echo $search; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="courses-container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="course-card">
                <h3><?php echo $row['course_name']; ?></h3>
                <p>Code: <?php echo $row['course_code']; ?></p>
                <!-- Add more course details you want to show -->
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>