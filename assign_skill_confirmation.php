<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $worker_name = $_SESSION['skill_assigned_worker'] ?? 'Unknown';
    $skill_name = $_SESSION['skill_assigned_skill'] ?? 'Unknown';
    $proficiency_level = $_SESSION['skill_assigned_level'] ?? 'Unknown';
    
    unset($_SESSION['skill_assigned_worker']);
    unset($_SESSION['skill_assigned_skill']);
    unset($_SESSION['skill_assigned_level']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Skill Assigned - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Skill Successfully Assigned!</h2>
        
        <div class="alert alert-success">
            <p><strong>✓ Worker:</strong> <?php echo htmlspecialchars($worker_name); ?></p>
            <p><strong>✓ Skill:</strong> <?php echo htmlspecialchars($skill_name); ?></p>
            <p><strong>✓ Proficiency Level:</strong> <?php echo htmlspecialchars($proficiency_level); ?></p>
        </div>

        <p>
            <a href="worker_skills_report.php" class="btn-link btn-success">View Worker Skills Report</a>
            <a href="assign_skill_form.php" class="btn-link btn-primary ml-10">Assign Another Skill</a>
            <a href="index.php" class="ml-10">Back to Home</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
