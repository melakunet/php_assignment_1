<?php
    if (!isset($_SESSION)) {
        session_start();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>

<body>
    <header>
        <div class="container">
            <h1>Worker Attendance Management System</h1>
            <p class="current-date">Today: <?php echo date('l, F j, Y'); ?></p>
            <nav style="margin-top: 15px;">
                <a href="index.php" style="color: white; margin: 0 15px; text-decoration: none;">Home</a>
                <a href="add_worker_form.php" style="color: white; margin: 0 15px; text-decoration: none;">Add Worker</a>
                <?php if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']): ?>
                    <span style="color: white; margin: 0 15px;">Welcome, <?php echo htmlspecialchars($_SESSION['userName']); ?>!</span>
                    <a href="logout.php" style="color: white; margin: 0 15px; text-decoration: none;">Logout</a>
                <?php else: ?>
                    <a href="register_user_form.php" style="color: white; margin: 0 15px; text-decoration: none;">Register</a>
                    <a href="login_form.php" style="color: white; margin: 0 15px; text-decoration: none;">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
