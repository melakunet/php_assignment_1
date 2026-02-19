<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $notification_sent = $_SESSION['notification_sent'] ?? false;
    $worker_name = $_SESSION['notification_worker'] ?? 'Unknown';
    
    unset($_SESSION['notification_sent']);
    unset($_SESSION['notification_worker']);

    if (!$notification_sent) {
        header("Location: send_late_notification_form.php");
        exit();
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Notification Sent - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Late Notification Email Logged Successfully!</h2>
        
        <div class="alert alert-warning">
            <p><strong>⚠️ Late arrival notification has been logged to the database.</strong></p>
            <p><strong>Worker:</strong> <?php echo htmlspecialchars($worker_name); ?></p>
            <p><strong>Status:</strong> Email logged successfully (Local Demo Mode)</p>
        </div>

        <div class="alert alert-info">
            <p><strong>ℹ️ Local Demo Mode:</strong> In a production environment, this email would be sent via SMTP to the worker. 
            In this local demo, the email has been saved to the database for review.</p>
        </div>

        <p>
            <a href="view_email_logs.php" class="btn-link btn-success">View Email Logs</a>
            <a href="send_late_notification_form.php" class="btn-link btn-warning ml-10">Send Another Notification</a>
            <a href="index.php" class="ml-10">Back to Home</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
