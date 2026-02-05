<?php
    session_start();
    require('database.php');
    require('image_util.php');

    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);
    $full_name = filter_input(INPUT_POST, 'full_name');
    $phone = filter_input(INPUT_POST, 'phone');
    $email = filter_input(INPUT_POST, 'email');
    $hire_date = filter_input(INPUT_POST, 'hire_date');
    $department_id = filter_input(INPUT_POST, 'department_id', FILTER_VALIDATE_INT);

    if ($full_name == null || $phone == null || $department_id == null) {
        $_SESSION["add_error"] = "Invalid worker data, Check all fields and try again.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    $queryImage = 'SELECT image_filename FROM workers WHERE worker_id = :worker_id';
    $statement = $db->prepare($queryImage);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $worker = $statement->fetch();
    $statement->closeCursor();
    
    $image_filename = $worker['image_filename'];
    
    if (isset($_FILES['worker_image']) && $_FILES['worker_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = process_image($_FILES['worker_image']);
        if ($upload_result['success']) {
            if ($worker['image_filename']) {
                delete_image($worker['image_filename']);
            }
            $image_filename = $upload_result['filename'];
        }
    }

    $query = '
        UPDATE workers
        SET full_name = :full_name,
            phone = :phone,
            email = :email,
            hire_date = :hire_date,
            department_id = :department_id,
            image_filename = :image_filename
        WHERE worker_id = :worker_id
    ';

    $statement = $db->prepare($query);
    $statement->bindValue(':full_name', $full_name);
    $statement->bindValue(':phone', $phone);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':hire_date', $hire_date);
    $statement->bindValue(':department_id', $department_id);
    $statement->bindValue(':image_filename', $image_filename);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["fullName"] = $full_name;
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die();

?>
