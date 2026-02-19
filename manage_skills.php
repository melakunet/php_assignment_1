<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // Get all skills
    $querySkills = 'SELECT * FROM skills ORDER BY skill_category, skill_name';
    $statement = $db->prepare($querySkills);
    $statement->execute();
    $skills = $statement->fetchAll();
    $statement->closeCursor();

    // Count how many workers have each skill
    $querySkillCounts = 'SELECT s.skill_id, s.skill_name, COUNT(ws.worker_id) as worker_count
                         FROM skills s
                         LEFT JOIN worker_skills ws ON s.skill_id = ws.skill_id
                         GROUP BY s.skill_id, s.skill_name
                         ORDER BY s.skill_category, s.skill_name';
    $statement = $db->prepare($querySkillCounts);
    $statement->execute();
    $skillCounts = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Skills - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Skills Management</h2>
        
        <div class="mb-20">
            <a href="add_skill_form.php" class="btn-link btn-success">Add New Skill</a>
            <a href="worker_skills_report.php" class="btn-link btn-primary ml-10">View Worker Skills Report</a>
        </div>

        <h3>All Skills (<?php echo count($skills); ?> total)</h3>
        
        <table>
            <tr>
                <th>Skill ID</th>
                <th>Skill Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Workers with this Skill</th>
                <th>&nbsp;</th>
            </tr>

            <?php 
            $currentCategory = '';
            foreach ($skillCounts as $skill): 
                // Group by category with visual separator
                if ($currentCategory != $skill['skill_category']) {
                    $currentCategory = $skill['skill_category'];
                }
            ?>
                <tr>
                    <td><?php echo $skill['skill_id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($currentCategory); ?></td>
                    <td>
                        <?php 
                        // Find the description from the skills array
                        foreach ($skills as $s) {
                            if ($s['skill_id'] == $skill['skill_id']) {
                                echo htmlspecialchars($s['description'] ?? 'N/A');
                                break;
                            }
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <span class="count-badge">
                            <?php echo $skill['worker_count']; ?>
                        </span>
                    </td>
                    <td>
                        <form action="view_skill_details.php" method="post" class="inline-form">
                            <input type="hidden" name="skill_id" value="<?php echo $skill['skill_id']; ?>" />
                            <input type="submit" value="View Details" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p class="mt-30"><a href="index.php">← Back to Home</a></p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
