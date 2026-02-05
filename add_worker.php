<?php
    session_start();
    require("database.php");
    require("image_util.php");

    $full_name = filter_input(INPUT_POST, 'full_name');
    $phone = filter_input(INPUT_POST, 'phone');
    $email = filter_input(INPUT_POST, 'email');
    $hire_date = filter_input(INPUT_POST, 'hire_date');
    $department_id = filter_input(INPUT_POST, 'department_id', FILTER_VALIDATE_INT);

    if ($full_name == null || $phone == null || $department_id == null) {
        $_SESSION["add_error"] = "Invalid worker data. Check all fields and try again.";
        header("Location: error.php");
        exit();
    }

    $image_filename = null;
    if (isset($_FILES['worker_image']) && $_FILES['worker_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = process_image($_FILES['worker_image']);
        if ($upload_result['success']) {
            $image_filename = $upload_result['filename'];
        } else {
            $_SESSION["add_error"] = $upload_result['error'];
        }
    }

    $query = 'INSERT INTO workers (full_name, phone, email, hire_date, department_id, image_filename) 
              VALUES (:full_name, :phone, :email, :hire_date, :department_id, :image_filename)';
    $statement = $db->prepare($query);
    $statement->bindValue(':full_name', $full_name);
    $statement->bindValue(':phone', $phone);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':hire_date', $hire_date);
    $statement->bindValue(':department_id', $department_id);
    $statement->bindValue(':image_filename', $image_filename);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["fullName"] = $full_name;
    $_SESSION["image_filename"] = $image_filename;
    
    header("Location: add_confirmation.php");
    exit();
?>
