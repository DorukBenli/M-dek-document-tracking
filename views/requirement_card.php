<div style="width: 500px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold;">
            <?php echo $requirementType . ' Documents'; ?>
        </div>
    </div>
    <!-- Content area -->
    <!-- Content area -->
    <div style="padding: 10px; margin-bottom: 20px;">
        <?php if (isset($course)) : ?>
            <p style="line-height: 130%;">
            </p>
            <!-- Display requirements and file upload area -->
            <?php foreach ($documents as $document) : ?>
                <div style="margin-bottom: 30px;"> <!-- Add margin bottom here -->
                    <strong><?php echo $document; ?></strong><br>
                    <?php
                    $documentname = str_replace(' ', '_', $document);
                    ?>
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
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>