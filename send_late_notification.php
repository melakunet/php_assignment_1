<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header("Location: login_form.php");
        die();
    }

    require_once('database.php');
    require_once('message.php');

    // This file can be called manually or automatically when a worker checks in late
    // For demo purposes, we'll create a form to manually trigger it

    $worker_id = filter_input(INPUT_POST, 'worker_id', FILTER_VALIDATE_INT);
    $attendance_date = filter_input(INPUT_POST, 'attendance_date');

    if ($worker_id == null || $attendance_date == null) {
        $_SESSION["add_error"] = "Invalid data. Worker ID and date are required.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    // Get worker details
    $queryWorker = 'SELECT w.full_name, w.email, a.scheduled_time, a.check_in_time
                    FROM workers w
                    JOIN attendance a ON w.worker_id = a.worker_id
                    WHERE w.worker_id = :worker_id AND a.date = :date AND a.status = "Late"';
    
    $statement = $db->prepare($queryWorker);
    $statement->bindValue(':worker_id', $worker_id);
    $statement->bindValue(':date', $attendance_date);
    $statement->execute();
    $worker = $statement->fetch();
    $statement->closeCursor();

    if (!$worker) {
        $_SESSION["add_error"] = "Worker not found or not marked as late on this date.";
        $url = "error.php";
        header("Location: " . $url);
        die();
    }

    // Send late notification email
    try {
        $to_address = $worker['email'];
        $to_name = $worker['full_name'];
        $from_address = 'noreply@attendance.com';
        $from_name = 'Attendance System';
        $subject = 'Late Arrival Notice - ' . date('F j, Y', strtotime($attendance_date));
        
        $body = generate_late_notification_email(
            $worker['full_name'],
            date('F j, Y', strtotime($attendance_date)),
            $worker['scheduled_time'],
            $worker['check_in_time']
        );
        
        send_mail($to_address, $to_name, $from_address, $from_name, $subject, $body, true, 'Late Notification');
        
        $_SESSION['notification_sent'] = true;
        $_SESSION['notification_worker'] = $worker['full_name'];
        header("Location: send_late_notification_confirmation.php");
        die();
        
    } catch (Exception $e) {
        $_SESSION["add_error"] = "Error logging email: " . $e->getMessage();
        header("Location: error.php");
        die();
    }

?>
