<?php
    // Email utility functions for Worker Attendance System
    // Local demo logs emails to database; optional SMTP sending via PHPMailer
    // Following the instructor's message.php pattern but adapted for local development

    require_once('database.php');

    // Optional SMTP configuration (set to true and fill values to send real emails)
    $MAIL_USE_SMTP = false;
    $MAIL_HOST = 'smtp.example.com';
    $MAIL_PORT = 587;
    $MAIL_USERNAME = '';
    $MAIL_PASSWORD = '';
    $MAIL_ENCRYPTION = 'tls'; // 'tls' or 'ssl'

    /**
     * Send email function (logs to database, optionally sends via SMTP)
     * @param string $to_address - Recipient email address
     * @param string $to_name - Recipient name
     * @param string $from_address - Sender email address
     * @param string $from_name - Sender name
     * @param string $subject - Email subject
     * @param string $body - Email body (can be HTML)
     * @param bool $is_body_html - Whether body is HTML formatted
     * @param string $email_type - Type of email (Welcome, Late Notification, etc.)
     * @return bool - Returns true on success
     * @throws Exception - On validation errors
     */
    function send_mail($to_address, $to_name, $from_address, $from_name,
        $subject, $body, $is_body_html = false, $email_type = 'General')
    {
        // Validate TO email address
        if (!valid_email($to_address)) {            
            throw new Exception('This To address is invalid: ' . htmlspecialchars($to_address));
        }

        // Validate FROM email address
        if (!valid_email($from_address)) {
            throw new Exception('This From address is invalid: ' . htmlspecialchars($from_address));
        }

        global $db, $MAIL_USE_SMTP, $MAIL_HOST, $MAIL_PORT, $MAIL_USERNAME, $MAIL_PASSWORD, $MAIL_ENCRYPTION;

        $status = 'Sent';
        $send_error = null;

        if ($MAIL_USE_SMTP) {
            try {
                $phpmailer_path = __DIR__ . '/PHPMailer/src/PHPMailer.php';
                $smtp_path = __DIR__ . '/PHPMailer/src/SMTP.php';
                $exception_path = __DIR__ . '/PHPMailer/src/Exception.php';

                if (!file_exists($phpmailer_path) || !file_exists($smtp_path) || !file_exists($exception_path)) {
                    throw new Exception('PHPMailer library files not found.');
                }

                require_once($exception_path);
                require_once($phpmailer_path);
                require_once($smtp_path);

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $MAIL_HOST;
                $mail->Port = $MAIL_PORT;

                if (!empty($MAIL_USERNAME)) {
                    $mail->SMTPAuth = true;
                    $mail->Username = $MAIL_USERNAME;
                    $mail->Password = $MAIL_PASSWORD;
                }

                if (!empty($MAIL_ENCRYPTION)) {
                    $mail->SMTPSecure = $MAIL_ENCRYPTION;
                }

                $mail->setFrom($from_address, $from_name);
                $mail->addAddress($to_address, $to_name);
                $mail->Subject = $subject;

                if ($is_body_html) {
                    $mail->isHTML(true);
                    $mail->Body = $body;
                    $mail->AltBody = strip_tags($body);
                } else {
                    $mail->Body = $body;
                }

                $mail->send();
            } catch (Exception $e) {
                $status = 'Failed';
                $send_error = $e->getMessage();
            }
        }

        try {
            $query = 'INSERT INTO email_logs 
                      (to_address, to_name, from_address, from_name, subject, body, email_type, status) 
                      VALUES 
                      (:to_address, :to_name, :from_address, :from_name, :subject, :body, :email_type, :status)';

            $statement = $db->prepare($query);
            $statement->bindValue(':to_address', $to_address);
            $statement->bindValue(':to_name', $to_name);
            $statement->bindValue(':from_address', $from_address);
            $statement->bindValue(':from_name', $from_name);
            $statement->bindValue(':subject', $subject);
            $statement->bindValue(':body', $body);
            $statement->bindValue(':email_type', $email_type);
            $statement->bindValue(':status', $status);

            $statement->execute();
            $statement->closeCursor();

            if ($send_error) {
                throw new Exception('Error sending email: ' . htmlspecialchars($send_error));
            }

            return true;

        } catch (Exception $e) {
            throw new Exception('Error logging email: ' . htmlspecialchars($e->getMessage()));
        }
    }
    
    /**
     * Validate email address format
     * @param string $email - Email address to validate
     * @return bool - True if valid, false otherwise
     */
    function valid_email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * Generate welcome email HTML
     * @param string $user_name - User's name
     * @return string - HTML email body
     */
    function generate_welcome_email($user_name) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
        </head>
        <body class="email-template email-template--welcome">
            <div class="email-container">
                <div class="email-header">
                    <h2>Welcome to Worker Attendance System!</h2>
                </div>
                <div class="email-content">
                    <p>Dear ' . htmlspecialchars($user_name) . ',</p>
                    <p>Your account has been successfully created in the Worker Attendance Management System.</p>
                    <p><strong>You can now:</strong></p>
                    <ul>
                        <li>Manage worker information</li>
                        <li>Track daily attendance</li>
                        <li>Assign skills to workers</li>
                        <li>Generate reports</li>
                    </ul>
                    <p>Thank you for joining our system!</p>
                </div>
                <div class="email-footer">
                    <p>&copy; ' . date('Y') . ' Worker Attendance System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Generate late arrival notification email HTML
     * @param string $worker_name - Worker's name
     * @param string $date - Date of late arrival
     * @param string $scheduled_time - Scheduled arrival time
     * @param string $actual_time - Actual check-in time
     * @return string - HTML email body
     */
    function generate_late_notification_email($worker_name, $date, $scheduled_time, $actual_time) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
        </head>
        <body class="email-template email-template--late">
            <div class="email-container">
                <div class="email-header email-header--warning">
                    <h2>⚠️ Late Arrival Notice</h2>
                </div>
                <div class="email-content email-content--warning">
                    <p>Dear ' . htmlspecialchars($worker_name) . ',</p>
                    <p class="email-warning">You were marked LATE on ' . htmlspecialchars($date) . '.</p>
                    <p><strong>Scheduled Time:</strong> ' . htmlspecialchars($scheduled_time) . '</p>
                    <p><strong>Check-in Time:</strong> ' . htmlspecialchars($actual_time) . '</p>
                    <p>Please ensure to arrive on time for future shifts.</p>
                    <p>If you have any questions, please contact your supervisor.</p>
                </div>
                <div class="email-footer">
                    <p>&copy; ' . date('Y') . ' Worker Attendance System</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Generate daily summary email HTML
     * @param string $date - Report date
     * @param int $total_present - Count of present workers
     * @param int $total_late - Count of late workers
     * @param int $total_absent - Count of absent workers
     * @param array $late_workers - Array of late workers with details
     * @param array $absent_workers - Array of absent workers
     * @return string - HTML email body
     */
    function generate_daily_summary_email($date, $total_present, $total_late, $total_absent, $late_workers = [], $absent_workers = []) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
        </head>
        <body class="email-template email-template--summary">
            <div class="email-container email-container--wide">
                <div class="email-header">
                    <h2>📊 Daily Attendance Report</h2>
                    <p>' . htmlspecialchars($date) . '</p>
                </div>
                <div class="email-content">
                    <div class="email-stats">
                        <div class="email-stat-box email-stat-present">
                            <div class="email-stat-number">' . $total_present . '</div>
                            <div>Present</div>
                        </div>
                        <div class="email-stat-box email-stat-late">
                            <div class="email-stat-number">' . $total_late . '</div>
                            <div>Late</div>
                        </div>
                        <div class="email-stat-box email-stat-absent">
                            <div class="email-stat-number">' . $total_absent . '</div>
                            <div>Absent</div>
                        </div>
                    </div>';
        
        if (count($late_workers) > 0) {
            $html .= '<h3>Late Arrivals:</h3><table><tr><th>Worker Name</th><th>Scheduled</th><th>Check-in</th></tr>';
            foreach ($late_workers as $worker) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($worker['name']) . '</td>
                    <td>' . htmlspecialchars($worker['scheduled']) . '</td>
                    <td>' . htmlspecialchars($worker['checkin']) . '</td>
                </tr>';
            }
            $html .= '</table>';
        }
        
        if (count($absent_workers) > 0) {
            $html .= '<h3>Absent Workers:</h3><ul>';
            foreach ($absent_workers as $worker) {
                $html .= '<li>' . htmlspecialchars($worker) . '</li>';
            }
            $html .= '</ul>';
        }
        
        $html .= '
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' Worker Attendance System</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
?>
