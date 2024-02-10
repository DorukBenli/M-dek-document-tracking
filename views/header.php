<style>
    /* Styles for the header */
    header {
        background-color: #7d96aa;
        color: white;
        padding: 10px;
        display: flex; /* Use flexbox for layout */
        justify-content: space-between; /* Align items with space between */
        align-items: center; /* Vertically center items */
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
        display: flex; /* Use flexbox for layout */
        align-items: center; /* Vertically center items */
        margin-left: 952px; /* Push to the right */
        margin-top: 10px; /* Adjust top margin as needed */
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
    <div class="term-selection">
        <p><strong>Select Term:</strong></p>
        <select name="term_selection" onchange="selTerm(this)">
            <option value="59" selected>Sep 2023 - Jun 2024</option>
            <option value="57">Feb 2023 - Jan 2024</option>
            <option value="55">Sep 2022 - Jun 2023</option>
            <option value="53">Feb 2022 - Jan 2023</option>
            <option value="51">Sep 2021 - Jun 2022</option>
            <option value="49">Feb 2021 - Jan 2022</option>
            <option value="47">Sep 2020 - Jun 2021</option>
            <option value="45">Feb 2020 - Jan 2021</option>
        </select>
    </div>
</header>
