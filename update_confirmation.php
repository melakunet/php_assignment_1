<?php
    session_start();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Worker Attendance System - Update Worker Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Update Worker Confirmation</h2>
            <p>
                Thank you, <?php echo $_SESSION["fullName"]; ?> for
                updating your worker information.
                We look forward to continuing to work with you.
            </p>

            <p><a href="index.php">Back to Home</a></p>

        </main>

        <?php include("footer.php"); ?>

    </body>
</html>
