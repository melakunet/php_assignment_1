<?php
    session_start();
    require('database.php');
    
    // get data from the form
    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);
    
    
    // code to delete worker from database
    // validate inputs
    
    if ($worker_id != false) {
        
        // check if worker has attendance records
        $queryCheck = 'SELECT COUNT(*) as count FROM attendance WHERE worker_id = :worker_id';
        $statement = $db->prepare($queryCheck);
        $statement->bindValue(':worker_id', $worker_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        
        if ($result['count'] > 0) {
            // worker has attendance records, cannot delete
            $_SESSION["add_error"] = "Cannot delete worker with attendance records. Please delete attendance records first.";
            $url = "error.php";
            header("Location: " . $url);
            die();
        }
        
        // delete the worker from the database
        $query = 'DELETE FROM workers WHERE worker_id = :worker_id';
        
        $statement = $db->prepare($query);
        $statement->bindValue(':worker_id', $worker_id);
        
        $statement->execute();
        $statement->closeCursor();
    }
    
    
    // reload the index page
    $url = "index.php";
    header("Location: " . $url);
    die();
    
?>
