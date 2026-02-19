<!DOCTYPE html>
<html>

    <head>
        <title>Worker Attendance System - Register User</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Register User</h2>

            <form action="register_user.php" method="post" id="register_user_form" >

                <div id="data">

                    <label>Username:</label>
                    <input type="text" name="user_name" /><br />

                    <label>Password:</label>
                    <input type="password" name="password" /><br />

                    <label>Email Address:</label>
                    <input type="text" name="email_address" /><br />                                     

                </div>

                <div id="buttons">
                   <label>&nbsp;</label>
                   <input type="submit" value="Register" /><br /> 
                </div>

            </form>            

            <p><a href="index.php">View Worker List</a></p>

        </main>

        <?php include("footer.php"); ?> 

    </body>
</html>
