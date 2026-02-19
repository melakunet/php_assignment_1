<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // Get worker_id if passed from another page
    $preselected_worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);

    // Get all workers
    $queryWorkers = 'SELECT w.worker_id, w.full_name, d.department_name 
                     FROM workers w 
                     LEFT JOIN departments d ON w.department_id = d.department_id 
                     ORDER BY w.full_name';
    $statement = $db->prepare($queryWorkers);
    $statement->execute();
    $workers = $statement->fetchAll();
    $statement->closeCursor();

    // Get all skills
    $querySkills = 'SELECT * FROM skills ORDER BY skill_category, skill_name';
    $statement = $db->prepare($querySkills);
    $statement->execute();
    $skills = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Assign Skill to Worker - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Assign Skill to Worker</h2>
        
        <form action="assign_skill.php" method="post">
            <label>Select Worker:</label>
            <select name="worker_id" required>
                <option value="">Choose a worker...</option>
                <?php foreach ($workers as $worker): ?>
                    <option value="<?php echo $worker['worker_id']; ?>"
                            <?php if ($preselected_worker_id == $worker['worker_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($worker['full_name']); ?> 
                        - <?php echo htmlspecialchars($worker['department_name'] ?? 'No Dept'); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            
            <label>Select Skill:</label>
            <select name="skill_id" required>
                <option value="">Choose a skill...</option>
                <?php 
                $currentCategory = '';
                foreach ($skills as $skill): 
                    // Add optgroup headers for categories
                    if ($currentCategory != $skill['skill_category']) {
                        if ($currentCategory != '') echo '</optgroup>';
                        $currentCategory = $skill['skill_category'];
                        echo '<optgroup label="' . htmlspecialchars($currentCategory) . '">';
                    }
                ?>
                    <option value="<?php echo $skill['skill_id']; ?>">
                        <?php echo htmlspecialchars($skill['skill_name']); ?>
                    </option>
                <?php 
                endforeach; 
                if ($currentCategory != '') echo '</optgroup>';
                ?>
            </select><br>
            
            <label>Proficiency Level:</label>
            <select name="proficiency_level" required>
                <option value="">Select level...</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate" selected>Intermediate</option>
                <option value="Advanced">Advanced</option>
                <option value="Expert">Expert</option>
            </select><br>
            
            <label>Date Acquired:</label>
            <input type="date" name="date_acquired" value="<?php echo date('Y-m-d'); ?>" required><br>
            
            <input type="submit" value="Assign Skill">
            <a href="worker_skills_report.php" class="ml-10">Cancel</a>
        </form>

        <p class="alert alert-info">
            <strong>Note:</strong> You cannot assign the same skill to a worker twice. 
            If the worker already has this skill, you'll need to remove it first before reassigning.
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
