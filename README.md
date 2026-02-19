# php_assignment_1
Worker Attendance Management System

A comprehensive web application built with PHP and MySQL to manage employee attendance, skills, and communications. This system allows managers to register new workers, track arrival times, assign skills, manage email communications, and view interactive dashboards with real-time statistics.

## 🌟 Key Features:

### Core Features:
- **Worker Registration**: Add new employees with their contact details, departments, and photos
- **Real-Time Check-In**: Workers select their name and scheduled time to check in
- **Automated Status Logic**: The system automatically calculates if a worker is Present or Late by comparing their actual check-in time to their scheduled start time (with a 10-minute grace period)
- **Attendance Dashboard**: View a daily report showing who is Present, Late, or Absent
- **Database Integration**: Fully persistent data storage using a relational MySQL database

### Advanced Features (New):

- Interactive dashboard with charts
- Skills management (many-to-many)
- Email logging and previews (local demo)

## 🛠️ Technologies Used:

- PHP 8.1+
- MySQL / MariaDB
- Apache Server (XAMPP)
- HTML5/CSS3
- JavaScript
- Chart.js (for visualizations)

## 📁 Project Structure:

```
php_assignment_1/
├── index.php                      # Main worker list
├── landing.php                    # Interactive dashboard (NEW)
├── database.php                   # Database connection
├── header.php / footer.php        # Shared templates
│
├── Worker Management:
│   ├── add_worker_form.php
│   ├── add_worker.php
│   ├── update_worker_form.php
│   ├── update_worker.php
│   ├── delete_worker.php
│   └── worker_details.php
│
├── Skills Management (NEW):
│   ├── manage_skills.php          # Skills overview
│   ├── add_skill_form.php
│   ├── assign_skill_form.php
│   ├── worker_skills_report.php   # Many-to-many report
│   └── view_skill_details.php
│
├── Email System (NEW):
│   ├── message.php                # Core email functions
│   ├── view_email_logs.php        # Email admin panel
│   ├── send_daily_summary_form.php
│   └── send_late_notification_form.php
│
├── Authentication:
│   ├── login_form.php
│   ├── register_user_form.php
│   └── logout.php
│
├── css/
│   └── styles.css                 # Main styles
│
├── images/                        # Worker photos
└── sql/
    └── php_assignment_1.sql       # Database schema

```

## 🗄️ Database Structure:

### Tables:
1. **workers** - Employee information
2. **departments** - Department data (one-to-many with workers)
3. **attendance** - Daily check-in records
4. **skills** - Available skills (NEW)
5. **worker_skills** - Junction table for many-to-many relationship (NEW)
6. **email_logs** - Email communication history (NEW)
7. **registrations** - User accounts

### Relationships Demonstrated:
- **One-to-Many**: departments → workers, workers → attendance
- **Many-to-Many**: workers ↔ skills (via worker_skills junction table)

## 🚀 Setup:

1. **Install XAMPP** (Apache + MySQL + PHP)

2. **Import Database:**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create database: `php_assignment_1`
   - Import file: `sql/php_assignment_1.sql`

3. **Place Files:**
   - Copy project to: `/Applications/XAMPP/xamppfiles/htdocs/php_assignment_1/`

4. **Run Application:**
   - Start XAMPP (Apache & MySQL)
   - Open browser: `http://localhost/php_assignment_1/`
   - Register a new account
   - Explore the dashboard!

## � Notes:

- Built for a PHP & MySQL course project
- Uses local email logging (no SMTP required)

## 👨‍💻 Author:
Assignment for the PHP & MySQL course, TriOS College — by Etefworkie Melaku.

## 📅 Last Updated:
February 19, 2026