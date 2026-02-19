<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // Get all late attendance records
    $queryLate = 'SELECT a.worker_id, w.full_name, w.email, a.date, a.scheduled_time, a.check_in_time
                  FROM attendance a
                  JOIN workers w ON a.worker_id = w.worker_id
                  WHERE a.status = "Late"
                  ORDER BY a.date DESC, w.full_name
                  LIMIT 50';
    
    $statement = $db->prepare($queryLate);
    $statement->execute();
    $late_records = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Send Late Notification - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Send Late Arrival Notifications</h2>
        
        <p class="alert alert-warning">
            <strong>⚠️ Late Notifications:</strong> Select a late arrival record below to log a notification email to the database.
        </p>

        <?php if (count($late_records) > 0): ?>
            <h3>Recent Late Arrivals</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Worker Name</th>
                    <th>Email</th>
                    <th>Scheduled Time</th>
                    <th>Check-in Time</th>
                    <th>&nbsp;</th>
                </tr>

                <?php foreach ($late_records as $record): ?>
                    <tr>
                        <td><?php echo date('M j, Y', strtotime($record['date'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($record['full_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($record['email']); ?></td>
                        <td><?php echo htmlspecialchars($record['scheduled_time']); ?></td>
                        <td class="text-warning text-bold"><?php echo htmlspecialchars($record['check_in_time']); ?></td>
                        <td>
                            <form action="send_late_notification.php" method="post" class="inline-form">
                                <input type="hidden" name="worker_id" value="<?php echo $record['worker_id']; ?>" />
                                <input type="hidden" name="attendance_date" value="<?php echo $record['date']; ?>" />
                                <input type="submit" value="Send Notification" class="btn-warning" 
                                       onclick="return confirm('Send late notification to <?php echo htmlspecialchars($record['full_name']); ?>?');" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="alert alert-success">
                No late arrival records found.
            </p>
        <?php endif; ?>

        <p class="mt-30">
            <a href="view_email_logs.php">View Email Logs</a> | 
            <a href="index.php">Back to Home</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
