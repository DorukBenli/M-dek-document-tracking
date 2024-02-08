<?php
// Include the file containing your database connection and helper functions
include 'database.php';

// Assuming "Canberk" is the username of the professor
$professor_username = "Canberk";

// Get the teaches relationships for the professor
$teaches_relationships = getTeachesRelationship($professor_username);

// Display the courses taught by the professor "Canberk"
foreach ($teaches_relationships as $teaches_relationship) {
    // Get the course code from the relationship
    $course_code = $teaches_relationship['course_code'];
    
    // Use the course code to get the course information
    $course_info = getCourse($course_code);
    
    // Display the course information
    echo "Course Code: " . $course_info['course_code'] . "<br>";
    echo "Course Name: " . $course_info['course_name'] . "<br>";
    echo "Program Code: " . $course_info['program_code'] . "<br>";
    echo "Term: " . $course_info['term'] . "<br>";
    // Add more fields as needed
    echo "<br>";
}
?>
