<?php
include '../helper.php';
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
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the Teaches JSON file was uploaded
    if (isset($_FILES['jsonFileTeaches'])) {
        $file = $_FILES['jsonFileTeaches'];
        $message = processUploadedJson($file, "processJsonData");

    // Check if the Courses JSON file was uploaded
    } elseif (isset($_FILES['jsonFileCourses'])) {
        $file = $_FILES['jsonFileCourses'];
        $message = processUploadedJson($file, "processCoursesJsonData");
    }
    // check for the handles
    else if (isset($_FILES['jsonFileHandles'])){
        $file = $_FILES['jsonFileHandles'];
        $message = processUploadedJson($file, "processHandlesJsonData");
    }
    elseif (isset($_FILES['jsonFileRequirements'])) {
        $file = $_FILES['jsonFileRequirements'];
        $message = processUploadedJson($file, "processRequirementsJsonData");
    } elseif (isset($_FILES['jsonFileCourseRequirements'])) {
        $file = $_FILES['jsonFileCourseRequirements'];
        $message = processUploadedJson($file, "processCourseRequirementsJsonData");
    } elseif (isset($_FILES['jsonFileRequiredDocuments'])) {
        $file = $_FILES['jsonFileRequiredDocuments'];
        $message = processUploadedJson($file, "processRequiredDocumentsJsonData");
    }
}

function processUploadedJson($file, $processingFunction) {
    $message = "";
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (strtolower($fileType) === 'json') {
            $jsonData = file_get_contents($file['tmp_name']);
            $data = json_decode($jsonData, true);
            if ($data === null) {
                $message = "Invalid JSON data.";
            } else {
                $message = $processingFunction($data);
            }
        } else {
            $message = "Please upload a valid JSON file.";
        }
    } else {
        $message = "File upload error: " . $file['error'];
    }
    return $message;
}

function processJsonData($data) {
    if (empty($data)) {
        echo 'No data to process.';
        return;
    }
    
    foreach ($data as $entry) {
        if (!isset($entry['username']) || !isset($entry['crn'])) {
            echo 'Missing username or CRN in entry.';
            continue;
        }
        
        $username = $entry['username'];
        $crn = $entry['crn'];
        $result = createTeachesRelationship($username, $crn);
        if ($result) {
            echo "Inserted {$username} with CRN {$crn} successfully.<br>";
        } else {
            echo "Failed to insert {$username} with CRN {$crn}.<br>";
        }
    }
}

function processCoursesJsonData($data) {
    foreach ($data as $course) {
        createCourse(
            $course['course_code'],
            $course['course_name'],
            $course['exam_count'],
            $course['program_code'],
            $course['term'],
            $course['crn'],
            $course['section_code']
        );
    }
    return "Course information updated successfully.";
}

