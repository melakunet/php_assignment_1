<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $skill_name = filter_input(INPUT_POST, 'skill_name');
    $skill_category = filter_input(INPUT_POST, 'skill_category');
    $description = filter_input(INPUT_POST, 'description');

    // Validate inputs
    if ($skill_name == null || $skill_category == null) {
        $_SESSION["add_error"] = "Invalid skill data. Skill name and category are required.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    require_once('database.php');

    // Check for duplicate skill name
    $queryCheck = 'SELECT skill_id FROM skills WHERE skill_name = :skill_name';
    $statement = $db->prepare($queryCheck);
    $statement->bindValue(':skill_name', $skill_name);
    $statement->execute();
    $existing = $statement->fetch();
    $statement->closeCursor();

    if ($existing) {
        $_SESSION["add_error"] = "Skill already exists: " . htmlspecialchars($skill_name);
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    // Insert new skill
    $query = 'INSERT INTO skills (skill_name, skill_category, description) 
              VALUES (:skill_name, :skill_category, :description)';

    $statement = $db->prepare($query);
    $statement->bindValue(':skill_name', $skill_name);
    $statement->bindValue(':skill_category', $skill_category);
    $statement->bindValue(':description', $description);

    $statement->execute();
    $statement->closeCursor();

    // Redirect to confirmation page
    $_SESSION['skill_added'] = $skill_name;
    $url = "add_skill_confirmation.php";
    header("Location: " . $url);
    die();

?>
