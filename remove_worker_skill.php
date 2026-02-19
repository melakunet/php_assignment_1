<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);
    $skill_name = filter_input(INPUT_POST, 'skill_name');

    if ($worker_id == null || $skill_name == null) {
        $_SESSION["add_error"] = "Invalid data for removing skill.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    require_once('database.php');

    // Get skill_id from skill_name
    $querySkillId = 'SELECT skill_id FROM skills WHERE skill_name = :skill_name';
    $statement = $db->prepare($querySkillId);
    $statement->bindValue(':skill_name', $skill_name);
    $statement->execute();
    $skill = $statement->fetch();
    $statement->closeCursor();

    if (!$skill) {
        $_SESSION["add_error"] = "Skill not found.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    $skill_id = $skill['skill_id'];

    // Delete the skill assignment from junction table
    $query = 'DELETE FROM worker_skills 
              WHERE worker_id = :worker_id AND skill_id = :skill_id';

    $statement = $db->prepare($query);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->bindValue(':skill_id', $skill_id);
    $statement->execute();
    $statement->closeCursor();

    // Redirect back to worker skills report
    header("Location: worker_skills_report.php");
    die();

?>
