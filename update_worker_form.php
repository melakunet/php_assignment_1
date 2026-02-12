<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);

    $queryWorkers = '
        SELECT w.*, d.department_name 
        FROM workers w
        LEFT JOIN departments d ON w.department_id = d.department_id
        WHERE w.worker_id = :worker_id';

    $statement = $db->prepare($queryWorkers);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $worker = $statement->fetch();
    $statement->closeCursor();

    $queryDepartments = 'SELECT * FROM departments ORDER BY department_name';
    $statement = $db->prepare($queryDepartments);
    $statement->execute();
    $departments = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Worker Attendance System - Update Worker</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Update Worker</h2>

            <form action="update_worker.php" method="post" id="update_worker_form" enctype="multipart/form-data">
                <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                
                <div id="data">

                    <label>Full Name:</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($worker['full_name']); ?>" required /><br />

                    <label>Phone:</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($worker['phone']); ?>" required /><br />

                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($worker['email'] ?? ''); ?>" /><br />

                    <label>Department:</label>
                    <select name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['department_id']; ?>" 
                                <?php if ($worker['department_id'] == $dept['department_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($dept['department_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br />

                    <label>Hire Date:</label>
                    <input type="date" name="hire_date" value="<?php echo $worker['hire_date']; ?>" /><br />

                    <?php if (!empty($worker['image_filename'])): ?>
                        <label>Current Image:</label>
                        <img src="images/<?php echo htmlspecialchars($worker['image_filename']); ?>" height="100"><br />                        
                    <?php endif; ?>

                    <label>Update Image:</label>
                    <input type="file" name="worker_image" /><br />

                </div>

                <div id="buttons">
                    <label>&nbsp;</label>
                    <input type="submit" value="Update Worker" /><br />
                </div>

            </form>

            <p><a href="index.php">View Worker List</a></p>

        </main>

        <?php include("footer.php"); ?>

    </body>
</html>
