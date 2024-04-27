<?php // Assuming $tas is an array of TA's, replace it with your actual data retrieval logic
// Check if username is not set
if (!isset($_SESSION['username'])) {
    // Redirect to index.php
    header("Location: index.php");
    exit(); // Make sure to exit after redirection
}
/*
if (isset($_SESSION['role']) && $_SESSION['role'] != 'TA' && $_SESSION['role'] != 'professor') {
    if ($_SESSION['role'] == 'argor') {
        header("Location: show-arg.php");
    }
    exit();
}*/
$tas = getTAs($course['crn'], $course['term']);        ?>

<div style="width: 600px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold;">Course Information</div>
    </div>

    <!-- Content area -->
    <div style="padding: 5px;">
        <?php if (isset($course)) : ?>
            <p style="line-height: 130%;">
                <!-- Make the course code and name clickable -->
                <a href="#" onclick="redirectToCourse('<?php echo $course['crn']; ?>', '<?php echo $course['term']; ?>'); return false;">
                    <strong><?php echo $course['course_code'] . '-' . $course['section_code'] . ' ' . $course['course_name']; ?></strong>
                </a>
                <br>
            </p>
            <?php foreach ($tas as $ta) : ?>
                <div style="position: relative; align-items: center; left: 0px; top: 10px;">
                <?php endforeach; ?>
                </div>
        <?php else : ?>
            <p>No course information available.</p>
        <?php endif; ?>
    </div>
    
    <script>
        // JavaScript function to redirect to the course page
        function redirectToCourse(crn, term) {
            // Construct the URL with query parameters
            var url = '../routes/router.php?action=showcourse&crn=' + crn + '&term=' + term;
            // Redirect the user to the constructed URL
            window.location.href = url;
        }
    </script>