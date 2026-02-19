<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    $summary_date = filter_input(INPUT_POST, 'summary_date');
    $manager_email = filter_input(INPUT_POST, 'manager_email');
    $manager_name = filter_input(INPUT_POST, 'manager_name');

    if ($summary_date == null || $manager_email == null || $manager_name == null) {
        $_SESSION["add_error"] = "Invalid data. All fields are required.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    require_once('database.php');
    require_once('message.php');

    // Get attendance statistics for the selected date
    $queryStats = 'SELECT 
                    SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as total_present,
                    SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as total_late,
                    SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as total_absent
                   FROM attendance
                   WHERE date = :date';
    
    $statement = $db->prepare($queryStats);
    $statement->bindValue(':date', $summary_date);
    $statement->execute();
    $stats = $statement->fetch();
    $statement->closeCursor();

    // Get late workers details
    $queryLate = 'SELECT w.full_name, a.scheduled_time, a.check_in_time
                  FROM attendance a
                  JOIN workers w ON a.worker_id = w.worker_id
                  WHERE a.date = :date AND a.status = "Late"
                  ORDER BY a.check_in_time';
    
    $statement = $db->prepare($queryLate);
    $statement->bindValue(':date', $summary_date);
    $statement->execute();
    $late_records = $statement->fetchAll();
    $statement->closeCursor();

    // Get absent workers
    $queryAbsent = 'SELECT w.full_name
                    FROM attendance a
                    JOIN workers w ON a.worker_id = w.worker_id
                    WHERE a.date = :date AND a.status = "Absent"
                    ORDER BY w.full_name';
    
    $statement = $db->prepare($queryAbsent);
    $statement->bindValue(':date', $summary_date);
    $statement->execute();
    $absent_records = $statement->fetchAll();
    $statement->closeCursor();

    // Format data for email
    $late_workers = [];
    foreach ($late_records as $record) {
        $late_workers[] = [
            'name' => $record['full_name'],
            'scheduled' => $record['scheduled_time'],
            'checkin' => $record['check_in_time']
        ];
    }

    $absent_workers = [];
    foreach ($absent_records as $record) {
        $absent_workers[] = $record['full_name'];
    }

    // Generate and send email
    try {
        $to_address = $manager_email;
        $to_name = $manager_name;
        $from_address = 'noreply@attendance.com';
        $from_name = 'Attendance System';
        $subject = 'Daily Attendance Report - ' . date('F j, Y', strtotime($summary_date));
        
        $body = generate_daily_summary_email(
            date('F j, Y', strtotime($summary_date)),
            $stats['total_present'] ?? 0,
            $stats['total_late'] ?? 0,
            $stats['total_absent'] ?? 0,
            $late_workers,
            $absent_workers
        );
        
        send_mail($to_address, $to_name, $from_address, $from_name, $subject, $body, true, 'Daily Summary');
        
        $_SESSION['summary_sent'] = true;
        $_SESSION['summary_date'] = $summary_date;
        header("Location: send_daily_summary_confirmation.php");
        die();
        
    } catch (Exception $e) {
        $_SESSION["add_error"] = "Error logging email: " . $e->getMessage();
        header("Location: error.php");
        die();
    }

?>
