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
    
    $old_image_name = $worker['image_filename'];
    $base_dir = 'images/';
    $image_filename = $old_image_name;
    
    // Check if file upload exists
    if (isset($_FILES['worker_image']) && $_FILES['worker_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = process_image($_FILES['worker_image']);
        if ($upload_result['success']) {
            // Delete old image if not placeholder
            if($old_image_name != null && $old_image_name != 'placeholder.png') {
                $path = $base_dir . $old_image_name;
                if(file_exists($path)) {
                    unlink($path);
                }
            }
            $image_filename = $upload_result['filename'];
        } else {
            // Image upload failed - show error
            $_SESSION["add_error"] = "Image upload failed: " . $upload_result['error'];
            $url = "error.php";
            header("Location: " . $url);
            die();
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
