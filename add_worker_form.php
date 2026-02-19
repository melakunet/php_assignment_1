<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    $queryDepartments = 'SELECT * FROM departments ORDER BY department_name';
    $statement = $db->prepare($queryDepartments);
    $statement->execute();
    $departments = $statement->fetchAll();
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Worker Attendance System - Add Worker</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=1.0" />
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Add New Worker</h2>
        
        <form action="add_worker.php" method="post" enctype="multipart/form-data">
            <label>Full Name:</label>
            <input type="text" name="full_name" required><br>
            
            <label>Phone:</label>
            <input type="text" name="phone" required><br>
            
            <label>Email:</label>
            <input type="email" name="email"><br>
            
            <label>Department:</label>
            <select name="department_id" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?php echo $dept['department_id']; ?>">
                        <?php echo htmlspecialchars($dept['department_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            
            <label>Hire Date:</label>
            <input type="date" name="hire_date" value="<?php echo date('Y-m-d'); ?>"><br>
            
            <label>Worker Photo:</label>
            <input type="file" name="worker_image" accept="image/*"><br>
            
            <input type="submit" value="Add Worker">
        </form>

        <p><a href="index.php">View Worker List</a></p>

    </main>

    <?php include("footer.php"); ?>

</body>
</html>
