<?php
// Check if the user is logged in
$userLoggedIn = true; // Set this to true if the user is logged in, otherwise set it to false

// Check if the user clicked on the "Log Off" link
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect the user to the index.php page
    header("Location: index.php");
    exit; // Stop further execution
}
?>

<?php if ($userLoggedIn) : ?>
    <tr>
        <td valign="top" height="30">
            <table width="100%">
                <tbody>
                    <tr>
                        <td rowspan="2" width="50"><img src="../images/logos/projects.jpg" width="48" height="48"></td>
                        <td valign="top">
                            <font class="logoTitle"><strong>Welcome Canberk Tahıl</strong></font>
                        </td>
                        <td align="right" valign="top" rowspan="2">
                            <br>
                            <font class="normal"><script>
                                // Get the current date and time
                                var now = new Date();
                                // Get the local date and time in string format
                                var localDateTime = now.toLocaleString('en-US', {
                                    weekday: 'long', // Full name of the day of the week
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric',
                                    hour: 'numeric',
                                    minute: 'numeric',
                                    hour12: true // 12-hour clock format
                                });
                                document.write(localDateTime);
                            </script></font>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <font class="logoText">
                                <table width="400" border="0" class="normal">
                                    <tbody>
                                        <tr>
                                            <td><a href="show.php">My Courses</a></td>
                                            <td><a href="">Documents</a></td>
                                            <td><a href="">Grades</a></td>
                                            <td><a href="index.php">Log Off</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </font>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3"><hr style="border-top: 2px solid #000; margin: 0;"></td>
    </tr>
<?php endif; ?>
