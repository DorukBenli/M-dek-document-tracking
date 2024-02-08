
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login_type = $_POST['logintype'];


    // Include the helper functions
    include 'helper.php';

    // Validate the password
    if (validatePassword($username, $password)) {
        // Redirect to show.php with the username
        echo '<form id="redirectForm" action="show.php" method="post">';
        echo '<input type="hidden" name="username" value="' . htmlspecialchars($username) . '">';
        echo '</form>';
        echo '<script>document.getElementById("redirectForm").submit();</script>';
    } else {
        // Password is incorrect
        echo '<p class="error">Invalid username or password. Please try again.</p>';
    }
}
?>


<style>
    h1 {
        color: #333399;
        font-size: 24px;
        margin-bottom: 20px;
        margin-top: 55px;
    }

    .error {
        color: red;
    }

    .container {
        width: 300px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #7d96aa;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group input[type="submit"] {
        background-color: #7d96aa;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-group input[type="submit"]:hover {
        background-color: #555;
    }
</style>


<div class="container">
    <div>
        <h1>
            <img src="./images/logos/security.jpg" alt="Security Logo" width="48" height="48" align="left" style="margin-right: 10px;">
            MUDEK Document Tracking System Login
        </h1>
        <?php if (isset($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
    <form action="" method="post" name="login">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" tabindex="1" value="">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" tabindex="2">
        </div>
        <div class="form-group">
            <label for="logintype">Login As:</label>
            <select id="logintype" name="logintype" tabindex="3">
                <option value=""></option>
                <option value="professor">Professor</option>
                <option value="argor">Argor</option>
                <option value="student">TA/LA</option>
                <option value="webadmin">Web Admin</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" name="button" tabindex="4" value="Log in">
        </div>
    </form>
</div>

