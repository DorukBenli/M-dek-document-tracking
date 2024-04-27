<style>
    .form-container-checkbox {
        display: flex;
        align-items: center;
    }

    /* Adjust spacing between elements */
    .form-container-checkbox input[type="checkbox"] {
        margin-top: 15px;
        margin-left: 10px;
    }

    .form-container-checkbox input[type="submit"] {
        margin-top: 15px;
        margin-right: 20px;
        /* Adjust margin as needed */
    }

    /* CSS to style the HR element */
    hr.custom-hr {
        margin-top: 3px;
        /* Adjust the top margin as needed */
        margin-bottom: 3px;
        /* Adjust the bottom margin as needed */
        border: none;
        /* Remove default border */
        border-top: 1px dashed #7d96aa;
        /* Add a custom border on top */
    }

    hr.custom-hr:last-child {
        margin-bottom: 0;
        border-top: none;
        /* Remove the border from the last HR element */
        display: none;

    }
</style>

<?php // Check if username is not set
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
} ?>

<div style="width: 500px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 5px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold; display: flex; align-items: center;">
            <div>
                <?php echo $requirementType . ' Documents'; ?>
            </div>
            <!-- Button to trigger the modal -->
            <?php if ($requirementType === 'YÖK') : ?>
                <div style="background-color: #7d96aa; color: white; margin-left: auto;">
                    <button type="button" onclick="showModalExam()" style="background-color: #7d96aa; color: white; border: 2px solid white; cursor: pointer;">
                        Add
                    </button>
                </div>
            <?php endif; ?>

        </div>

        <!-- The Modal -->
        <div id="Modal-exam" class="modal">
            <div class="custom-modal-content">
                <span class="close-exam" onclick="closeModalExam()">&times;</span>
                <div class="custom-modal-exam-header">
                    <h2>Add Exam/Assignment/Other</h2>
                </div>
                <div class="custom-modal-exam-body" style="margin-top: 30px;">
                    <form action="../routes/router.php?action=addExam" method="post" onsubmit="return confirm('Are you sure you want to add ?');">
                        <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                        <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                        <select id="examType" name="exam" onchange="checkOtherOption()">
                            <?php
                            // Initialize an array to hold the exam options
                            $exams = array();

                            // Check if the course has midterms
                            if ($numMidterms > 0) {
                                $newdoc = "Midterm " . ($numMidterms + 1) . "";
                                $exams[] = $newdoc;
                            } else {
                                $exams[] = "Midterm 1";
                            }

                            // Check if the course does not have a final exam
                            if (!$hasFinalExam) {
                                $exams[] = "Final";
                            }

                            // Check if the course has midterms
                            if ($numHWs > 0) {
                                $newdoc = "Take Home Exam " . ($numHWs + 1) . "";
                                $exams[] = $newdoc;
                            } else {
                                $exams[] = "Take Home Exam 1";
                            }

                            // Check if the course has midterms
                            if ($numQuizzes > 0) {
                                $newdoc = "Quiz " . ($numQuizzes + 1) . "";
                                $exams[] = $newdoc;
                            } else {
                                $exams[] = "Quiz 1";
                            }

                            // Check if the course has midterms
                            if ($numOthers > 0) {
                                $newdoc = "Other " . ($numOthers + 1) . "";
                                $exams[] = $newdoc;
                            } else {
                                $exams[] = "Other 1";
                            }

                            // Loop through the exams to populate the dropdown options
                            foreach ($exams as $exam) {
                                echo "<option value='$exam'>$exam</option>";
                            }
                            ?>
                        </select>
                        <!-- Input field for typing the name of the "Other" exam -->
                        <div id="otherExamField" class="custom-form-group" style="display: none;">
                            <label for="customTaUsername">Name:</label>
                            <input type="text" id="other_exam" name="other_exam" class="custom-form-control" placeholder="Enter" required>
                        </div>
                        <div class="custom-form-group">
                            <input type="submit" value="Add" class="custom-btn custom-btn-primary" style="margin-top: 10px;">
                        </div>
                    </form>
                </div>

                <!-- JavaScript to toggle the input field based on the selected option -->
                <script>
                    function checkOtherOption() {
                        var selectBox = document.getElementById("examType");
                        var userInput = document.getElementById("otherExam");

                        if (selectBox.value === "Other 1") {
                            userInput.style.display = "block";
                            userInput.setAttribute("required", "required");
                        } else {
                            userInput.style.display = "none";
                            userInput.removeAttribute("required");
                        }
                    }

                    function checkOtherOption() {
                        var selectBox = document.getElementById("examType");
                        var otherExamField = document.getElementById("otherExamField");

                        // Get the value of the selected option
                        var selectedOption = selectBox.value;

                        // Check if the first 5 characters of the selected option are "Other"
                        if (selectedOption.substr(0, 5) === "Other") {
                            // Show the text input field
                            otherExamField.style.display = "block";
                            // Make the text input field required
                            document.getElementById("other_exam").required = true;
                        } else {
                            // Hide the text input field
                            otherExamField.style.display = "none";
                            // Make the text input field not required
                            document.getElementById("other_exam").required = false;
                        }
                    }
                </script>

                <style>
                    .custom-modal-exam-header {
                        /* Set a background color */
                        color: #7d96aa;
                        /* Set text color to white */
                        /* Add border radius to the top left corner */
                        /* Add border radius to the top right corner */
                    }

                    .custom-modal-exam-header h2 {
                        margin: 0;
                        /* Remove default margin for the h2 element */
                    }

                    #examType {
                        width: 100%;
                        height: 40px;
                        /* Set the desired height */

                        /* Set the width to 100% of its container */
                        /* Add any additional styling here */
                    }
                </style>

            </div>
        </div>


    </div>
    <!-- Content area -->
    <!-- Content area -->
    <div style="padding: 10px;">
        <?php if (isset($course)) : ?>
            <!-- Display requirements and file upload area -->
            <?php foreach ($documents as $document) : ?>
                <div style="margin-bottom: 3px;"> <!-- Add margin bottom here -->
                    <div style="display: flex; align-items: center;">
                        <?php
                        $documentname = str_replace(' ', '_', $document);

                        $isMidterm = stripos($document, 'Midterm') !== false && preg_match('/^Midterm \d{1,2}$/', $document);
                        $isFinal = ($document === 'Final');

                        $midtermNumber = null;
                        if ($isMidterm) {
                            $parts = explode(' ', $document);
                            $midtermNumber = intval(end($parts));
                        }

                        // Check if the document is the last midterm or the final exam
                        $lastMidterm = $isMidterm && $midtermNumber === $numMidterms;

                        $isHw = stripos($document, 'Take Home Exam') !== false && preg_match('/^Take Home Exam \d{1,2}$/', $document);

                        $HwNumber = null;
                        if ($isHw) {
                            $parts = explode(' ', $document);
                            $HwNumber = intval(end($parts));
                        }

                        // Check if the document is the last midterm or the final exam
                        $lastHw = $isHw && $HwNumber === $numHWs;

                        $isQuiz = stripos($document, 'Quiz') !== false && preg_match('/^Quiz \d{1,2}$/', $document);

                        $quizNumber = null;
                        if ($isQuiz) {
                            $parts = explode(' ', $document);
                            $quizNumber = intval(end($parts));
                        }

                        // Check if the document is the last midterm or the final exam
                        $lastQuiz = $isQuiz && $quizNumber === $numQuizzes;

                        $isOther = stripos($document, 'Other') !== false && preg_match('/^Other \d{1,2}$/', $document);

                        $otherNumber = null;
                        if ($isOther) {
                            $parts = explode(' ', $document);
                            $otherNumber = intval(end($parts));
                        }

                        // Check if the document is the last midterm or the final exam
                        $lastOther = $isOther && $otherNumber === $numOthers;
                        $finalExam = $isFinal;

                        // Check if the condition is met to show the remove button
                        $showRemoveButton = $lastMidterm || $finalExam || $lastHw || $lastQuiz || $lastOther;

                        $isExam = $isMidterm || $isFinal;

                        if ($isExam) {
                            $makeup = "$document Makeup";
                            $hasMakeup = isSubmit($course['term'], $course['crn'], $makeup);
                        }
                        ?>
                        <?php if ($isOther) : ?>
                            <?php
                            $name = getName($course['term'], $course['crn'], $document);
                            $displayName = $document . ' - ' . $name; // Concatenate document and name
                            ?>
                            <strong><?php echo $displayName; ?></strong>
                        <?php else : ?>
                            <strong><?php echo $document; ?></strong>
                        <?php endif; ?>

                        <?php if ($isExam && !($hasMakeup) && $requirementType === 'YÖK' && $role == "professor") : ?>
                            <div style="margin-left: 15px;">
                                <form id="documentForm_<?php echo $documentname; ?>" action="../routes/router.php?action=addMakeup" method="post" onsubmit="return confirm('Are you sure you want to add makeup of <?php echo $document; ?> ?');">
                                    <input type="hidden" name="document_type" value="<?php echo $documentname; ?>">
                                    <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                    <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                    <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                    <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                    <input type="submit" value="Add Makeup" style="width: 90px; height: 20px; background-color: white; color: #7d96aa; border: 2px solid #7d96aa; cursor: pointer;">
                                </form>
                            </div>
                        <?php endif; ?>
                        <?php if ($isExam && $hasMakeup && $requirementType === 'YÖK' && $role == "professor") : ?>
                            <div style="margin-left: 15px;">
                                <form id="documentForm_<?php echo $documentname; ?>" action="../routes/router.php?action=removeMakeup" method="post" onsubmit="return confirm('Are you sure you want to remove makeup of <?php echo $document; ?> ?');">
                                    <input type="hidden" name="document_type" value="<?php echo $documentname; ?>">
                                    <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                    <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                    <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                    <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                    <input type="submit" value="Remove Makeup" style="width: 115px; height: 20px; background-color: white; color: #7d96aa; border: 2px solid #7d96aa; cursor: pointer;">
                                </form>
                            </div>
                        <?php endif; ?>
                        <?php if ($showRemoveButton && $requirementType === 'YÖK' && $role == "professor") : ?>
                            <div style="margin-left: auto;">
                                <form id="documentForm_<?php echo $documentname; ?>" action="../routes/router.php?action=removeExam" method="post" onsubmit="return confirm('Are you sure you want to remove <?php echo $document; ?> ?');">
                                    <input type="hidden" name="document_type" value="<?php echo $documentname; ?>">
                                    <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                    <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                    <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                    <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                    <input type="submit" value="Remove" style="width: 70px; height: 20px; background-color: white; color: #7d96aa; border: 2px solid #7d96aa; cursor: pointer;">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($requirementType != 'YÖK') : ?>
                        <!-- Add file drop/upload area here -->
                        <div id="fileUpload_<?php echo $document; ?>" class="file-upload">
                            <!-- File drop area -->
                            <div class="file-drop">
                                <?php if ($status[$document] == 0) : ?>
                                    <form id="uploadForm_<?php echo $documentname; ?>" action="../routes/router.php?action=uploadFile" method="post" enctype="multipart/form-data" onsubmit="return validateForm('<?php echo $documentname; ?>')">
                                        <div style="margin-top: 8px; margin-bottom: 8px;">
                                            Drag and drop PDF file here or browse to upload.
                                        </div>
                                        <input type="file" name="file_<?php echo $documentname; ?>" id="file_<?php echo $documentname; ?>" class="input-file">
                                        <input type="hidden" name="document_type" value="<?php echo $documentname; ?>">
                                        <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                        <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                        <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                        <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                        <input type="submit" value="Upload" style="background-color: white; color: #7d96aa; border: 2px solid #7d96aa; cursor: pointer; margin-left: 140px;">
                                    </form>

                                <?php elseif ($status[$document] === 1) : ?>
                                    <p>Document submitted.</p>
                                    <!-- Display submitted file and remove form -->
                                    <div style="display: flex; align-items: center;">
                                        <!-- Download PDF link -->
                                        <div style="margin-right: 10px;">
                                            <a href="../routes/router.php?action=downloadFile&term=<?php echo $course['term']; ?>&crn=<?php echo $course['crn']; ?>&document_type=<?php echo $documentname; ?>">Download PDF</a>
                                        </div>
                                        <!-- Remove file form -->
                                        <div>
                                            <form id="removeForm_<?php echo $documentname; ?>" action="../routes/router.php?action=removeFile" method="post" onsubmit="return confirm('Are you sure you want to remove this file?');">
                                                <input type="hidden" name="document_type" value="<?php echo $document; ?>">
                                                <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                                <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                                <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                                <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                                <input type="submit" value="Remove File" style="background-color: white; color: #7d96aa; border: 2px solid #7d96aa; cursor: pointer;">
                                            </form>
                                        </div>
                                    </div>
                                    <!-- You can add code here to display the submitted file if needed -->
                                <?php endif; ?>
                            </div>
                            <!-- Uploaded file display area -->
                            <div id="fileDisplay_<?php echo $document; ?>" class="file-display"></div>
                        </div>
                    <?php else : ?>
                        <?php if (!checkArgor($course['term'], $course['crn'], $document)) : ?>
                            <div style="display: flex; align-items: center; margin-bottom: 2; margin-right: 10px;">
                                Received Status:
                                <span style="margin-left: 5px; color: red;">❌</span>
                            </div>
                        <?php else : ?>
                            <div style="display: flex; align-items: center; margin-bottom: 2; margin-right: 10px;">
                                Received Status:
                                <span style="margin-left: 5px; color: green;">✓</span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <hr class="custom-hr">
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>


<script>
    // Get the modal
    var modalExam = document.getElementById('Modal-exam');

    // Get the <span> element that closes the modal
    var spanExam = document.getElementsByClassName("close-exam")[0];

    // When the user clicks the button, open the modal 
    function showModalExam() {
        modalExam.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    function closeModalExam() {
        modalExam.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modalExam) {
            modalExam.style.display = "none";
        }
    }
</script>