<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);
    $skill_id = filter_input(INPUT_POST, 'skill_id', FILTER_VALIDATE_INT);
    $proficiency_level = filter_input(INPUT_POST, 'proficiency_level');
    $date_acquired = filter_input(INPUT_POST, 'date_acquired');

    // Validate inputs
    if ($worker_id == null || $skill_id == null || $proficiency_level == null || $date_acquired == null) {
        $_SESSION["add_error"] = "Invalid data. All fields are required.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    // Validate proficiency level
    $validLevels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
    if (!in_array($proficiency_level, $validLevels)) {
        $_SESSION["add_error"] = "Invalid proficiency level.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    require_once('database.php');

    
    $queryCheck = 'SELECT worker_skill_id FROM worker_skills 
                   WHERE worker_id = :worker_id AND skill_id = :skill_id';
    $statement = $db->prepare($queryCheck);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->bindValue(':skill_id', $skill_id);
    $statement->execute();
    $existing = $statement->fetch();
    $statement->closeCursor();

    if ($existing) {
        // Get worker and skill names for error message
        $queryNames = 'SELECT w.full_name, s.skill_name 
                       FROM workers w, skills s 
                       WHERE w.worker_id = :worker_id AND s.skill_id = :skill_id';
        $statement = $db->prepare($queryNames);
        $statement->bindValue(':worker_id', $worker_id);
        $statement->bindValue(':skill_id', $skill_id);
        $statement->execute();
        $names = $statement->fetch();
        $statement->closeCursor();

        $_SESSION["add_error"] = "Worker already has this skill. " . 
                                  htmlspecialchars($names['full_name']) . " already has " . 
                                  htmlspecialchars($names['skill_name']) . ".";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    // Insert skill assignment (Many-to-Many relationship via junction table)
    $query = 'INSERT INTO worker_skills (worker_id, skill_id, proficiency_level, date_acquired) 
              VALUES (:worker_id, :skill_id, :proficiency_level, :date_acquired)';

    $statement = $db->prepare($query);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->bindValue(':skill_id', $skill_id);
    $statement->bindValue(':proficiency_level', $proficiency_level);
    $statement->bindValue(':date_acquired', $date_acquired);

    $statement->execute();
    $statement->closeCursor();

    // Get worker and skill names for confirmation
    $queryNames = 'SELECT w.full_name, s.skill_name 
                   FROM workers w, skills s 
                   WHERE w.worker_id = :worker_id AND s.skill_id = :skill_id';
    $statement = $db->prepare($queryNames);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->bindValue(':skill_id', $skill_id);
    $statement->execute();
    $names = $statement->fetch();
    $statement->closeCursor();

    // Redirect to confirmation page
    $_SESSION['skill_assigned_worker'] = $names['full_name'];
    $_SESSION['skill_assigned_skill'] = $names['skill_name'];
    $_SESSION['skill_assigned_level'] = $proficiency_level;
    $url = "assign_skill_confirmation.php";
    header("Location: " . $url);
    die();

?>
