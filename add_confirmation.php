<?php
    session_start();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Worker Attendance System - Add Worker Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Add Worker Confirmation</h2>
            
            <?php if (isset($_SESSION["image_filename"]) && $_SESSION["image_filename"]): ?>
                <div class="text-center">
                    <img src="images/<?php echo htmlspecialchars($_SESSION["image_filename"]); ?>" 
                         alt="Worker Photo" 
                         height="100">
                </div>
            <?php endif; ?>
            
            <p>
                Thank you, <?php echo htmlspecialchars($_SESSION["fullName"]); ?> for
                saving your worker information.
                We look forward to working with you.
            </p>

            <p><a href="index.php">Back to Home</a></p>

        </main>

        <?php include("footer.php"); ?>

    </body>
</html>
