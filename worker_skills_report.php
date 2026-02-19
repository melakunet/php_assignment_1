<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // Get all workers with their skills using MANY-TO-MANY JOIN
    $queryWorkerSkills = 'SELECT 
                            w.worker_id,
                            w.full_name,
                            w.email,
                            d.department_name,
                            s.skill_name,
                            s.skill_category,
                            ws.proficiency_level,
                            ws.date_acquired
                          FROM workers w
                          INNER JOIN worker_skills ws ON w.worker_id = ws.worker_id
                          INNER JOIN skills s ON ws.skill_id = s.skill_id
                          LEFT JOIN departments d ON w.department_id = d.department_id
                          ORDER BY w.full_name, s.skill_name';
    
    $statement = $db->prepare($queryWorkerSkills);
    $statement->execute();
    $workerSkills = $statement->fetchAll();
    $statement->closeCursor();

    // Get worker summary (workers with skill counts)
    $querySummary = 'SELECT 
                        w.worker_id,
                        w.full_name,
                        d.department_name,
                        COUNT(ws.skill_id) as total_skills,
                        GROUP_CONCAT(s.skill_name ORDER BY s.skill_name SEPARATOR ", ") as skills_list
                     FROM workers w
                     LEFT JOIN worker_skills ws ON w.worker_id = ws.worker_id
                     LEFT JOIN skills s ON ws.skill_id = s.skill_id
                     LEFT JOIN departments d ON w.department_id = d.department_id
                     GROUP BY w.worker_id, w.full_name, d.department_name
                     ORDER BY total_skills DESC, w.full_name';
    
    $statement = $db->prepare($querySummary);
    $statement->execute();
    $workerSummary = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Worker Skills Report - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Worker Skills Report (Many-to-Many Relationship)</h2>
        
        <div class="skills-action-buttons">
            <a href="manage_skills.php" class="btn-manage-skills">Manage Skills</a>
            <a href="assign_skill_form.php" class="btn-assign-skill">Assign Skill to Worker</a>
        </div>

        <!-- Summary Table -->
        <h3>Skills Summary by Worker</h3>
        <table>
            <tr>
                <th>Worker Name</th>
                <th>Department</th>
                <th>Total Skills</th>
                <th>Skills List</th>
                <th>&nbsp;</th>
            </tr>

            <?php foreach ($workerSummary as $worker): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($worker['full_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($worker['department_name'] ?? 'N/A'); ?></td>
                    <td class="text-center">
                        <span class="skill-count-badge">
                            <?php echo $worker['total_skills']; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($worker['skills_list'] ?? 'No skills assigned'); ?></td>
                    <td>
                        <form action="assign_skill_form.php" method="post" class="inline-form">
                            <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                            <input type="submit" value="Assign Skill" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Detailed Skills Table -->
        <h3 class="section-spacing">Detailed Worker Skills (<?php echo count($workerSkills); ?> assignments)</h3>
        <table>
            <tr>
                <th>Worker Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Skill Name</th>
                <th>Category</th>
                <th>Proficiency Level</th>
                <th>Date Acquired</th>
                <th>&nbsp;</th>
            </tr>

            <?php 
            $currentWorker = '';
            foreach ($workerSkills as $ws): 
                $proficiencyClass = 'proficiency-' . strtolower($ws['proficiency_level']);
            ?>
                <tr>
                    <td>
                        <?php 
                        // Only show worker name if different from previous row (grouped display)
                        if ($currentWorker != $ws['full_name']) {
                            echo '<strong>' . htmlspecialchars($ws['full_name']) . '</strong>';
                            $currentWorker = $ws['full_name'];
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($ws['email']); ?></td>
                    <td><?php echo htmlspecialchars($ws['department_name'] ?? 'N/A'); ?></td>
                    <td><strong><?php echo htmlspecialchars($ws['skill_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($ws['skill_category']); ?></td>
                    <td class="text-center">
                        <span class="proficiency-badge <?php echo $proficiencyClass; ?>">
                            <?php echo htmlspecialchars($ws['proficiency_level']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($ws['date_acquired'])); ?></td>
                    <td>
                        <form action="remove_worker_skill.php" method="post" class="inline-form">
                            <input type="hidden" name="worker_id" value="<?php echo $ws['worker_id']; ?>" />
                            <input type="hidden" name="skill_name" value="<?php echo $ws['skill_name']; ?>" />
                            <input type="submit" value="Remove" onclick="return confirm('Remove this skill from <?php echo htmlspecialchars($ws['full_name']); ?>?');" class="skill-remove-btn" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p class="back-section"><a href="index.php">← Back to Home</a></p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
