<?php
    require("database.php");


    // get data from the form
    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);


    $queryWorkers = '
        SELECT worker_id, full_name, phone, role FROM workers WHERE worker_id = :worker_id';

    $statement = $db->prepare($queryWorkers);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $worker = $statement->fetch();
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

            <form action="update_worker.php" method="post" id="update_worker_form">
                <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>" />
                <div id="data">

                    <label>Full Name:</label>
                    <input type="text" name="full_name" value="<?php echo $worker['full_name']; ?>" /><br />

                    <label>Phone:</label>
                    <input type="text" name="phone" value="<?php echo $worker['phone']; ?>" /><br />

                    <label>Role:</label>
                    <select name="role">
                        <option value="Manager" <?php if ($worker['role'] == 'Manager') echo 'selected'; ?>>Manager</option>
                        <option value="Sales" <?php if ($worker['role'] == 'Sales') echo 'selected'; ?>>Sales</option>
                        <option value="Cashier" <?php if ($worker['role'] == 'Cashier') echo 'selected'; ?>>Cashier</option>
                        <option value="Stock" <?php if ($worker['role'] == 'Stock') echo 'selected'; ?>>Stock</option>
                    </select><br />

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
