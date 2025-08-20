<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Teacher Portal - Dashboard</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .teacher-profile {
            padding: 30px 25px;
            text-align: center;
            border-bottom: 1px solid #e8eaed;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            margin-bottom: 20px;
        }

        .teacher-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin: 0 auto 15px;
            overflow: hidden;
        }

        .teacher-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .teacher-name {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .teacher-title {
            font-size: 0.9em;
            opacity: 0.9;
            margin-bottom: 3px;
        }

        .teacher-id {
            font-size: 0.8em;
            opacity: 0.8;
        }

        .nav-menu {
            padding: 10px 0;
        }

        .nav-item {
            margin: 0 15px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: #4b5563;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 1.1em;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between; /* fixed */
            align-items: center;
        }

        .header-title {
            font-size: 2em;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .header-subtitle {
            color: #6b7280;
            font-size: 1em;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            margin-left: auto;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            outline: none;
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.3em;
            color: white;
        }

        .card-icon.students {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .card-icon.marks {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }
        .card-icon.attendance {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
        }
        .card-icon.notices {
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
            color: #8b5a3c;
        }

        .card-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .card-value {
            font-size: 2.5em;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 10px;
        }

        .card-description {
            color: #6b7280;
            font-size: 0.9em;
        }

        /* Quick Actions */
        .quick-actions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.5em;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .action-card {
            padding: 20px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.1));
            border-radius: 12px;
            border: 1px solid rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover,
        .action-card:focus {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(79, 70, 229, 0.2);
            outline: none;
        }

        .action-card h4 {
            color: #4f46e5;
            margin-bottom: 8px;
            font-size: 1.1em;
        }

        .action-card p {
            color: #6b7280;
            font-size: 0.9em;
        }

        /* Recent Activity */
        .recent-activity {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1em;
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .activity-time {
            font-size: 0.8em;
            color: #6b7280;
        }






.container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        .teacher-profile {
            padding: 30px 25px;
            text-align: center;
            border-bottom: 1px solid #e8eaed;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            margin-bottom: 20px;
        }
        .teacher-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            margin: 0 auto 15px;
            overflow: hidden;
        }
        .teacher-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .teacher-name { font-size: 1.3em; font-weight: 600; margin-bottom: 5px; }
        .teacher-title { font-size: 0.9em; opacity: 0.9; margin-bottom: 3px; }
        .teacher-id { font-size: 0.8em; opacity: 0.8; }
        .nav-menu { padding: 10px 0; }
        .nav-item { margin: 0 15px 8px; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: #4b5563;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        .nav-link:hover, .nav-link.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(79,70,229,0.3);
        }
        .nav-link i {
            margin-right: 12px;
            font-size: 1.1em;
            width: 20px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }
        .section-title {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1f2937;
            letter-spacing: 1px;
            text-align: center;
        }
        .work-list {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .work-item {
            display: flex;
            align-items: center;
            padding: 18px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .work-item:last-child {
            border-bottom: none;
        }
        .work-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 18px;
            font-size: 1.3em;
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .work-content {
            flex: 1;
        }
        .work-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .work-desc {
            color: #6b7280;
            font-size: 0.95em;
        }
        .work-actions {
            margin-left: 20px;
        }
        .btn {
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }
        .btn-primary:hover, .btn-primary:focus {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79,70,229,0.4);
            outline: none;
        }
        @media (max-width: 900px) {
            .main-content { margin-left: 0; padding: 15px; }
            .sidebar { position: static; width: 100%; }
        }



















        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
                z-index: 1000;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .header-actions {
                margin: 15px 0 0 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" role="navigation" aria-label="Teacher Portal Navigation">
            <div class="teacher-profile">
                <div class="teacher-avatar">
                    <img
                      src="https://scontent.fdac138-1.fna.fbcdn.net/v/t39.30808-6/476630836_1342214043451264_5083001827614943380_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEZmvzZy6F7jgGqDPq788s7hTEhiCE-5tWFMSGIIT7m1ZD4EF5He0XQFfWnnT-4E32bKQ4wVjCphIpYxjvdna9m&_nc_ohc=O7Eibxr1F7gQ7kNvwE05Zuv&_nc_oc=Adnp6JMAnvupjyo9TsYGjf28jcUsa2vu03w01W6K7_UXjqjfPj86WYVG_zYoodvK_Ag&_nc_zt=23&_nc_ht=scontent.fdac138-1.fna&_nc_gid=z7pJABwRyTCdjMp3ZlyILA&oh=00_AfUUEDZUia2fU0EBbNOjZMmtOhSWChyPbF6Mm4kukJXtIw&oe=68AA72B5"
                      alt="Profile picture of Md. Mehedi Hasan"
                    />
                </div>
                <div class="teacher-name">Md. Mehedi Hasan</div>
                <div class="teacher-title">Director</div>
                <div class="teacher-title">Mehedi's IoT Academy</div>
                <div class="teacher-id">ID: TCH001</div>
            </div>

            <nav class="nav-menu" role="menu">
                <div class="nav-item">
                    <a href="http://localhost/phplogin/welcom.php" class="nav-link active" aria-current="page" role="menuitem">
                        <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="students.php" class="nav-link" role="menuitem">
                        <i class="fas fa-user-graduate" aria-hidden="true"></i>
                        Students
                    </a>
                </div>
                <div class="nav-item">
                    <a href="marks.php" class="nav-link" role="menuitem">
                        <i class="fas fa-chart-line" aria-hidden="true"></i>
                        Marks
                    </a>
                </div>
                <div class="nav-item">
                    <a href="attendance.php" class="nav-link" role="menuitem">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i>
                        Attendance
                    </a>
                </div>
                <div class="nav-item">
                    <a href="work_panel.php" class="nav-link" role="menuitem">
                        <i class="fas fa-tasks" aria-hidden="true"></i>
                        Work Panel
                    </a>
                </div>
                <div class="nav-item">
                    <a href="notices.php" class="nav-link" role="menuitem">
                        <i class="fas fa-bullhorn" aria-hidden="true"></i>
                        Notices
                    </a>
                </div>
                <div class="nav-item">
                    <a href="reports.php" class="nav-link" role="menuitem">
                        <i class="fas fa-file-alt" aria-hidden="true"></i>
                        Reports
                    </a>
                </div>
                <div class="nav-item">
                    <a href="profile.php" class="nav-link" role="menuitem">
                        <i class="fas fa-user-cog" aria-hidden="true"></i>
                        Profile
                    </a>
                </div>

<div class="nav-item"><a href="teacher.php" class="nav-link"><i class="fas fa-user-cog"></i> Our Faculty</a></div>


            </nav>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 class="header-title">Mehedi's IoT Academy</h1>
                    <p class="header-subtitle">Welcome back, Md. Mehedi Hasan</p>
                </div>
                <div class="header-actions">
                    <a href="http://localhost/phplogin/index.php" class="btn btn-primary" role="button">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                        Logout
                    </a>
                </div>
            </header>

            <!-- Dashboard Stats
            <section class="dashboard-grid" aria-label="Dashboard statistics">
                <article class="dashboard-card" tabindex="0">
                    <div class="card-header">
                        <div class="card-icon students" aria-hidden="true">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <div class="card-title">Total Students</div>
                            <div class="card-value">156</div>
                            <div class="card-description">Active students in all classes</div>
                        </div>
                    </div>
                </article>

                <article class="dashboard-card" tabindex="0">
                    <div class="card-header">
                        <div class="card-icon marks" aria-hidden="true">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div class="card-title">Pending Marks</div>
                            <div class="card-value">23</div>
                            <div class="card-description">Assignments to be graded</div>
                        </div>
                    </div>
                </article>

                <article class="dashboard-card" tabindex="0">
                    <div class="card-header">
                        <div class="card-icon attendance" aria-hidden="true">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="card-title">Today's Attendance</div>
                            <div class="card-value">92%</div>
                            <div class="card-description">Students present today</div>
                        </div>
                    </div>
                </article>

                <article class="dashboard-card" tabindex="0">
                    <div class="card-header">
                        <div class="card-icon notices" aria-hidden="true">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <div class="card-title">Active Notices</div>
                            <div class="card-value">5</div>
                            <div class="card-description">Published announcements</div>
                        </div>
                    </div>
                </article>
            </section> -->



 


















            <!-- Quick Actions -->
            <section class="quick-actions" aria-label="Quick action shortcuts">
                <h2 class="section-title">Quick Actions</h2>
                <div class="actions-grid">
                    
                    
<div class="work-list">
                <div class="work-item">
                    <div class="work-icon"><i class="fas fa-user-plus"></i></div>
                    <div class="work-content">
                        <div class="work-title">Add New Student</div>
                        <div class="work-desc">Register a new student to the academy.</div>
                    </div>
                    <div class="work-actions">
                        <a href="students.php" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Go</a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);"><i class="fas fa-pen-nib"></i></div>
                    <div class="work-content">
                        <div class="work-title">Enter Marks</div>
                        <div class="work-desc">Input or update student marks.</div>
                    </div>
                    <div class="work-actions">
                        <a href="marks.php" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Go</a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-icon" style="background: linear-gradient(135deg, #4ecdc4, #44a08d);"><i class="fas fa-calendar-check"></i></div>
                    <div class="work-content">
                        <div class="work-title">Take Attendance</div>
                        <div class="work-desc">Mark daily attendance for students.</div>
                    </div>
                    <div class="work-actions">
                        <a href="attendance.php" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Go</a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-icon" style="background: linear-gradient(135deg, #ffecd2, #fcb69f); color: #8b5a3c;"><i class="fas fa-bullhorn"></i></div>
                    <div class="work-content">
                        <div class="work-title">Publish Notice</div>
                        <div class="work-desc">Create and publish new announcements.</div>
                    </div>
                    <div class="work-actions">
                        <a href="create_notice.php" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Go</a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);"><i class="fas fa-file-alt"></i></div>
                    <div class="work-content">
                        <div class="work-title">View Reports</div>
                        <div class="work-desc">Access student and attendance reports.</div>
                    </div>
                    <div class="work-actions">
                        <a href="reports.php" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Go</a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);"><i class="fas fa-user-cog"></i></div>
                    <div class="work-content">
                        <div class="work-title">Manage Profile</div>
                        <div class="work-desc">Update your teacher profile information.</div>
                    </div>
                    <div class="work-actions">
                        <a href="profile.php" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Go</a>
                    </div>
























                
            </section>

         










          










        </main>
    </div>
</body>
</html>