function processHandlesJsonData($data) {
    global $conn; // Ensure the database connection is available
    if (empty($data)) {
        return 'No data to process.';
    }
    
    $results = [];
    foreach ($data as $entry) {
        if (!isset($entry['username']) || !isset($entry['crn'])) {
            $results[] = 'Missing username or CRN in entry.';
            continue;
        }

        $username = $entry['username'];
        $crn = intval($entry['crn']);

        try {
            $stmt = mysqli_prepare($conn, "INSERT INTO Handles (username, crn) VALUES (?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'si', $username, $crn);
            $execute_result = mysqli_stmt_execute($stmt);
            if (!$execute_result) {
                throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
            $results[] = "Inserted {$username} with CRN {$crn} successfully.";
        } catch (Exception $e) {
            $results[] = "Failed to insert {$username} with CRN {$crn}. Error: " . $e->getMessage();
        }
    }
    return implode('<br>', $results);
}


function processRequirementsJsonData($data) {
    global $conn;
    if (empty($data)) {
        return 'No data to process.';
    }
    
    $results = [];
    foreach ($data as $entry) {
        if (!isset($entry['type'])) {
            $results[] = 'Missing requirement type in entry.';
            continue;
        }

        $type = $entry['type'];

        $stmt = mysqli_prepare($conn, "INSERT INTO Requirement (type) VALUES (?)");
        mysqli_stmt_bind_param($stmt, 's', $type);
        $execute_result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($execute_result) {
            $results[] = "Inserted requirement type {$type} successfully.";
        } else {
            $results[] = "Failed to insert requirement type {$type}.";
        }
    }
    return implode('<br>', $results);
}


//following function will automatically insert to submit table: bu query de NOT EXISTS kullanma (fixed)
function updateSubmitTableWithRequirements($conn, $term, $crn) {
    mysqli_begin_transaction($conn);
    try {
        $courseRequirementsStmt = mysqli_prepare($conn, "SELECT requirement_type FROM CourseRequirements WHERE term = ? AND crn = ?");
        if (!$courseRequirementsStmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($courseRequirementsStmt, 'si', $term, $crn);
        if (!mysqli_stmt_execute($courseRequirementsStmt)) {
            throw new Exception('Execute failed: ' . mysqli_stmt_error($courseRequirementsStmt));
        }
        $courseRequirementsResult = mysqli_stmt_get_result($courseRequirementsStmt);
        mysqli_stmt_close($courseRequirementsStmt);

        while ($requirement = mysqli_fetch_assoc($courseRequirementsResult)) {
            $requiredDocsStmt = mysqli_prepare($conn, "SELECT document_type FROM RequiredDocuments WHERE requirement_type = ?");
            if (!$requiredDocsStmt) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($requiredDocsStmt, 's', $requirement['requirement_type']);
            if (!mysqli_stmt_execute($requiredDocsStmt)) {
                throw new Exception('Execute failed: ' . mysqli_stmt_error($requiredDocsStmt));
            }
            $requiredDocsResult = mysqli_stmt_get_result($requiredDocsStmt);
            mysqli_stmt_close($requiredDocsStmt);

            while ($document = mysqli_fetch_assoc($requiredDocsResult)) {
                //if there exists a string 'makeup' or conditional ones just skip them
                if (stripos($document['document_type'], 'Makeup') !== false or stripos($document['document_type'], 'Midterm 2') !== false or stripos($document['document_type'], 'Midterm 3') !== false or stripos($document['document_type'], 'Quiz') !== false or stripos($document['document_type'], 'Take Home') !== false or stripos($document['document_type'], 'Other')) {
                    continue; 
                }
                // Check if the document already exists in the submit table
                $checkExistStmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM submit WHERE term = ? AND crn = ? AND document_type = ?");
                mysqli_stmt_bind_param($checkExistStmt, 'sis', $term, $crn, $document['document_type']);
                if (!mysqli_stmt_execute($checkExistStmt)) {
                    throw new Exception('Execute failed: ' . mysqli_stmt_error($checkExistStmt));
                }
                mysqli_stmt_bind_result($checkExistStmt, $count);
                mysqli_stmt_fetch($checkExistStmt);
                mysqli_stmt_close($checkExistStmt);

                // If the document does not exist, insert it
                if ($count == 0) {
                    $insertStmt = mysqli_prepare($conn, "INSERT INTO submit (term, crn, document_type, submitted) VALUES (?, ?, ?, 0)");
                    if (!$insertStmt) {
                        throw new Exception('Prepare failed: ' . mysqli_error($conn));
                    }
                    mysqli_stmt_bind_param($insertStmt, 'sis', $term, $crn, $document['document_type']);
                    if (!mysqli_stmt_execute($insertStmt)) {
                        throw new Exception('Execute failed: ' . mysqli_stmt_error($insertStmt));
                    }
                    mysqli_stmt_close($insertStmt);
                }
            }
        }

        mysqli_commit($conn);
        return "Documents for CRN {$crn} and term {$term} processed successfully.";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return "Error: " . $e->getMessage();
    }
}



function processCourseRequirementsJsonData($data) {
    global $conn;
    if (empty($data)) {
        return 'No data to process.';
    }
    
    $results = [];
    foreach ($data as $entry) {
        if (!isset($entry['requirement_type']) || !isset($entry['term']) || !isset($entry['crn'])) {
            $results[] = 'Missing data in entry.';
            continue;
        }

        $requirement_type = $entry['requirement_type'];
        $term = $entry['term'];
        $crn = intval($entry['crn']);

        $stmt = mysqli_prepare($conn, "INSERT INTO CourseRequirements (requirement_type, term, crn) VALUES (?, ?, ?)");
        if (!$stmt) {
            $results[] = "Failed to prepare the statement: " . mysqli_error($conn);
            continue;
        }

        mysqli_stmt_bind_param($stmt, 'ssi', $requirement_type, $term, $crn);

        // Execute the prepared statement
        $execute_result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($execute_result) {
            // Insert was successful, now update the submit table
            $results[] = "Inserted course requirement successfully for CRN {$crn}.";
            $submitUpdateResults = updateSubmitTableWithRequirements($conn, $term, $crn);
            $results[] = $submitUpdateResults;
        } else {
            $results[] = "Failed to insert course requirement for CRN {$crn}. Error: " . mysqli_stmt_error($stmt);
        }
    }
    return implode('<br>', $results);
}

function processRequiredDocumentsJsonData($data) {
    global $conn;
    if (empty($data)) {
        return 'No data to process.';
    }
    
    $results = [];
    foreach ($data as $entry) {
        if (!isset($entry['requirement_type']) || !isset($entry['document_type'])) {
            $results[] = 'Missing requirement type or document type in entry.';
            continue;
        }

        $requirement_type = $entry['requirement_type'];
        $document_type = $entry['document_type'];

        // Prepare the statement
        $stmt = mysqli_prepare($conn, "INSERT INTO RequiredDocuments (requirement_type, document_type) VALUES (?, ?)");
        if (!$stmt) {
            $results[] = "Failed to prepare the statement: " . mysqli_error($conn);
            continue;
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt, 'ss', $requirement_type, $document_type);
        
        // Execute the statement
        if (!mysqli_stmt_execute($stmt)) {
            $results[] = "Failed to insert required document {$document_type} for requirement type {$requirement_type}. Error: " . mysqli_stmt_error($stmt);
        } else {
            $results[] = "Inserted required document {$document_type} for requirement type {$requirement_type} successfully.";
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    }
    return implode('<br>', $results);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Upload JSON</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .upload-container {
            width: 300px;
            margin: 100px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px; /* Space between title and file input button */
        }

        .drop-area {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            text-align: center;
            background-color: #fafafa;
            color: #666;
            cursor: pointer;
            font-size: 14px;
        }

        .drop-area:hover {
            background-color: #e9ecef;
        }

        input[type="file"] {
            display: none; /* Keep file input hidden */
        }

        button {
            display: block;
            width: 100%;
            padding: 8px;
            border: none;
            background-color: #7d96aa;
            color: white;
            border-radius: 3px;
            cursor: pointer;
            text-transform: uppercase;
        }

        button:hover {
            background-color: #5d96aa;
        }
        select#uploadType {
            width: 100%;
            padding: 8px 12px;
            border-radius: 4px;/
            border: 1px solid #ccc;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            appearance: none;
            -webkit-appearance: none; 
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="black" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'); /* Custom arrow */
            background-repeat: no-repeat;
            background-position-x: 95%;
            background-position-y: 50%;
            cursor: pointer;
        }

        select#uploadType:focus {
            outline: none;
            box-shadow: 0 0 0 2px #7d96aa;
        }

        select#uploadType option {
            padding: 8px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'hframe.php'; ?>
    <div class="upload-container">
        <h2>Upload JSON File</h2>
        <select id="uploadType" onchange="showForm()">
            <option value="select">Select Upload Type</option>
            <option value="teaches">Add to Teaches</option>
            <option value="course">Add Course</option>
            <option value="handles">Add Handles</option>
            <option value="requirements">Add Requirements</option>
            <option value="courseRequirements">Add Course Requirements</option>
            <option value="requiredDocuments">Add Required Documents</option>
        </select>

        <div id="teachesForm" style="display:none;">
            <h3>Upload JSON File for teaches</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" id="fileInput" name="jsonFile" accept=".json">
                <button type="button" onclick="document.getElementById('fileInput').click();">Select File</button>
                <div id="dropArea" class="drop-area" onclick="document.getElementById('fileInput').click();">
                    Drag and drop a file here or click to select
                </div>
                <button type="submit">Upload File</button>
            </form>
        </div>

        <div id="courseForm" style="display:none;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Upload Course Information</h3>
                <input type="file" id="fileInputCourses" name="jsonFileCourses" accept=".json">
                <button type="button" onclick="document.getElementById('fileInputCourses').click();">Select File</button>
                <div id="dropArea" class="drop-area" onclick="document.getElementById('fileInputCourses').click();">
                    Drag and drop a file here or click to select
                </div>
                <button type="submit">Upload File</button>
            </form>
        </div>

        <div id="handlesForm" style="display:none;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Upload Handles Information</h3>
                <input type="file" id="fileInputHandles" name="jsonFileHandles" accept=".json">
                <button type="button" onclick="document.getElementById('fileInputHandles').click();">Select File</button>
                <div class="drop-area" onclick="document.getElementById('fileInputHandles').click();">
                    Drag and drop a file here or click to select
                </div>
                <button type="submit">Upload File</button>
            </form>
        </div>


        <div id="requirementsForm" style="display:none;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Upload Requirements Information</h3>
                <input type="file" id="fileInputRequirements" name="jsonFileRequirements" accept=".json">
                <button type="button" onclick="document.getElementById('fileInputRequirements').click();">Select File</button>
                <div id="dropArea" class="drop-area" onclick="document.getElementById('fileInputRequirements').click();">
                    Drag and drop a file here or click to select
                </div>
                <button type="submit">Upload File</button>
            </form>
        </div>

        <div id="courseRequirementsForm" style="display:none;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Upload Course Requirements Information</h3>
                <input type="file" id="fileInputCourseRequirements" name="jsonFileCourseRequirements" accept=".json">
                <button type="button" onclick="document.getElementById('fileInputCourseRequirements').click();">Select File</button>
                <div id="dropArea" class="drop-area" onclick="document.getElementById('fileInputCourseRequirements').click();">
                    Drag and drop a file here or click to select
                </div>
                <button type="submit">Upload File</button>
            </form>
        </div>

        <div id="requiredDocumentsForm" style="display:none;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Upload Required Documents Information</h3>
                <input type="file" id="fileInputRequirededDocs" name="jsonFileRequirededDocs" accept=".json">
                <button type="button" onclick="document.getElementById('fileInputRequirededDocs').click();">Select File</button>
                <div id="dropArea" class="drop-area" onclick="document.getElementById('fileInputRequirededDocs').click();">
                    Drag and drop a file here or click to select
                </div>
                <button type="submit">Upload File</button>
            </form>
        </div>

    </div>

    <?php if ($message): ?>
        <p id="message"><?php echo $message; ?></p>
        <script>clearMessageAfterDelay();</script>
    <?php endif; ?>


    <script>
        function showForm() {
            var selection = document.getElementById('uploadType').value;
            var teachesForm = document.getElementById('teachesForm');
            var courseForm = document.getElementById('courseForm');
            var HandlesForm = document.getElementById('handlesForm');
            var requirementsForm = document.getElementById('requirementsForm');
            var courseRequirementsForm = document.getElementById('courseRequirementsForm');
            var requiredDocumentsForm = document.getElementById('requiredDocumentsForm');

            teachesForm.style.display = 'none';
            courseForm.style.display = 'none';
            HandlesForm.style.display = 'none';
            requirementsForm.style.display = 'none';
            courseRequirementsForm.style.display = 'none';
            requiredDocumentsForm.style.display = 'none';

            if (selection === 'teaches') {
                teachesForm.style.display = 'block';
            } else if (selection === 'course') {
                courseForm.style.display = 'block';
            } else if (selection === 'handles') { 
                handlesForm.style.display = 'block';
            } else if (selection === 'requirements') {
                requirementsForm.style.display = 'block';
            } else if (selection === 'courseRequirements') {
                courseRequirementsForm.style.display = 'block';
            } else if (selection === 'requiredDocuments') {
                requiredDocumentsForm.style.display = 'block';
            } 
        }

        function clearMessageAfterDelay() {
        setTimeout(function() {
            var messageElement = document.getElementById('message');
            if (messageElement) {
                messageElement.innerHTML = '';
            }
        }, 3000);
    }
        if (message) {
            clearMessageAfterDelay();
        }
    </script>
</body>
</html>
