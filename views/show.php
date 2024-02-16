<?php
// Include the database functions file
include '../helper.php';
session_start();

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
$courses_taught_by_canberk = array();

// Iterate through the teaches relationships to get the course codes
foreach ($teaches_relationships as $teaches_relationship) {
    // Get the course code from the relationship
    $course_code = $teaches_relationship['course_code'];

    // Use the course code to get the course information
    $course_info = getCourse($course_code, $selectedTerm);

    if ($course_info != NULL) {
        // Add the course information to the array of courses taught by Canberk
        $courses_taught_by_canberk[] = $course_info;
    }
}

// Paginate the courses (each page with 2 courses)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Get current page number, default is 1
$per_page = 2; // Number of courses per page
$total_courses = count($courses_taught_by_canberk); // Total number of courses
$total_pages = ceil($total_courses / $per_page); // Total number of pages

// Slice the array of courses based on the current page number
$start = ($page - 1) * $per_page;
$end = $start + $per_page;
$paginated_courses = array_slice($courses_taught_by_canberk, $start, $per_page);
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

        .card {
            /* Styles for card.php */
            margin-bottom: 30px;
            /* Adjust the margin to control the distance between hframe and card */
        }

        .card:first-child {
            margin-top: 0px;
            margin-bottom: 100px;
            /* Add margin to create space between hframe and first card */
        }

        .card:last-child {
            margin-bottom: 100px;
            /* Add margin to create space between last card and bottom of the webpage */
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
            text-align: center; /* Center align the text */
            margin-bottom: 400px; /* Add some top margin for spacing */
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
        <?php if ($total_courses == 0) : ?>
            <div class="no-courses-message">
                You didn't teach any courses in <?php echo $selectedTerm; ?> semester.
            </div>
        <?php else : ?>
            <?php foreach ($paginated_courses as $course) : ?>
                <div class="card">
                    <?php include 'card.php'; ?>
                </div>
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
        function generatePaginationLinks(currentPage) {
            const paginationContainer = document.getElementById('pagination-container');
            paginationContainer.innerHTML = ''; // Clear existing links
            for (let i = 1; i <= total_pages; i++) {
                const link = document.createElement('a');
                link.href = `?page=${i}`;
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

        // Call the function to generate pagination links with the current page
        generatePaginationLinks(currentPage);
    </script>
</body>


</html>