<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // Get today's date
    $today = date('Y-m-d');

    // Get attendance statistics for today
    $queryTodayStats = 'SELECT 
                            COUNT(*) as total_scheduled,
                            SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present_count,
                            SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late_count,
                            SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent_count
                        FROM attendance
                        WHERE date = :today';
    
    $statement = $db->prepare($queryTodayStats);
    $statement->bindValue(':today', $today);
    $statement->execute();
    $todayStats = $statement->fetch();
    $statement->closeCursor();

    // Get total workers count
    $queryWorkerCount = 'SELECT COUNT(*) as total_workers FROM workers';
    $statement = $db->prepare($queryWorkerCount);
    $statement->execute();
    $workerCount = $statement->fetch();
    $statement->closeCursor();

    // Get total skills count
    $querySkillCount = 'SELECT COUNT(*) as total_skills FROM skills';
    $statement = $db->prepare($querySkillCount);
    $statement->execute();
    $skillCount = $statement->fetch();
    $statement->closeCursor();

    // Get total skill assignments count
    $queryAssignmentCount = 'SELECT COUNT(*) as total_assignments FROM worker_skills';
    $statement = $db->prepare($queryAssignmentCount);
    $statement->execute();
    $assignmentCount = $statement->fetch();
    $statement->closeCursor();

    // Get recent check-ins (last 5)
    $queryRecentCheckins = 'SELECT w.full_name, w.image_filename, a.check_in_time, a.status, a.date
                            FROM attendance a
                            JOIN workers w ON a.worker_id = w.worker_id
                            WHERE a.check_in_time IS NOT NULL
                            ORDER BY a.date DESC, a.check_in_time DESC
                            LIMIT 5';
    
    $statement = $db->prepare($queryRecentCheckins);
    $statement->execute();
    $recentCheckins = $statement->fetchAll();
    $statement->closeCursor();

    // Get department-wise attendance for today
    $queryDeptStats = 'SELECT d.department_name, 
                              COUNT(*) as total,
                              SUM(CASE WHEN a.status = "Present" THEN 1 ELSE 0 END) as present,
                              SUM(CASE WHEN a.status = "Late" THEN 1 ELSE 0 END) as late,
                              SUM(CASE WHEN a.status = "Absent" THEN 1 ELSE 0 END) as absent
                       FROM attendance a
                       JOIN workers w ON a.worker_id = w.worker_id
                       LEFT JOIN departments d ON w.department_id = d.department_id
                       WHERE a.date = :today
                       GROUP BY d.department_id, d.department_name
                       ORDER BY d.department_name';
    
    $statement = $db->prepare($queryDeptStats);
    $statement->bindValue(':today', $today);
    $statement->execute();
    $deptStats = $statement->fetchAll();
    $statement->closeCursor();

    // Get top 5 skills by worker count
    $queryTopSkills = 'SELECT s.skill_name, s.skill_category, COUNT(ws.worker_id) as worker_count
                       FROM skills s
                       LEFT JOIN worker_skills ws ON s.skill_id = ws.skill_id
                       GROUP BY s.skill_id, s.skill_name, s.skill_category
                       ORDER BY worker_count DESC
                       LIMIT 5';
    
    $statement = $db->prepare($queryTopSkills);
    $statement->execute();
    $topSkills = $statement->fetchAll();
    $statement->closeCursor();

    // Get last 7 days attendance trend
    $queryWeeklyTrend = 'SELECT 
                            date,
                            SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                            SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent
                         FROM attendance
                         WHERE date >= DATE_SUB(:today, INTERVAL 7 DAY)
                         GROUP BY date
                         ORDER BY date ASC';
    
    $statement = $db->prepare($queryWeeklyTrend);
    $statement->bindValue(':today', $today);
    $statement->execute();
    $weeklyTrend = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Statistics - Worker Attendance System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css?v=<?php echo time(); ?>" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include("header.php"); ?>

    <main class="dashboard-container">
        <div class="dashboard-header">
            <h1>Statistical Overview</h1>
            <p class="dashboard-date">Today: <?php echo date('l, F j, Y'); ?></p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <!-- Today's Attendance Card -->
            <div class="stat-card card-primary">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h3>Today's Attendance</h3>
                    <div class="stat-number"><?php echo $todayStats['total_scheduled'] ?? 0; ?></div>
                    <p>Workers Scheduled</p>
                </div>
            </div>

            <!-- Present Count Card -->
            <div class="stat-card card-success">
                <div class="stat-icon">✓</div>
                <div class="stat-content">
                    <h3>Present</h3>
                    <div class="stat-number"><?php echo $todayStats['present_count'] ?? 0; ?></div>
                    <p>On Time & Working</p>
                </div>
            </div>

            <!-- Late Count Card -->
            <div class="stat-card card-warning">
                <div class="stat-icon">⏰</div>
                <div class="stat-content">
                    <h3>Late Arrivals</h3>
                    <div class="stat-number"><?php echo $todayStats['late_count'] ?? 0; ?></div>
                    <p>Late Check-ins</p>
                </div>
            </div>

            <!-- Absent Count Card -->
            <div class="stat-card card-danger">
                <div class="stat-icon">✗</div>
                <div class="stat-content">
                    <h3>Absent</h3>
                    <div class="stat-number"><?php echo $todayStats['absent_count'] ?? 0; ?></div>
                    <p>No Check-in</p>
                </div>
            </div>

            <!-- Total Workers Card -->
            <div class="stat-card card-info">
                <div class="stat-icon">👨‍💼</div>
                <div class="stat-content">
                    <h3>Total Workers</h3>
                    <div class="stat-number"><?php echo $workerCount['total_workers']; ?></div>
                    <p>In System</p>
                </div>
            </div>

            <!-- Skills Card -->
            <div class="stat-card card-purple">
                <div class="stat-icon">⚡</div>
                <div class="stat-content">
                    <h3>Skills</h3>
                    <div class="stat-number"><?php echo $skillCount['total_skills']; ?></div>
                    <p><?php echo $assignmentCount['total_assignments']; ?> Assignments</p>
                </div>
            </div>
        </div>

        <!-- Charts and Data Section -->
        <div class="dashboard-grid">
            
            <!-- Left Column -->
            <div class="dashboard-column">
                
                <!-- Weekly Trend Chart -->
                <div class="dashboard-card">
                    <h3>📈 7-Day Attendance Trend</h3>
                    <canvas id="weeklyTrendChart"></canvas>
                </div>

                <!-- Department Breakdown -->
                <div class="dashboard-card">
                    <h3>🏢 Department-wise Attendance (Today)</h3>
                    <?php if (count($deptStats) > 0): ?>
                        <table class="dashboard-table">
                            <tr>
                                <th>Department</th>
                                <th>Present</th>
                                <th>Late</th>
                                <th>Absent</th>
                                <th>Total</th>
                            </tr>
                            <?php foreach ($deptStats as $dept): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($dept['department_name'] ?? 'N/A'); ?></strong></td>
                                    <td class="text-success"><?php echo $dept['present']; ?></td>
                                    <td class="text-warning"><?php echo $dept['late']; ?></td>
                                    <td class="text-danger"><?php echo $dept['absent']; ?></td>
                                    <td><strong><?php echo $dept['total']; ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No attendance data for today.</p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Right Column -->
            <div class="dashboard-column">
                
                <!-- Today's Status Pie Chart -->
                <div class="dashboard-card">
                    <h3>📊 Today's Status Distribution</h3>
                    <canvas id="statusPieChart"></canvas>
                </div>

                <!-- Recent Check-ins -->
                <div class="dashboard-card">
                    <h3>🕒 Recent Check-ins</h3>
                    <?php if (count($recentCheckins) > 0): ?>
                        <div class="worker-grid">
                            <?php foreach ($recentCheckins as $checkin): ?>
                                <div class="worker-card">
                                    <div class="worker-card__photo">
                                        <?php if (!empty($checkin['image_filename'])): ?>
                                            <img src="images/<?php echo htmlspecialchars($checkin['image_filename']); ?>"
                                                 alt="<?php echo htmlspecialchars($checkin['full_name']); ?>">
                                        <?php else: ?>
                                            <img src="images/placeholder.png" alt="Worker photo">
                                        <?php endif; ?>
                                    </div>

                                    <div class="worker-card__name"><?php echo htmlspecialchars($checkin['full_name']); ?></div>
                                    <p class="worker-card__meta"><?php echo date('M j, g:i A', strtotime($checkin['date'] . ' ' . $checkin['check_in_time'])); ?></p>

                                    <div class="worker-card__status status-<?php echo strtolower($checkin['status']); ?>">
                                        <?php echo htmlspecialchars($checkin['status']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No recent check-ins.</p>
                    <?php endif; ?>
                </div>

                <!-- Top Skills -->
                <div class="dashboard-card">
                    <h3>⚡ Top 5 Skills</h3>
                    <?php if (count($topSkills) > 0): ?>
                        <div class="skills-list">
                            <?php foreach ($topSkills as $skill): ?>
                                <div class="skill-item">
                                    <div class="skill-info">
                                        <strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong>
                                        <span class="skill-category"><?php echo htmlspecialchars($skill['skill_category']); ?></span>
                                    </div>
                                    <div class="skill-count">
                                        <?php echo $skill['worker_count']; ?> workers
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No skills data available.</p>
                    <?php endif; ?>
                </div>

            </div>

        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>⚡ Quick Actions</h3>
            <div class="action-buttons">
                <a href="add_worker_form.php" class="action-btn btn-success">
                    <span class="btn-icon">➕</span>
                    <span>Add Worker</span>
                </a>
                <a href="manage_skills.php" class="action-btn btn-primary">
                    <span class="btn-icon">⚡</span>
                    <span>Manage Skills</span>
                </a>
                <a href="worker_skills_report.php" class="action-btn btn-info">
                    <span class="btn-icon">📋</span>
                    <span>Skills Report</span>
                </a>
                <a href="send_daily_summary_form.php" class="action-btn btn-purple">
                    <span class="btn-icon">📧</span>
                    <span>Send Summary</span>
                </a>
                <a href="view_email_logs.php" class="action-btn btn-warning">
                    <span class="btn-icon">📨</span>
                    <span>Email Logs</span>
                </a>
                <a href="index.php" class="action-btn btn-secondary">
                    <span class="btn-icon">📝</span>
                    <span>Worker List</span>
                </a>
            </div>
        </div>

    <!-- Chart.js Scripts -->
    <script>
        // Data passed from PHP to JavaScript
        const dashboardData = {
            weeklyLabels: [
                <?php foreach ($weeklyTrend as $day): ?>
                    '<?php echo date('M j', strtotime($day['date'])); ?>',
                <?php endforeach; ?>
            ],
            weeklyPresent: [
                <?php foreach ($weeklyTrend as $day): ?><?php echo $day['present']; ?>,<?php endforeach; ?>
            ],
            weeklyLate: [
                <?php foreach ($weeklyTrend as $day): ?><?php echo $day['late']; ?>,<?php endforeach; ?>
            ],
            weeklyAbsent: [
                <?php foreach ($weeklyTrend as $day): ?><?php echo $day['absent']; ?>,<?php endforeach; ?>
            ],
            statusData: [
                <?php echo $todayStats['present_count'] ?? 0; ?>,
                <?php echo $todayStats['late_count'] ?? 0; ?>,
                <?php echo $todayStats['absent_count'] ?? 0; ?>
            ]
        };
    </script>
    <script src="js/dashboard.js"></script>
    
    <?php include("footer.php"); ?>
