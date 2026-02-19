<!DOCTYPE html>
<html>

<head>
    <title>Worker Attendance - Database Error</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Database Error</h2>

        <p>There was an error connecting to the database.</p>
        <p>The database must be installed.</p>
        <p>MySQL must be running.</p>
        <p>Error Message: <?php echo $error_message; ?></p>

        <p><a href="index.php">Back to Home</a></p>
    </main>

    <?php include("footer.php"); ?> 

</body>
</html>
