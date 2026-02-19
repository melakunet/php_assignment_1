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

    // Get worker's skills (Many-to-Many relationship)
    $querySkills = '
        SELECT s.skill_id, s.skill_name, s.skill_category, 
               ws.proficiency_level, ws.date_acquired
        FROM worker_skills ws
        INNER JOIN skills s ON ws.skill_id = s.skill_id
        WHERE ws.worker_id = :worker_id
        ORDER BY s.skill_category, s.skill_name
    ';
    
    $statement = $db->prepare($querySkills);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $worker_skills = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Worker Details - <?php echo htmlspecialchars($worker['full_name']); ?></title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
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
                    <form action="update_worker_form.php" method="post" class="inline-form">
                        <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                        <input type="submit" value="Update Worker" class="btn-update" />
                    </form>

                    <form action="delete_worker.php" method="post" class="inline-form" 
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

        <!-- Worker Skills Section (Many-to-Many Relationship) -->
        <div class="skills-section">
            <h3>Worker Skills (<?php echo count($worker_skills); ?> total)</h3>
            
            <?php if (count($worker_skills) > 0): ?>
                <table>
                    <tr>
                        <th>Skill Name</th>
                        <th>Category</th>
                        <th>Proficiency Level</th>
                        <th>Date Acquired</th>
                        <th>&nbsp;</th>
                    </tr>
                    <?php foreach ($worker_skills as $skill): 
                        $proficiencyClass = 'proficiency-' . strtolower($skill['proficiency_level']);
                    ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($skill['skill_category']); ?></td>
                            <td class="text-center">
                                <span class="proficiency-badge <?php echo $proficiencyClass; ?>">
                                    <?php echo htmlspecialchars($skill['proficiency_level']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($skill['date_acquired'])); ?></td>
                            <td>
                                <form action="view_skill_details.php" method="post" class="inline-form">
                                    <input type="hidden" name="skill_id" value="<?php echo $skill['skill_id']; ?>" />
                                    <input type="submit" value="View Skill" class="btn-primary" />
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                
                <p class="mt-15">
                    <form action="assign_skill_form.php" method="post" class="inline-form">
                        <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                        <input type="submit" value="+ Assign New Skill" class="btn-success" />
                    </form>
                </p>
            <?php else: ?>
                <p class="alert alert-warning">
                    This worker has no skills assigned yet.
                </p>
                <p class="mt-15">
                    <form action="assign_skill_form.php" method="post" class="inline-form">
                        <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                        <input type="submit" value="+ Assign First Skill" class="btn-success" />
                    </form>
                </p>
            <?php endif; ?>
        </div>

        <p><a href="index.php" class="back-link">&larr; Back to Worker List</a></p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
