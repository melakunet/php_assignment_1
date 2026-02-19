<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // Get filter type if selected
    $filter_type = filter_input(INPUT_GET, 'type');

    // Build query based on filter
    if ($filter_type && $filter_type != 'all') {
        $query = 'SELECT * FROM email_logs WHERE email_type = :type ORDER BY sent_date DESC LIMIT 50';
        $statement = $db->prepare($query);
        $statement->bindValue(':type', $filter_type);
    } else {
        $query = 'SELECT * FROM email_logs ORDER BY sent_date DESC LIMIT 50';
        $statement = $db->prepare($query);
    }

    $statement->execute();
    $email_logs = $statement->fetchAll();
    $statement->closeCursor();

    // Get distinct email types for filter dropdown
    $queryTypes = 'SELECT DISTINCT email_type FROM email_logs ORDER BY email_type';
    $statement = $db->prepare($queryTypes);
    $statement->execute();
    $email_types = $statement->fetchAll();
    $statement->closeCursor();

    // Get statistics
    $queryStats = 'SELECT 
                    COUNT(*) as total_emails,
                    SUM(CASE WHEN email_type = "Welcome" THEN 1 ELSE 0 END) as welcome_count,
                    SUM(CASE WHEN email_type = "Late Notification" THEN 1 ELSE 0 END) as late_count,
                    SUM(CASE WHEN email_type = "Daily Summary" THEN 1 ELSE 0 END) as summary_count
                   FROM email_logs';
    $statement = $db->prepare($queryStats);
    $statement->execute();
    $stats = $statement->fetch();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Email Logs - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Email Logs (Local Demo System)</h2>
        
        <p class="alert alert-info">
            <strong>ℹ️ Note:</strong> This is a local demo system. Emails are logged to the database instead of being sent via SMTP. 
            This demonstrates email functionality without requiring external email server configuration.
        </p>

        <!-- Statistics -->
        <div class="email-stats-container">
            <div class="email-stat-card">
                <div class="email-stat-number"><?php echo $stats['total_emails']; ?></div>
                <div>Total Emails Logged</div>
            </div>
            <div class="email-stat-card">
                <div class="email-stat-number"><?php echo $stats['welcome_count']; ?></div>
                <div>Welcome Emails</div>
            </div>
            <div class="email-stat-card">
                <div class="email-stat-number"><?php echo $stats['late_count']; ?></div>
                <div>Late Notifications</div>
            </div>
            <div class="email-stat-card">
                <div class="email-stat-number"><?php echo $stats['summary_count']; ?></div>
                <div>Daily Summaries</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="get" action="view_email_logs.php">
                <label><strong>Filter by Type:</strong></label>
                <select name="type" onchange="this.form.submit()">
                    <option value="all" <?php if (!$filter_type || $filter_type == 'all') echo 'selected'; ?>>All Email Types</option>
                    <?php foreach ($email_types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type['email_type']); ?>"
                                <?php if ($filter_type == $type['email_type']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($type['email_type']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Email Logs Table -->
        <h3>Email Log Entries (Last 50)</h3>
        <?php if (count($email_logs) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Date/Time</th>
                    <th>Type</th>
                    <th>To</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Preview</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                </tr>

                <?php foreach ($email_logs as $log): 
                    $typeClass = 'type-' . strtolower(str_replace(' ', '-', $log['email_type']));
                ?>
                    <tr>
                        <td><?php echo $log['email_log_id']; ?></td>
                        <td><?php echo date('M j, Y g:i A', strtotime($log['sent_date'])); ?></td>
                        <td>
                            <span class="email-type-badge <?php echo $typeClass; ?>">
                                <?php echo htmlspecialchars($log['email_type']); ?>
                            </span>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($log['to_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($log['to_address']); ?></small>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($log['from_name']); ?><br>
                            <small><?php echo htmlspecialchars($log['from_address']); ?></small>
                        </td>
                        <td><strong><?php echo htmlspecialchars($log['subject']); ?></strong></td>
                        <td>
                            <div class="email-preview">
                                <?php echo substr(strip_tags($log['body']), 0, 100) . '...'; ?>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($log['status']); ?></td>
                        <td>
                            <form action="view_email_preview.php" method="post" class="inline-form">
                                <input type="hidden" name="email_log_id" value="<?php echo $log['email_log_id']; ?>" />
                                <input type="submit" value="View Full" class="btn-primary" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="alert alert-danger">
                No email logs found.
            </p>
        <?php endif; ?>

        <p class="mt-30">
            <a href="send_daily_summary_form.php" class="btn-link btn-success">Send Daily Summary</a>
            <a href="index.php" class="ml-10">← Back to Home</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
