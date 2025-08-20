 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Teacher Portal - Notices</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
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
        .notice-list {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .notice-item {
            padding: 18px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .notice-item:last-child {
            border-bottom: none;
        }
        .notice-title {
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 6px;
            font-size: 1.1em;
        }
        .notice-date {
            color: #6b7280;
            font-size: 0.95em;
            margin-bottom: 8px;
        }
        .notice-content {
            color: #333;
            font-size: 1em;
        }
        .add-notice-form {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(79,70,229,0.08);
        }
        .add-notice-form input, .add-notice-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 1em;
            background: #fff;
        }
        .add-notice-form button {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(79,70,229,0.08);
            transition: background 0.2s;
        }
        .add-notice-form button:hover {
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
        }
        @media (max-width: 900px) {
            .main-content { margin-left: 0; padding: 15px; }
            .sidebar { position: static; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar" role="navigation" aria-label="Teacher Portal Navigation">
            <div class="teacher-profile">
                <div class="teacher-avatar">
                    <img src="https://scontent.fdac138-1.fna.fbcdn.net/v/t39.30808-6/476630836_1342214043451264_5083001827614943380_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEZmvzZy6F7jgGqDPq788s7hTEhiCE-5tWFMSGIIT7m1ZD4EF5He0XQFfWnnT-4E32bKQ4wVjCphIpYxjvdna9m&_nc_ohc=O7Eibxr1F7gQ7kNvwE05Zuv&_nc_oc=Adnp6JMAnvupjyo9TsYGjf28jcUsa2vu03w01W6K7_UXjqjfPj86WYVG_zYoodvK_Ag&_nc_zt=23&_nc_ht=scontent.fdac138-1.fna&_nc_gid=z7pJABwRyTCdjMp3ZlyILA&oh=00_AfUUEDZUia2fU0EBbNOjZMmtOhSWChyPbF6Mm4kukJXtIw&oe=68AA72B5"
                         alt="Profile picture of Md. Mehedi Hasan" />
                </div>
                <div class="teacher-name">Md. Mehedi Hasan</div>
                <div class="teacher-title">Director</div>
                <div class="teacher-title">Mehedi's IoT Academy</div>
                <div class="teacher-id">ID: TCH001</div>
            </div>
            <nav class="nav-menu" role="menu">
                <div class="nav-item"><a href="welcom.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></div>
                <div class="nav-item"><a href="students.php" class="nav-link"><i class="fas fa-user-graduate"></i> Students</a></div>
                <div class="nav-item"><a href="marks.php" class="nav-link"><i class="fas fa-chart-line"></i> Marks</a></div>
                <div class="nav-item"><a href="attendance.php" class="nav-link"><i class="fas fa-calendar-check"></i> Attendance</a></div>
                <div class="nav-item"><a href="work_panel.php" class="nav-link"><i class="fas fa-tasks"></i> Work Panel</a></div>
                <div class="nav-item"><a href="notices.php" class="nav-link active"><i class="fas fa-bullhorn"></i> Notices</a></div>
                <div class="nav-item"><a href="reports.php" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a></div>
                <div class="nav-item"><a href="profile.php" class="nav-link"><i class="fas fa-user-cog"></i> Profile</a></div>
                <div class="nav-item"><a href="teacher.php" class="nav-link"><i class="fas fa-user-cog"></i> Our Faculty</a></div>
            </nav>
        </div>
        <!-- Main Content -->
        <main class="main-content">
            <h2 class="section-title">Notices</h2>
            <form class="add-notice-form" method="post" action="">
                <input type="text" name="notice_title" placeholder="Notice Title" required />
                <textarea name="notice_content" rows="4" placeholder="Notice Content" required></textarea>
                <button type="submit"><i class="fas fa-paper-plane"></i> Publish Notice</button>
            </form>
            <div class="notice-list">
                <?php
                // Simple file-based storage for demo (replace with DB in production)
                $noticeFile = "notices.txt";
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['notice_title']) && !empty($_POST['notice_content'])) {
                    $title = htmlspecialchars($_POST['notice_title']);
                    $content = htmlspecialchars($_POST['notice_content']);
                    $date = date("Y-m-d H:i");
                    $entry = "$date|$title|$content\n";
                    file_put_contents($noticeFile, $entry, FILE_APPEND);
                    echo "<div style='color:green; margin-bottom:15px;'>Notice published successfully!</div>";
                }
                if (file_exists($noticeFile)) {
                    $lines = array_reverse(file($noticeFile));
                    foreach ($lines as $line) {
                        list($date, $title, $content) = explode('|', trim($line));
                        echo "<div class='notice-item'>
                                <div class='notice-title'><i class='fas fa-bullhorn'></i> $title</div>
                                <div class='notice-date'>$date</div>
                                <div class='notice-content'>$content</div>
                              </div>";
                    }
                } else {
                    echo "<div style='color:#888;'>No notices published yet.</div>";
                }
                ?>
            </div>
        </main>
    </div>
</body>