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
            <?php if ($role == "professor") : ?>
                <div style="margin-left: auto;">
                    <!-- Add TA button -->
                    <button onclick="openModal()">Add TA</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- The modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form id="addTAForm" action="../routes/router.php?action=addTA" method="post" onsubmit="return validateForm()">
                <label for="taUsername">TA Username:</label>
                <input type="text" id="taUsername" name="taUsername">
                <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                <input type="submit" value="Add">
            </form>
        </div>
    </div>
    <p style="line-height: 130%;">
    </p>
    <div>
        <?php if (isset($_SESSION['tas']) && !empty($_SESSION['tas'])) : ?>
            <?php foreach ($_SESSION['tas'] as $ta) : ?>
                <div style="position: relative; display: flex; align-items: center;">
                    <div style="margin-left: 10px;">
                        <strong><?php echo $ta['username']; ?></strong>
                    </div>
                    <!-- Remove TA button -->
                    <?php if ($role == "professor") : ?>
                        <div style="margin-left: 365px;"> <!-- Adjust margin as needed -->
                            <form id="removeTA_<?php echo $ta['username']; ?>" action="../routes/router.php?action=removeTA" method="post" onsubmit="return confirm('Are you sure you want to remove this TA?');">
                                <input type="hidden" name="username" value="<?php echo $ta['username']; ?>">
                                <input type="hidden" name="term" value="<?php echo $course['term']; ?>">
                                <input type="hidden" name="course_code" value="<?php echo $course['course_code']; ?>">
                                <input type="hidden" name="section_code" value="<?php echo $course['section_code']; ?>">
                                <input type="hidden" name="crn" value="<?php echo $course['crn']; ?>">
                                <input type="submit" value="Remove">
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <br> <!-- Add line break for vertical spacing -->
            <?php endforeach; ?>
        <?php else : ?>
            <div style="margin-left: 150px;">
                <p>No TA assigned to this course.</p>
            </div>
        <?php endif; ?>
    </div>
</div>