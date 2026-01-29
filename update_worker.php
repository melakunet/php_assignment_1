<?php
    session_start();

    require('database.php');

    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);

    $full_name = filter_input(INPUT_POST, 'full_name');
    $phone = filter_input(INPUT_POST, 'phone');
    $role = filter_input(INPUT_POST, 'role');

    
    if ($full_name == null || $phone == null || $role == null) {
        $_SESSION["add_error"] = "Invalid worker data, Check all fields and try again.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    
    // Update Worker

    $query = '
        UPDATE workers
        SET full_name = :full_name,
            phone = :phone,
            role = :role
        WHERE worker_id = :worker_id
    ';

    $statement = $db->prepare($query);
    $statement->bindValue(':full_name', $full_name);
    $statement->bindValue(':phone', $phone);
    $statement->bindValue(':role', $role);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["fullName"] = $full_name;
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die();

?>
