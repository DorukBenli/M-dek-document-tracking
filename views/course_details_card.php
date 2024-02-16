<div style="width: 600px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold;">
            <?php echo $course['course_code'] . '-' . $course['section_code'] . ' ' . $course['course_name'] . ' ' . $course['term']; ?>
        </div>
    </div>

    <!-- Content area -->
    <!-- Content area -->
    <div style="padding: 10px;">
        <?php if (isset($course)) : ?>
            <p style="line-height: 130%;">
            </p>
            <!-- Display requirements and file upload area -->
            <?php foreach ($requirements as $requirement) : ?>
                <p>
                    <strong><?php echo $requirement['document_type']; ?> for <?php echo $requirement['requirement_type']; ?></strong><br>
                    <!-- Add file drop/upload area here -->
                <div id="fileUpload_<?php echo $requirement['document_type']; ?>" class="file-upload">
                    <!-- File drop area -->
                    <div class="file-drop">
                        <?php if ($status[$requirement['document_type']] == 0) : ?>
                            <form id="uploadForm_<?php echo $requirement['document_type']; ?>" action="../routes/router.php?action=uploadFile" method="post" enctype="multipart/form-data">
                                <p>Drag and drop PDF file here or browse to upload.</p>
                                <input type="file" name="file_<?php echo $requirement['document_type']; ?>" id="file_<?php echo $requirement['document_type']; ?>" class="input-file">
                                <input type="hidden" name="document_type" value="<?php echo $requirement['document_type']; ?>">
                                <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                <input type="submit" value="Upload">
                            </form>

                        <?php elseif ($status[$requirement['document_type']] == 1) : ?>
                            <p>Document submitted.</p>
                            <!-- Display submitted file and remove form -->
                            <div style="display: flex; align-items: center;">
                                <!-- Download PDF link -->
                                <div style="margin-right: 10px;">
                                    <a href="../routes/router.php?action=downloadFile&term=<?php echo $course['term']; ?>&course_code=<?php echo $course['course_code']; ?>&document_type=<?php echo $requirement['document_type']; ?>">Download PDF</a>
                                </div>
                                <!-- Remove file form -->
                                <div>
                                    <form id="removeForm_<?php echo $requirement['document_type']; ?>" action="../routes/router.php?action=removeFile" method="post">
                                        <input type="hidden" name="document_type" value="<?php echo $requirement['document_type']; ?>">
                                        <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                        <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                        <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                        <input type="submit" value="Remove File">
                                    </form>
                                </div>
                            </div>

                            <!-- You can add code here to display the submitted file if needed -->
                        <?php endif; ?>
                    </div>
                    <!-- Uploaded file display area -->
                    <div id="fileDisplay_<?php echo $requirement['document_type']; ?>" class="file-display"></div>
                </div>
                </p>
            <?php endforeach; ?>


            <!-- Add TA information here -->
            <strong>TA's:</strong><br><br>
            <?php // Assuming $tas is an array of TA's, replace it with your actual data retrieval logic 
            ?>
        <?php else : ?>
            <p>No course information available.</p>
        <?php endif; ?>
        <br><br>
    </div>


</div>