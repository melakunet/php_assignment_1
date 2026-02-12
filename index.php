<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    $queryWorkers = 'SELECT w.*, d.department_name 
                     FROM workers w 
                     LEFT JOIN departments d ON w.department_id = d.department_id 
                     ORDER BY w.full_name';
    $statement = $db->prepare($queryWorkers);
    $statement->execute();
    $workers = $statement->fetchAll();
    $statement->closeCursor();

    $today = date('Y-m-d');
    $queryAttendance = 'SELECT w.full_name, d.department_name, a.scheduled_time, a.check_in_time, a.status 
                        FROM attendance a 
                        JOIN workers w ON a.worker_id = w.worker_id 
                        LEFT JOIN departments d ON w.department_id = d.department_id
                        WHERE a.date = :date 
                        ORDER BY a.check_in_time';
    $statement = $db->prepare($queryAttendance);
    $statement->bindValue(':date', $today);
    $statement->execute();
    $attendance_records = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Worker List (<?php echo "Logged In User: " . $_SESSION['userName']; ?>)</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Department</th>
                <th>Photo</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php foreach ($workers as $worker): ?>
                <tr>
                    <td><?php echo $worker['worker_id']; ?></td>
                    <td><?php echo htmlspecialchars($worker['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($worker['phone']); ?></td>
                    <td><?php echo htmlspecialchars($worker['email'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($worker['department_name'] ?? 'N/A'); ?></td>
                    <td>
                        <img src="images/<?php echo htmlspecialchars($worker['image_filename']); ?>" 
                             alt="<?php echo htmlspecialchars($worker['full_name']); ?>" />
                    </td>
                    <td>
                        <form action="update_worker_form.php" method="post">
                            <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                            <input type="submit" value="Update" />
                        </form>
                    </td>
                    <td>
                        <form action="delete_worker.php" method="post">
                            <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                    <td>
                        <form action="worker_details.php" method="post">
                            <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                            <input type="submit" value="View Details" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

        <p><a href="add_worker_form.php">Add Worker</a></p>

        <br><br>

        <h2>Today's Attendance</h2>
        <table>
            <tr>
                <th>Worker Name</th>
                <th>Department</th>
                <th>Scheduled Time</th>
                <th>Check-In Time</th>
                <th>Status</th>
            </tr>

            <?php foreach ($attendance_records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['department_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($record['scheduled_time']); ?></td>
                    <td><?php echo htmlspecialchars($record['check_in_time'] ?? 'N/A'); ?></td>
                    <td class="status-<?php echo strtolower($record['status']); ?>">
                        <?php echo htmlspecialchars($record['status']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    </main>

    <?php include("footer.php"); ?> 

</body>
</html>
