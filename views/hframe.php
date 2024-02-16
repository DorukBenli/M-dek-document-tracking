<?php
// Check if the user is logged in
include_once '../helper.php';
// Start session to check for error messages
?>

<tr>
    <td valign="top" height="30">
        <table width="100%">
            <tbody>
                <tr>
                    <td rowspan="2" width="50"><img src="../images/logos/projects.jpg" width="48" height="48"></td>
                    <td valign="top">
                        <font class="logoTitle"><strong>Welcome <?php echo $username; ?></strong></font>
                    </td>
                    <td align="right" valign="top" rowspan="2">
                        <br>
                        <font class="normal">
                            <script>
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
                            </script>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <font class="logoText">
                            <table width="400" border="0" class="normal">
                                <tbody>
                                    <tr>
                                        <td>
                                            <form action="../routes/router.php?action=store_term_session" method="post" name="store_term_seesion">
                                                <input type="hidden" name="hframe_term" value="<?php echo $_SESSION['selected_term']; ?>">
                                                <input type="submit" value="My Courses">
                                            </form>
                                        </td>
                                        <td>
                                            <form action="../routes/router.php?action=logout" method="post" name="logout">
                                                <input type="submit" value="Log Off">
                                            </form>
                                        </td>
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
    <td colspan="3">
        <hr style="border-top: 2px solid #000; margin: 0;">
    </td>
</tr>