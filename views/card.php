<div style="width: 600px; margin: auto; border: 1px solid #000;">
    <!-- Restricted area with background color -->
    <div style="background-color: #7d96aa; padding: 10px; color: white;">
        <div class="colHeader" style="padding: 3px; font-weight: bold;">Course Information</div>
    </div>

    <!-- Content area -->
    <div style="padding: 10px;">
        <?php if(isset($course)): ?>
            <p style="line-height: 130%;">
                <strong><?php echo $course['course_code'] . '-' . $course['section_code'] . ' ' . $course['course_name']; ?></strong><br><br>
            </p>
            <!-- Add TA information here -->
            <strong>TA's:</strong><br><br>
            <?php // Assuming $tas is an array of TA's, replace it with your actual data retrieval logic ?>
        <?php else: ?>
            <p>No course information available.</p>
        <?php endif; ?>
        <br><br>
    </div>
</div>
