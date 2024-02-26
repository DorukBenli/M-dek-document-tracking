<style>
    /* Styles for modal */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin-top: 350px;
        margin-left: 460px;
        /* 5% from the top and centered */
        padding: 30px;
        border: 1px solid #888;
        width: 50%;
        /* Adjust the width of the modal */
        max-width: 400px;
        /* Set a maximum width */
        border-radius: 10px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Add shadow */
    }

    /* Close button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Button styles */
    .button {
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 5px;
    }
</style>

<div style="width: 600px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold;">
            <?php echo $course['course_code'] . '-' . $course['section_code'] . ' ' . $course['course_name']; ?>
        </div>
    </div>

    <!-- Content area -->
    <div style="padding: 10px;">
        <?php if (isset($course)) : ?>
            <p style="line-height: 130%;">
            </p>
            <!-- Display requirements and file upload area -->
            <?php foreach ($requirements as $requirementType => $documents) : ?>
                <div class="requiremet_card" style="position: relative; margin-bottom: 20px;">
                    <?php include 'requirement_card.php'; ?>
                </div>
            <?php endforeach; ?>
            <div class="ta_card" style="position: relative; margin-top: 20px;">
                <?php include 'ta_card.php'; ?>
            </div>
            <!-- Add TA information here -->
        <?php else : ?>
            <p>No course information available.</p>
        <?php endif; ?>
        <br><br>
    </div>
</div>


<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // When the user clicks the button, open the modal 
    function openModal() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    function closeModal() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Function to validate the form before submission
    function validateForm() {
        var usernameInput = document.getElementById("taUsername").value.trim();

        // Check if the username input contains only spaces
        if (usernameInput === "") {
            alert("Please enter a valid TA username.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }

    // Function to validate the form before submission
    function validateForm(documentType) {
        var fileInput = document.getElementById("file_" + documentType);

        // Check if a file is selected
        if (fileInput.files.length === 0) {
            alert("Please select a file to upload.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>