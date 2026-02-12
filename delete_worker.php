<?php
    session_start();
    require('database.php');
    
    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);
    
    if ($worker_id != false) {
        
        $queryCheck = 'SELECT COUNT(*) as count FROM attendance WHERE worker_id = :worker_id';
        $statement = $db->prepare($queryCheck);
        $statement->bindValue(':worker_id', $worker_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        
        if ($result['count'] > 0) {
            $_SESSION["add_error"] = "Cannot delete worker with attendance records. Please delete attendance records first.";
            $url = "error.php";
            header("Location: " . $url);
            die();
        }
        
        // Get current worker record to check current image name
        $queryImage = 'SELECT image_filename FROM workers WHERE worker_id = :worker_id';
        $statement = $db->prepare($queryImage);
        $statement->bindValue(':worker_id', $worker_id);
        $statement->execute();
        $worker = $statement->fetch();
        $statement->closeCursor();
        
        $old_image_name = $worker['image_filename'];
        $base_dir = 'images/';

        // Delete image if not placeholder
        if($old_image_name != null && $old_image_name != 'placeholder.png') {
            $path = $base_dir . $old_image_name;
            if(file_exists($path)) {
                unlink($path);
            }
        }
        
        $query = 'DELETE FROM workers WHERE worker_id = :worker_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':worker_id', $worker_id);
        $statement->execute();
        $statement->closeCursor();
    }
    
    $url = "index.php";
    header("Location: " . $url);
    die();
    
?>
