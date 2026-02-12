<?php
    session_start();

    $_SESSION = []; // Clears all the session data
    session_destroy(); // Clean up the session id

    header("Location: login_form.php");
    die();
?>
