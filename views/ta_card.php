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

<style>
    /* CSS to style the HR element */
    hr.custom-hr {
        margin-top: 5px;
        /* Adjust the top margin as needed */
        margin-bottom: 7px;
        /* Adjust the bottom margin as needed */
        border: none;
        /* Remove default border */
        border-top: 1px dashed #7d96aa;
        /* Add a custom border on top */
    }

    hr.custom-hr:last-child {
        margin-bottom: 3px;
        border-top: none;
        display: none;
        /* Remove the border from the last HR element */
    }

    /* Custom Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .custom-modal-content {
        position: relative;
        margin: 10% auto;
        padding: 20px;
        width: 50%;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .custom-close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .custom-close:hover,
    .custom-close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .custom-modal-header h2 {
        margin-top: 0;
    }

    .custom-form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    .custom-form-control {
        width: 97%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .custom-btn {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .custom-btn-primary {
        background-color: #7d96aa;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<div style="width: 500px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold; display: flex; align-items: center;">
            <strong>TA's:</strong>
            <?php if (isset($_SESSION['error'])) : ?>
                <div style="color: red; margin-left: 10px;"><?php echo $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])) : ?>
                <div style="color: green; margin-left: 10px;"><?php echo $_SESSION['success']; ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <div style="background-color: #7d96aa; color: white; margin-left: auto;">
                <!-- Add TA button -->
                <button onclick="openModal()" style="background-color: #7d96aa; color: white; border: 2px solid white; cursor: pointer;">
                    Add TA
                </button>
            </div>
        </div>
    </div>

    <!-- The modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="custom-modal-content">
            <span class="custom-close" onclick="closeModal()">&times;</span>
            <div class="custom-modal-header">
                <h2>Add Teaching Assistant</h2>
            </div>
            <div class="custom-modal-body">
                <form id="addCustomTAForm" action="../routes/router.php?action=addTA" method="post" onsubmit="return confirm('Are you sure you want to add ?');">
                    <div class="custom-form-group">
                        <label for="customTaUsername">TA Username:</label>
                        <input type="text" id="taUsername" name="taUsername" class="custom-form-control" placeholder="Enter TA Username" required>
                    </div>
                    <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                    <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                    <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                    <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                    <div class="custom-form-group">
                        <input type="submit" value="Add" class="custom-btn custom-btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div style="margin-top: 7px; margin-bottom: 5px;"></div>
    <div>
        <?php if (isset($_SESSION['tas']) && !empty($_SESSION['tas'])) : ?>
            <?php foreach ($_SESSION['tas'] as $ta) : ?>
                <div style="position: relative; display: flex; align-items: center;">
                    <div style="margin-left: 10px; margin-bottom: 4px;">
                        <strong><?php echo $ta['username']; ?></strong>
                    </div>
                    <!-- Remove TA button -->
                    <?php if ($role == "professor") : ?>
                        <div style="position: absolute; margin-left: 425px; margin-bottom: 4px;">
                            <form id="removeTA_<?php echo $ta['username']; ?>" action="../routes/router.php?action=removeTA" method="post" onsubmit="return confirm('Are you sure you want to remove this TA?');">
                                <input type="hidden" name="username" value="<?php echo $ta['username']; ?>">
                                <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                <input type="submit" value="Remove" style="background-color: white; color: #7d96aa; border: 2px solid #7d96aa; cursor: pointer;">
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <hr class="custom-hr">
            <?php endforeach; ?>
        <?php else : ?>
            <div style="margin-left: 150px;">
                <p>No TA assigned to this course.</p>
            </div>
        <?php endif; ?>
    </div>
</div>