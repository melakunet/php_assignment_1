<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $skill_added = $_SESSION['skill_added'] ?? 'Unknown';
    unset($_SESSION['skill_added']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Skill Added - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Skill Successfully Added!</h2>
        
        <div class="alert alert-success">
            <p><strong>✓ Skill Added:</strong> <?php echo htmlspecialchars($skill_added); ?></p>
        </div>

        <p>
            <a href="manage_skills.php" class="btn-link btn-success">View All Skills</a>
            <a href="add_skill_form.php" class="btn-link btn-primary ml-10">Add Another Skill</a>
            <a href="index.php" class="ml-10">Back to Home</a>
        </p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
