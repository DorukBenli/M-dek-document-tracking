<style>
    /* Styles for the header */
    header {
        background-color: #7d96aa;
        color: white;
        padding: 10px;
        display: flex;
        /* Use flexbox for layout */
        justify-content: space-between;
        /* Align items with space between */
        align-items: center;
        /* Vertically center items */
    }

    .logo {
        display: flex;
        align-items: center;
    }

    .logo img {
        width: 44px;
        height: 44px;
        margin-right: 10px;
    }

    .course-info {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .term-selection {
        font-size: 12px;
        color: white;
        display: flex;
        /* Use flexbox for layout */
        align-items: center;
        /* Vertically center items */
        margin-left: 952px;
        /* Push to the right */
        margin-top: 10px;
        /* Adjust top margin as needed */
    }

    .term-selection p {
        font-size: 15px;
        margin-right: 10px;
    }

    .term-selection select {
        background-color: #7d96aa;
        color: white;
        border: none;
        border-bottom: 1px solid white;
        padding: 5px 10px;
        cursor: pointer;
    }
</style>

<header>
    <div class="logo">
        <img src="../images/logos/document.png" alt="Logo">
        <div>
            <p class="course-info"><strong>MUDEK</strong></p>
            <p class="course-info"><strong>Web for Document Tracking System</strong></p>
        </div>
    </div>
    <?php
    // Check if the user is logged in (you need to replace this condition with your actual login check)
    $isLoggedIn = isset($_SESSION['username']) && !empty($_SESSION['username']);
    ?>
    <?php if ($isLoggedIn) : ?>
        <div class="term-selection">
            <p><strong>Select Term:</strong></p>
            <form id="termForm" action="../routes/router.php?action=store_term_session" method="post">
                <select name="term_selection" onchange="updateFormAction()">
                    <option value="Spring 2024" <?php echo ($_SESSION['selected_term'] === 'Spring 2024' || $_SESSION['selected_term'] === NULL) ? 'selected' : ''; ?>>Spring 2024</option>
                    <option value="Fall 2023" <?php echo ($_SESSION['selected_term'] === 'Fall 2023') ? 'selected' : ''; ?>>Fall 2023</option>
                    <option value="Spring 2023" <?php echo ($_SESSION['selected_term'] === 'Spring 2023') ? 'selected' : ''; ?>>Spring 2023</option>
                    <option value="Fall 2022" <?php echo ($_SESSION['selected_term'] === 'Fall 2022') ? 'selected' : ''; ?>>Fall 2022</option>
                    <option value="Spring 2022" <?php echo ($_SESSION['selected_term'] === 'Spring 2022') ? 'selected' : ''; ?>>Spring 2022</option>
                    <option value="Fall 2021" <?php echo ($_SESSION['selected_term'] === 'Fall 2021') ? 'selected' : ''; ?>>Fall 2021</option>
                    <option value="Spring 2021" <?php echo ($_SESSION['selected_term'] === 'Spring 2021') ? 'selected' : ''; ?>>Spring 2021</option>
                    <option value="Fall 2020" <?php echo ($_SESSION['selected_term'] === 'Fall 2020') ? 'selected' : ''; ?>>Fall 2020</option>
                </select>
            </form>
        </div>
    <?php endif; ?>
</header>


<script>
    function updateFormAction() {
        var form = document.getElementById('termForm');
        var selectedTerm = form.elements['term_selection'].value;
        var actionUrl = '../routes/router.php?action=store_term_session&term=' + encodeURIComponent(selectedTerm);
        form.action = actionUrl;
        form.submit(); // Submit the form
    }
</script>