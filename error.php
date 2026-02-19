<?php
    session_start();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Worker Attendance System - Error</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Error</h2>
            <p>
                <?php echo $_SESSION["add_error"]; ?>
            </p>

            <p><a href="index.php">Back to Home</a></p>

        </main>

        <?php include("footer.php"); ?>

    </body>
</html>
