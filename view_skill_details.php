<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    $skill_id = filter_input(INPUT_POST, 'skill_id', FILTER_VALIDATE_INT);

    if (!$skill_id) {
        header("Location: manage_skills.php");
        exit();
    }

    // Get skill details
    $querySkill = 'SELECT * FROM skills WHERE skill_id = :skill_id';
    $statement = $db->prepare($querySkill);
    $statement->bindValue(':skill_id', $skill_id);
    $statement->execute();
    $skill = $statement->fetch();
    $statement->closeCursor();

    if (!$skill) {
        echo "Skill not found.";
        exit();
    }

    // Get all workers who have this skill
    $queryWorkers = 'SELECT 
                        w.worker_id,
                        w.full_name,
                        w.email,
                        w.phone,
                        d.department_name,
                        ws.proficiency_level,
                        ws.date_acquired
                     FROM worker_skills ws
                     INNER JOIN workers w ON ws.worker_id = w.worker_id
                     LEFT JOIN departments d ON w.department_id = d.department_id
                     WHERE ws.skill_id = :skill_id
                     ORDER BY ws.proficiency_level DESC, w.full_name';
    
    $statement = $db->prepare($queryWorkers);
    $statement->bindValue(':skill_id', $skill_id);
    $statement->execute();
    $workers = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Skill Details - <?php echo htmlspecialchars($skill['skill_name']); ?></title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Skill Details</h2>

        <div class="skill-header">
            <h3><?php echo htmlspecialchars($skill['skill_name']); ?></h3>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($skill['skill_category']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($skill['description'] ?? 'No description provided'); ?></p>
            <p><strong>Total Workers with this Skill:</strong> 
                <span class="count-badge">
                    <?php echo count($workers); ?>
                </span>
            </p>
        </div>

        <?php if (count($workers) > 0): ?>
            <h3>Workers with this Skill</h3>
            <table>
                <tr>
                    <th>Worker Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Proficiency Level</th>
                    <th>Date Acquired</th>
                    <th>&nbsp;</th>
                </tr>

                <?php foreach ($workers as $worker): 
                    $proficiencyClass = 'proficiency-' . strtolower($worker['proficiency_level']);
                ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($worker['full_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($worker['email']); ?></td>
                        <td><?php echo htmlspecialchars($worker['phone']); ?></td>
                        <td><?php echo htmlspecialchars($worker['department_name'] ?? 'N/A'); ?></td>
                        <td class="text-center">
                            <span class="proficiency-badge <?php echo $proficiencyClass; ?>">
                                <?php echo htmlspecialchars($worker['proficiency_level']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($worker['date_acquired'])); ?></td>
                        <td>
                            <form action="worker_details.php" method="post" class="inline-form">
                                <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                                <input type="submit" value="View Worker" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="alert alert-warning">
                No workers currently have this skill assigned.
            </p>
        <?php endif; ?>

        <p class="mt-30">
            <a href="manage_skills.php">← Back to Skills Management</a> | 
            <a href="assign_skill_form.php">Assign this Skill to a Worker</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
