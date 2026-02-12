<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);

    if (!$worker_id) {
        header("Location: index.php");
        exit();
    }

    $query = '
        SELECT w.worker_id, w.full_name, w.phone, w.email, w.hire_date, 
               w.image_filename, w.department_id, d.department_name, d.location
        FROM workers w
        LEFT JOIN departments d ON w.department_id = d.department_id
        WHERE w.worker_id = :worker_id
    ';

    $statement = $db->prepare($query);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $worker = $statement->fetch();
    $statement->closeCursor();

    if (!$worker) {
        echo "Worker not found.";
        exit();
    }

    $queryAttendance = '
        SELECT date, scheduled_time, check_in_time, status
        FROM attendance
        WHERE worker_id = :worker_id
        ORDER BY date DESC
        LIMIT 10
    ';
    
    $statement = $db->prepare($queryAttendance);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $attendance_records = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Worker Details - <?php echo htmlspecialchars($worker['full_name']); ?></title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Worker Details</h2>

        <div class="worker-details-container">
            <div class="worker-image">
                <?php if (!empty($worker['image_filename'])): ?>
                    <img src="images/<?php echo htmlspecialchars($worker['image_filename']); ?>" 
                         alt="<?php echo htmlspecialchars($worker['full_name']); ?>" 
                         class="worker-photo">
                <?php else: ?>
                    <img src="images/placeholder.png" 
                         alt="No photo" 
                         class="worker-photo">
                <?php endif; ?>
            </div>

            <!-- Worker Information -->
            <div class="worker-info">
                <h3><?php echo htmlspecialchars($worker['full_name']); ?></h3>
                
                <table class="details-table">
                    <tr>
                        <th>Worker ID:</th>
                        <td><?php echo htmlspecialchars($worker['worker_id']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?php echo htmlspecialchars($worker['phone'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($worker['email'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Department:</th>
                        <td><?php echo htmlspecialchars($worker['department_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Location:</th>
                        <td><?php echo htmlspecialchars($worker['location'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Hire Date:</th>
                        <td><?php echo $worker['hire_date'] ? date('F j, Y', strtotime($worker['hire_date'])) : 'N/A'; ?></td>
                    </tr>
                </table>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <form action="update_worker_form.php" method="post" style="display: inline;">
                        <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                        <input type="submit" value="Update Worker" class="btn-update" />
                    </form>

                    <form action="delete_worker.php" method="post" style="display: inline;" 
                          onsubmit="return confirm('Are you sure you want to delete this worker?');">
                        <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                        <input type="submit" value="Delete Worker" class="btn-delete" />
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="attendance-section">
            <h3>Recent Attendance History</h3>
            
            <?php if (count($attendance_records) > 0): ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Scheduled Time</th>
                        <th>Check-In Time</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($attendance_records as $record): ?>
                        <tr>
                            <td><?php echo date('M j, Y', strtotime($record['date'])); ?></td>
                            <td><?php echo htmlspecialchars($record['scheduled_time']); ?></td>
                            <td><?php echo htmlspecialchars($record['check_in_time'] ?? 'N/A'); ?></td>
                            <td class="status-<?php echo strtolower($record['status']); ?>">
                                <?php echo htmlspecialchars($record['status']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No attendance records found for this worker.</p>
            <?php endif; ?>
        </div>

        <p><a href="index.php" class="back-link">&larr; Back to Worker List</a></p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
