<div style="width: 500px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold; display: flex; align-items: center;">
            <div>
                <?php echo $requirementType . ' Documents'; ?>
            </div>
            <!-- Button to trigger the modal -->
            <?php if ($requirementType === 'YÖK' && $role == "professor") : ?>
                <div style="margin-left: auto;">
                    <button type="button" onclick="showModalExam()">Add Exam</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- The Modal -->
        <div id="Modal-exam" class="modal">
            <div class="modal-content-exam">
                <span class="close-exam" onclick="closeModalExam()">&times;</span>
                <form action="../routes/router.php?action=addExam" method="post" onsubmit="return confirm('Are you sure you want to add an exam ?');">
                    <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                    <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                    <select name="exam">
                        <?php
                        // Initialize an array to hold the exam options
                        $exams = array();

                        // Check if the course has midterms
                        if ($numMidterms > 0) {
                            $newdoc = "Midterm " . ($numMidterms + 1) . "";
                            // Add midterm options up to n
                            $exams[] = $newdoc;
                        } else {
                            // If no midterms, add Midterm 1
                            $exams[] = "Midterm 1";
                        }

                        // Check if the course does not have a final exam
                        if (!$hasFinalExam) {
                            // Add Final option
                            $exams[] = "Final";
                        }

                        // Loop through the exams to populate the dropdown options
                        foreach ($exams as $exam) {
                            echo "<option value='$exam'>$exam</option>";
                        }
                        ?>
                    </select>
                    <button type="add-exam">Add</button>
                </form>
            </div>
        </div>


    </div>
    <!-- Content area -->
    <!-- Content area -->
    <div style="padding: 10px;">
        <?php if (isset($course)) : ?>
            <p style="line-height: 130%;">
            </p>
            <!-- Display requirements and file upload area -->
            <?php foreach ($documents as $document) : ?>
                <div style="margin-bottom: 30px;"> <!-- Add margin bottom here -->
                    <div style="display: flex; align-items: center;">
                        <strong><?php echo $document; ?></strong>
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
                        $finalExam = $isFinal;

                        // Check if the condition is met to show the remove button
                        $showRemoveButton = $lastMidterm || $finalExam;

                        ?>
                        <?php if ($showRemoveButton && $requirementType === 'YÖK' && $role == "professor") : ?>
                            <div style="margin-left: auto;">
                                <form id="documentForm_<?php echo $documentname; ?>" action="../routes/router.php?action=removeExam" method="post" onsubmit="return confirm('Are you sure you want to remove <?php echo $document; ?> ?');">
                                    <input type="hidden" name="document_type" value="<?php echo $documentname; ?>">
                                    <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                    <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                    <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                    <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                    <input type="submit" value="Remove">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Add file drop/upload area here -->
                    <div id="fileUpload_<?php echo $document; ?>" class="file-upload">
                        <!-- File drop area -->
                        <div class="file-drop">
                            <?php if ($status[$document] == 0) : ?>
                                <form id="uploadForm_<?php echo $documentname; ?>" action="../routes/router.php?action=uploadFile" method="post" enctype="multipart/form-data" onsubmit="return validateForm('<?php echo $documentname; ?>')">
                                    <p>Drag and drop PDF file here or browse to upload.</p>
                                    <input type="file" name="file_<?php echo $documentname; ?>" id="file_<?php echo $documentname; ?>" class="input-file">
                                    <input type="hidden" name="document_type" value="<?php echo $documentname; ?>">
                                    <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                    <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                    <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                    <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                    <input type="submit" value="Upload">
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
                                            <input type="submit" value="Remove File">
                                        </form>
                                    </div>
                                </div>
                                <!-- You can add code here to display the submitted file if needed -->
                            <?php endif; ?>
                        </div>
                        <!-- Uploaded file display area -->
                        <div id="fileDisplay_<?php echo $document; ?>" class="file-display"></div>
                    </div>
                </div>
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