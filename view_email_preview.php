<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    $email_log_id = filter_input(INPUT_POST, 'email_log_id', FILTER_VALIDATE_INT);

    if (!$email_log_id) {
        header("Location: view_email_logs.php");
        exit();
    }

    $query = 'SELECT * FROM email_logs WHERE email_log_id = :email_log_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':email_log_id', $email_log_id);
    $statement->execute();
    $email = $statement->fetch();
    $statement->closeCursor();

    if (!$email) {
        echo "Email log not found.";
        exit();
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Email Preview - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Email Preview</h2>

        <div class="email-header-box">
            <table>
                <tr>
                    <th>Email ID:</th>
                    <td><?php echo $email['email_log_id']; ?></td>
                </tr>
                <tr>
                    <th>Type:</th>
                    <td><strong><?php echo htmlspecialchars($email['email_type']); ?></strong></td>
                </tr>
                <tr>
                    <th>Date/Time:</th>
                    <td><?php echo date('l, F j, Y \a\t g:i A', strtotime($email['sent_date'])); ?></td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td><?php echo htmlspecialchars($email['status']); ?></td>
                </tr>
                <tr>
                    <th>From:</th>
                    <td>
                        <strong><?php echo htmlspecialchars($email['from_name']); ?></strong><br>
                        &lt;<?php echo htmlspecialchars($email['from_address']); ?>&gt;
                    </td>
                </tr>
                <tr>
                    <th>To:</th>
                    <td>
                        <strong><?php echo htmlspecialchars($email['to_name']); ?></strong><br>
                        &lt;<?php echo htmlspecialchars($email['to_address']); ?>&gt;
                    </td>
                </tr>
                <tr>
                    <th>Subject:</th>
                    <td><strong><?php echo htmlspecialchars($email['subject']); ?></strong></td>
                </tr>
            </table>
        </div>

        <h3>Email Body:</h3>
        <div class="email-body">
            <!-- Display email body as HTML in iframe for safety -->
            <iframe srcdoc="<?php echo htmlspecialchars($email['body']); ?>"></iframe>
        </div>

        <p class="mt-30">
            <a href="view_email_logs.php">← Back to Email Logs</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
