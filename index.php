<?php

    require("database.php");

    // Handle worker registration
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_worker'])) {
        session_start();
        
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        
        $query = 'INSERT INTO workers (full_name, phone, role) 
                  VALUES (:full_name, :phone, :role)';
        $statement = $db->prepare($query);
        $statement->bindValue(':full_name', $full_name);
        $statement->bindValue(':phone', $phone);
        $statement->bindValue(':role', $role);
        $statement->execute();
        $statement->closeCursor();
        
        $_SESSION["fullName"] = $full_name;
        header("Location: add_confirmation.php");
        exit();
    }

    // Handle check-in
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_in'])) {
        $worker_id = $_POST['worker_id'];
        $scheduled_time = $_POST['scheduled_time'];
        $check_in_time = date('H:i:s');
        $date = date('Y-m-d');
        
        // Check if late or on time
        if (strtotime($check_in_time) <= strtotime($scheduled_time)) {
            $status = 'Present';
        } else {
            $status = 'Late';
        }
        
        $query = 'INSERT INTO attendance (worker_id, scheduled_time, check_in_time, status, date) 
                  VALUES (:worker_id, :scheduled_time, :check_in_time, :status, :date)';
        $statement = $db->prepare($query);
        $statement->bindValue(':worker_id', $worker_id);
        $statement->bindValue(':scheduled_time', $scheduled_time);
        $statement->bindValue(':check_in_time', $check_in_time);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':date', $date);
        $statement->execute();
        $statement->closeCursor();
        
        header("Location: index.php");
        exit();
    }

    // Get all workers
    $queryWorkers = 'SELECT * FROM workers ORDER BY full_name';
    $statement = $db->prepare($queryWorkers);
    $statement->execute();
    $workers = $statement->fetchAll();
    $statement->closeCursor();

    // Get today's attendance
    $today = date('Y-m-d');
    $queryAttendance = 'SELECT w.full_name, w.role, a.scheduled_time, a.check_in_time, a.status 
                        FROM attendance a 
                        JOIN workers w ON a.worker_id = w.worker_id 
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
        <!-- Worker Registration -->
        <h2>Worker Registration</h2>
        <form action="index.php" method="post">
            <label>Full Name:</label>
            <input type="text" name="full_name" required><br>
            
            <label>Phone:</label>
            <input type="text" name="phone" required><br>
            
            <label>Role:</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="Manager">Manager</option>
                <option value="Sales">Sales</option>
                <option value="Cashier">Cashier</option>
                <option value="Stock">Stock</option>
            </select><br>
            
            <input type="submit" name="register_worker" value="Register Worker">
        </form>

        <br><br>

        <!-- Check-In Form -->
        <h2>Worker Check-In</h2>
        <p>Current Time: <?php echo date('h:i:s A'); ?></p>
        <form action="index.php" method="post">
            <label>Select Worker:</label>
            <select name="worker_id" required>
                <option value="">Choose a worker</option>
                <?php foreach ($workers as $worker): ?>
                    <option value="<?php echo $worker['worker_id']; ?>">
                        <?php echo htmlspecialchars($worker['full_name']); ?> - <?php echo htmlspecialchars($worker['role']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            
            <label>Scheduled Time:</label>
            <input type="time" name="scheduled_time" value="09:00" required><br>
            
            <input type="submit" name="check_in" value="Check In">
        </form>

        <br><br>

        <!-- Today's Attendance -->
        <h2>Today's Attendance</h2>
        <table>
            <tr>
                <th>Worker Name</th>
                <th>Role</th>
                <th>Scheduled Time</th>
                <th>Check-In Time</th>
                <th>Status</th>
            </tr>

            <?php foreach ($attendance_records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['role']); ?></td>
                    <td><?php echo htmlspecialchars($record['scheduled_time']); ?></td>
                    <td><?php echo htmlspecialchars($record['check_in_time']); ?></td>
                    <td><?php echo htmlspecialchars($record['status']); ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

        <br><br>

        <!-- All Workers -->
        <h2>All Registered Workers</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Role</th>
                <th>&nbsp;</th> <!-- for update -->
                <th>&nbsp;</th> <!-- for delete -->
            </tr>

            <?php foreach ($workers as $worker): ?>
                <tr>
                    <td><?php echo $worker['worker_id']; ?></td>
                    <td><?php echo htmlspecialchars($worker['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($worker['phone']); ?></td>
                    <td><?php echo htmlspecialchars($worker['role']); ?></td>
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
                </tr>
            <?php endforeach; ?>

        </table>

    </main>

    <?php include("footer.php"); ?> 

</body>
</html>
