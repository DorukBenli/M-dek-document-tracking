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
        margin-left: 1031px;
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
                    <option value="202302" <?php echo ($_SESSION['selected_term'] === '202302' || $_SESSION['selected_term'] === NULL) ? 'selected' : ''; ?>>202302</option>
                    <option value="202301" <?php echo ($_SESSION['selected_term'] === '202301') ? 'selected' : ''; ?>>202301</option>
                    <option value="202202" <?php echo ($_SESSION['selected_term'] === '202202') ? 'selected' : ''; ?>>202202</option>
                    <option value="202201" <?php echo ($_SESSION['selected_term'] === '202201') ? 'selected' : ''; ?>>202201</option>
                    <option value="202102" <?php echo ($_SESSION['selected_term'] === '202102') ? 'selected' : ''; ?>>202102</option>
                    <option value="202101" <?php echo ($_SESSION['selected_term'] === '202101') ? 'selected' : ''; ?>>202101</option>
                    <option value="202002" <?php echo ($_SESSION['selected_term'] === '202002') ? 'selected' : ''; ?>>202002</option>
                    <option value="202001" <?php echo ($_SESSION['selected_term'] === '202001') ? 'selected' : ''; ?>>202001</option>
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