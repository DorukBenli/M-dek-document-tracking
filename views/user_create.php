<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <!-- Add your CSS styles and JavaScript here if needed -->
</head>
<body>
    <h1>Create User</h1>

    <!-- Display any validation errors here -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- User creation form -->
    <form action="create_user.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>

        <label for="role">Role:</label>
        <input type="text" id="role" name="role" required>
        <br>

        <!-- Add more input fields for other user attributes as needed -->

        <button type="submit">Create User</button>
    </form>

    <!-- Add a link to go back to the user list or other pages -->
    <a href="user_list.php">Back to User List</a>
</body>
</html>
