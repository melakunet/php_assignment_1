<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Skill - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Add New Skill</h2>
        
        <form action="add_skill.php" method="post">
            <label>Skill Name:</label>
            <input type="text" name="skill_name" required placeholder="e.g., Data Entry"><br>
            
            <label>Category:</label>
            <select name="skill_category" required>
                <option value="">Select Category</option>
                <option value="Soft Skills">Soft Skills</option>
                <option value="Technical">Technical</option>
                <option value="Management">Management</option>
                <option value="Other">Other</option>
            </select><br>
            
            <label>Description:</label>
            <textarea name="description" rows="4" placeholder="Brief description of this skill"></textarea><br>
            
            <input type="submit" value="Add Skill">
            <a href="manage_skills.php" class="ml-10">Cancel</a>
        </form>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
