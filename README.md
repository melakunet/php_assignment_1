# php_assignment_1
Worker Attendance Management System
A simple web application built with PHP and MySQL to manage employee attendance and daily check-ins. This system allows managers to register new workers, track arrival times, and automatically determine attendance status based on scheduled shifts.

Key Features:

Worker Registration: Add new employees with their contact details and roles (Sales, Cashier, etc.).

Real-Time Check-In: Workers select their name and scheduled time to check in.

Automated Status Logic: The system automatically calculates if a worker is Present or Late by comparing their actual check-in time to their scheduled start time (with a 10-minute grace period).

Attendance Dashboard: View a daily report showing who is Present, Late, or Absent.

Database Integration: Fully persistent data storage using a relational MySQL database.

Technologies Used:

PHP

MySQL / MariaDB

Apache Server (XAMPP)

HTML/CSS

Setup:

Import the php_assignment_1.sql file (located in the sql/ folder) into phpMyAdmin.



Run the application via localhost.