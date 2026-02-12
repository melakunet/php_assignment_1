<?php
    session_start();

    $user_name = filter_input(INPUT_POST, 'user_name');
    $user_password = filter_input(INPUT_POST, 'password');
    $email_address = filter_input(INPUT_POST, 'email_address');    

    $hash = password_hash($user_password, PASSWORD_DEFAULT);

    require_once('database.php');
    
    // Check for duplicate userName
    $queryUsers = '
        SELECT userName, password, emailAddress FROM registrations';

    $statement = $db->prepare($queryUsers);
    $statement->execute();
    $users = $statement->fetchAll();
    $statement->closeCursor();

    foreach ($users as $user) {
        if ($user_name == $user["userName"]) {
            $_SESSION["add_error"] = "Invalid data, Duplicate User Name. Try again.";
            $url = "error.php";
            header("Location: " . $url);
            die();  
        }
    }

    if ($user_name == null || $user_password == null || $hash == null || $email_address == null) {
            $_SESSION["add_error"] = "Invalid registration data, Check all fields and try again.";
            $url = "error.php";
            header("Location: " . $url);
            die();  
        }

    // Add Registration

    $query = 'INSERT INTO registrations (userName, password, emailAddress) 
        VALUES (:userName, :password, :emailAddress)';

    $statement = $db->prepare($query);
    $statement->bindValue(':userName', $user_name);
    $statement->bindValue(':password', $hash);
    $statement->bindValue(':emailAddress', $email_address);

    $statement->execute();
    $statement->closeCursor();

    // Get the newly created user's ID
    $user_id = $db->lastInsertId();

    $_SESSION["isLoggedIn"] = TRUE;
    $_SESSION["userName"] = $user_name;
    $_SESSION["user_id"] = $user_id;

    $url = "register_confirmation.php";
    header("Location: " . $url);
    die();

?>
