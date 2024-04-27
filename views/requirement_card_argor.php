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
        margin-top: 5px;
        /* Adjust the top margin as needed */
        margin-bottom: 5px;
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
if (isset($_SESSION['role']) && $_SESSION['role'] != 'argor') {
    if ($_SESSION['role'] == 'professor' || $_SESSION['role'] == 'TA') {
        header("Location: show.php");
    }
    exit();
} ?>

<div style="width: 500px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold; display: flex; align-items: center;">
            <div>
                <?php echo 'Documents'; ?>
            </div>
        </div>
    </div>
    <!-- Content area -->
    <div style="padding: 2;">
        <?php if (isset($course)) : ?>
            <div style="margin-top: 5px;"></div>
            <!-- Display requirements and file upload area -->
            <form id="submitAllForm" action="../routes/router.php?action=submitFileArg" method="post">
                <?php $totalDocuments = count($softDocuments); ?>
                <?php foreach ($softDocuments as $index => $document) : ?>
                    <div style="margin-bottom: 2px; margin-left: 5px;"> <!-- Add margin bottom here -->
                        <div style="display: flex; align-items: center;">
                            <?php if (substr($document, 0, 5) === "Other") : ?>
                                <?php
                                $name = getName($course['term'], $course['crn'], $document);
                                $displayName = $document . ' - ' . $name; // Concatenate document and name
                                ?>
                                <strong><?php echo $displayName; ?></strong>
                            <?php else : ?>
                                <strong><?php echo $document; ?></strong>
                            <?php endif; ?>
                        </div>
                        <!-- Add file drop/upload area here -->
                        <?php if (!checkArgor($course['term'], $course['crn'], $document)) : ?>
                            <div style="display: flex; align-items: center; margin-right: 10px;">
                                <strong>Status: </strong>
                                <span style="margin-left: 5px; color: red;">❌</span>
                                <label for="submit_<?php echo $document; ?>" style="margin-left: 20px;">Gönder</label>
                                <input style="margin-left: 5px;" type="checkbox" id="submit_<?php echo $document; ?>" name="documents_submit[]" value="<?php echo $document; ?>">
                            </div>
                        <?php else : ?>
                            <div style="display: flex; align-items: center; margin-right: 10px;">
                                <strong>Status: </strong>
                                <span style=" margin-left: 5px; color: green;">✓</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($index < $totalDocuments - 1) : ?>
                        <hr class="custom-hr">
                    <?php endif; ?>
                <?php endforeach; ?>
                <!-- Single submit button for all unchecked documents -->
                <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                <div style="margin-left: 350px; margin-bottom: 3px;">
                    <input type="submit" id="submitBtn" style="background-color: white; color: #7d96aa; border: 2px solid #7d96aa; cursor: pointer;" value="Değişiklikleri Kaydet">
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>


<script>
    // JavaScript to enable/disable submit button based on checkbox state
    const checkboxesSubmit = document.querySelectorAll('input[id^="submit_"]');
    const checkboxesUndo = document.querySelectorAll('input[id^="undo_"]');
    const submitBtn = document.getElementById('submitBtn');

    checkboxesSubmit.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const anySubmitChecked = Array.from(checkboxesSubmit).some(cb => cb.checked);
            const anyUndoChecked = Array.from(checkboxesUndo).some(cb => cb.checked);
            submitBtn.disabled = !anySubmitChecked && !anyUndoChecked;
        });
    });

    checkboxesUndo.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const anySubmitChecked = Array.from(checkboxesSubmit).some(cb => cb.checked);
            const anyUndoChecked = Array.from(checkboxesUndo).some(cb => cb.checked);
            submitBtn.disabled = !anySubmitChecked && !anyUndoChecked;
        });
    });

    function checkForm(event) {
        const anySubmitChecked = Array.from(checkboxesSubmit).some(cb => cb.checked);
        const anyUndoChecked = Array.from(checkboxesUndo).some(cb => cb.checked);
        if (!anySubmitChecked && !anyUndoChecked) {
            alert('En az bir döküman seçin');
            event.preventDefault(); // Prevent form submission
        } else {
            const confirmed = confirm('Değişiklikleri göndermeye emin misiniz?');
            if (!confirmed) {
                event.preventDefault(); // Prevent form submission
            }
        }
    }

    // Add event listener to the form submit event
    const form = document.getElementById('submitAllForm');
    form.addEventListener('submit', checkForm);
</script>