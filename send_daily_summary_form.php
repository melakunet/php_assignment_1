<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // Get today's date by default
    $selected_date = filter_input(INPUT_GET, 'date') ?? date('Y-m-d');

?>

<!DOCTYPE html>
<html>

<head>
    <title>Send Daily Summary - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Send Daily Attendance Summary Email</h2>
        
        <p class="alert alert-info">
            <strong>ℹ️ Note:</strong> This will log a summary email to the database showing attendance statistics for the selected date.
        </p>

        <form action="send_daily_summary.php" method="post">
            <label>Select Date:</label>
            <input type="date" name="summary_date" value="<?php echo htmlspecialchars($selected_date); ?>" required><br>
            
            <label>Send To (Manager Email):</label>
            <input type="email" name="manager_email" value="manager@company.com" required><br>
            
            <label>Manager Name:</label>
            <input type="text" name="manager_name" value="HR Manager" required><br>
            
            <input type="submit" value="Generate & Log Summary Email">
            <a href="view_email_logs.php" class="ml-10">Cancel</a>
        </form>

        <div class="info-box">
            <h3>What will be included:</h3>
            <ul>
                <li>Total workers present</li>
                <li>Total workers late</li>
                <li>Total workers absent</li>
                <li>List of late arrivals with times</li>
                <li>List of absent workers</li>
            </ul>
        </div>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
