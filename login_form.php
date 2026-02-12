<?php
    session_start();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Worker Attendance System - Login</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Login</h2>

            <?php
                if (isset($_SESSION['login_error'])) {
                    echo '<p style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['login_error']) . '</p>';
                    unset($_SESSION['login_error']);
                }
            ?>

            <form action="login.php" method="post" id="login_form" >

                <div id="data">

                    <label>Username:</label>
                    <input type="text" name="user_name" /><br />

                    <label>Password:</label>
                    <input type="password" name="password" /><br />                                                         

                </div>

                <div id="buttons">
                   <label>&nbsp;</label>
                   <input type="submit" value="Login" /><br /> 
                </div>

            </form>            

            <p><a href="register_user_form.php">Register</a></p>

        </main>

        <?php include("footer.php"); ?> 

    </body>
</html>
