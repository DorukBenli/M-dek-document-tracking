<?php
// Include the database functions file
include '../helper.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Check if username is not set
if (!isset($_SESSION['username'])) {
    // Redirect to index.php
    header("Location: index.php");
    exit(); // Make sure to exit after redirection
}

if (isset($_SESSION['role']) && $_SESSION['role'] != 'TA' && $_SESSION['role'] != 'professor') {
    if ($_SESSION['role'] == 'argor') {
        header("Location: show-arg.php");
    }
    exit();
}

// Assuming "Canberk" is the username of the professor
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} elseif (isset($_GET['username'])) {
    $username = $_GET['username'];
}

// Check if the selected term is passed via URL
if (isset($_SESSION['selected_term'])) {
    // Retrieve the selected term from the URL
    $selectedTerm = $_SESSION['selected_term'];
}

// Get the teaches relationships for the professor
$teaches_relationships = getTeachesRelationship($username);

// Initialize an empty array to store the courses
$courses_taught = array();

// Iterate through the teaches relationships to get the course codes
foreach ($teaches_relationships as $teaches_relationship) {
    // Get the course code from the relationship
    $crn = $teaches_relationship['crn'];

    // Use the course code to get the course information
    $course_info = getCourse($crn, $selectedTerm);

    if ($course_info != NULL) {
        // Add the course information to the array of courses taught by Canberk
        $courses_taught[] = $course_info;
    }
}

// Paginate the courses (each page with 2 courses)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Get current page number, default is 1
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$per_page = 2; // Number of courses per page
$filteredCourses = array_filter($courses_taught, function ($course) use ($searchTerm) {
    // Convert both the course name and search term to lowercase for case-insensitive comparison
    $lowerCaseCourseCode = strtolower($course['course_code']);
    $lowerCaseCourseName = strtolower($course['course_name']);
    $lowerCaseSearchTerm = strtolower($searchTerm);
    // Check if the lowercased search term is a substring of the lowercased course name
    return (stripos($lowerCaseCourseCode, $lowerCaseSearchTerm) !== false || stripos($lowerCaseCourseName, $lowerCaseSearchTerm) !== false);
});

$total_courses = count($filteredCourses); // Total number of courses
$total_pages = ceil($total_courses / $per_page); // Total number of pages
// Slice the array of courses based on the current page number
$start = ($page - 1) * $per_page;
$end = $start + $per_page;
$paginated_courses = array_slice($filteredCourses, $start, $per_page);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        header {
            height: 50px;
            /* Height of the header */
        }

        main {
            display: flex;
            flex-direction: column;
            height: calc(100% - 50px);
            /* Subtract header height from the viewport height */
        }

        .container {
            display: flex;
            flex-grow: 1;
            /* Allow container to grow and fill remaining space */
        }

        .hframe {
            flex: 1;
            /* Expand to fill available space */
            overflow: auto;
            /* Enable vertical scrolling if content exceeds height */
            border-right: 1px solid #ccc;
            /* Optional: Add border between frames */
            margin-bottom: 40px;
            /* Add margin to create space between hframe and card */

        }


        .pagination-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Styles for pagination links */
        .pagination-link {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #e0e0e0;
            color: #333;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .pagination-link:hover {
            background-color: #ccc;
        }

        .current-page {
            background-color: #ccc;
            pointer-events: none;
            /* Disable click events */
        }

        .no-courses-message {
            text-align: center;
            /* Center align the text */
            margin-bottom: 400px;
            /* Add some top margin for spacing */
        }

        .search-container {
            position: relative;
            /* Position the container relative to its nearest positioned ancestor */
            bottom: 550px;
            /* Adjust the top position as needed */
            left: 525px;
            /* Adjust the right position as needed */
        }

        .search-container input[type="text"] {
            width: 370px;
            /* Adjust the width as needed */
            height: 20px;
            /* Adjust the height as needed */
            padding: 5px;
            /* Add padding for better appearance */
        }

        .search-container button {
            height: 34px;
            /* Match button height to input height */
            padding: 5px 10px;
            /* Add padding for better appearance */
        }
    </style>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <?php include_once 'header.php'; ?>
    </header>
    <main>
        <div class="container">
            <div class="hframe">
                <?php include 'hframe.php'; ?>
            </div>
        </div>
        <?php if ($total_courses == 0 && isset($_GET['search']) === false) : ?>
            <div class="no-courses-message">
                You didn't teach any courses in <?php echo $selectedTerm; ?> semester.
            </div>
        <?php elseif ($total_courses == 0 && isset($_GET['search']) === true) : ?>
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search courses...">
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="no-courses-message-2">
                Search term not found.
            </div>
        <?php else : ?>
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search courses...">
                    <button type="submit" style="background-color: #7d96aa; color: white; border: 2px solid #7d96aa; cursor: pointer;">Search</button>
                </form>
            </div>
            <?php $cardMargin = 40; // Adjust this value as needed
            $counter = 0;
            ?>
            <?php foreach ($paginated_courses as $index => $course) : ?>
                <?php $topPosition = $index * ($cardMargin + 200) + 200; ?>
                <?php if (end($courses_taught)['crn'] != $course['crn'] || $courses_taught[0]['crn'] == end($courses_taught)['crn'] || $index == 1) : ?>
                    <div class="card" style="position: absolute; top: <?php echo $topPosition; ?>px; left: 450px;">
                        <?php include 'card.php'; ?>
                    </div>
                <?php else : ?>
                    <div class="lastcard" style="position: absolute; top: 200px; left: 450px">
                        <?php include 'card.php'; ?>
                    </div>
                <?php endif; ?>
                <?php $counter++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <div class="pagination-container" id="pagination-container">
        <!-- Pagination links will be added dynamically here -->
    </div>
    <script>
        // Replace the total_pages value with the actual number of pages
        const total_pages = <?php echo $total_pages; ?>;


        // Function to generate pagination links
        // Function to generate pagination links
        function generatePaginationLinks(currentPage, searchTerm) {
            const paginationContainer = document.getElementById('pagination-container');
            paginationContainer.innerHTML = ''; // Clear existing links
            for (let i = 1; i <= total_pages; i++) {
                const link = document.createElement('a');
                link.href = `?page=${i}&search=${searchTerm}`; // Include the search term in the link
                link.textContent = i;
                link.classList.add('pagination-link');
                if (i === currentPage) {
                    link.classList.add('current-page');
                }
                paginationContainer.appendChild(link);
            }
        }

        // Get the current page from the URL or set a default value
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = parseInt(urlParams.get('page')) || 1;
        const searchTerm = urlParams.get('search') || ''; // Get the search term from the URL

        // Call the function to generate pagination links with the current page and search term
        generatePaginationLinks(currentPage, searchTerm);
    </script>
</body>


</html>