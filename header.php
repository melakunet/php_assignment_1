<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

    <header>
        <div class="container">
            <h1>Worker Attendance Management System</h1>
            <p class="current-date">Today: <?php echo date('l, F j, Y'); ?></p>
            <nav class="header-nav">
                <a href="landing.php" class="nav-link">Stats</a>
                <a href="index.php" class="nav-link">Home</a>
                <a href="add_worker_form.php" class="nav-link">Add Worker</a>
                <a href="manage_skills.php" class="nav-link">Skills</a>
                <a href="worker_skills_report.php" class="nav-link">Report</a>
                <a href="view_email_logs.php" class="nav-link">Logs</a>
                <?php if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']): ?>
                    <span class="nav-text">Welcome, <?php echo htmlspecialchars($_SESSION['userName']); ?>!</span>
                    <a href="logout.php" class="nav-link">Logout</a>
                <?php else: ?>
                    <a href="register_user_form.php" class="nav-link">Register</a>
                    <a href="login_form.php" class="nav-link">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
