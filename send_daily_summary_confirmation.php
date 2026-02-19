<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $summary_sent = $_SESSION['summary_sent'] ?? false;
    $summary_date = $_SESSION['summary_date'] ?? 'Unknown';
    
    unset($_SESSION['summary_sent']);
    unset($_SESSION['summary_date']);

    if (!$summary_sent) {
        header("Location: send_daily_summary_form.php");
        exit();
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Summary Sent - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Daily Summary Email Logged Successfully!</h2>
        
        <div class="alert alert-success">
            <p><strong>✓ Daily attendance summary has been logged to the database.</strong></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($summary_date))); ?></p>
            <p><strong>Status:</strong> Email logged successfully (Local Demo Mode)</p>
        </div>

        <div class="alert alert-info">
            <p><strong>ℹ️ Local Demo Mode:</strong> In a production environment, this email would be sent via SMTP to the manager. 
            In this local demo, the email has been saved to the database for review.</p>
        </div>

        <p>
            <a href="view_email_logs.php" class="btn-link btn-success">View Email Logs</a>
            <a href="send_daily_summary_form.php" class="btn-link btn-primary ml-10">Send Another Summary</a>
            <a href="index.php" class="ml-10">Back to Home</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
