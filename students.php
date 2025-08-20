<?php
include 'connection.php';

// Add student
if (isset($_POST['add_student'])) {
    $name = $_POST['student_name'];
    $reg_id = $_POST['reg_id'];
    $department = $_POST['department'];
    $subject = $_POST['subject'];
    $stmt = $conn->prepare("INSERT INTO students (name, reg_id, department, subject) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $reg_id, $department, $subject);
    $stmt->execute();
    $stmt->close();
    header("Location: students.php");
    exit();
}

// Edit student (update)
if (isset($_POST['edit_student'])) {
    $id = $_POST['edit_id'];
    $name = $_POST['edit_name'];
    $reg_id = $_POST['edit_reg_id'];
    $department = $_POST['edit_department'];
    $subject = $_POST['edit_subject'];
    $stmt = $conn->prepare("UPDATE students SET name=?, reg_id=?, department=?, subject=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $reg_id, $department, $subject, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: students.php");
    exit();
}

// Delete student
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: students.php");
    exit();
}

// Search student
$students = [];
if (isset($_GET['search_reg_id']) && $_GET['search_reg_id'] !== '') {
    $search_reg_id = $_GET['search_reg_id'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE reg_id LIKE ?");
    $like = "%$search_reg_id%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        .container { display: flex; min-height: 100vh; }
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
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }
        .section-title {
            font-size: 1.5em;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .form-row .student-form {
            flex: 1;
            min-width: 300px;
        }
        .student-form {
            background: rgba(255,255,255,0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .student-form label { font-weight: 500; margin-bottom: 8px; display: block; }
        .student-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 1em;
        }
        .student-form button {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .student-table th, .student-table td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        .student-table th {
            background: #f3f4f6;
            font-weight: 600;
        }
        .student-table tr:last-child td { border-bottom: none; }
        .action-btn {
            background: none;
            border: none;
            color: #4f46e5;
            cursor: pointer;
            font-size: 1em;
            margin-right: 10px;
        }
        .action-btn.delete { color: #e53e3e; }
    </style>
</head>
<body>
    <div class="container">
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
                <div class="nav-item"><a href="students.php" class="nav-link active"><i class="fas fa-user-graduate"></i> Students</a></div>
                <div class="nav-item"><a href="marks.php" class="nav-link"><i class="fas fa-chart-line"></i> Marks</a></div>
                <div class="nav-item"><a href="attendance.php" class="nav-link"><i class="fas fa-calendar-check"></i> Attendance</a></div>
                <div class="nav-item"><a href="work_panel.php" class="nav-link"><i class="fas fa-tasks"></i> Work Panel</a></div>
                <div class="nav-item"><a href="notices.php" class="nav-link"><i class="fas fa-bullhorn"></i> Notices</a></div>
                <div class="nav-item"><a href="reports.php" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a></div>
                <div class="nav-item"><a href="profile.php" class="nav-link"><i class="fas fa-user-cog"></i> Profile</a></div>
          <div class="nav-item"><a href="teacher.php" class="nav-link"><i class="fas fa-user-cog"></i> Our Faculty</a></div>
          
            </nav>
        </div>
        <main class="main-content">
            <h2 class="section-title">Student Management</h2>
            <div class="form-row">
                <!-- Search Form -->
                <form class="student-form" action="students.php" method="GET">
                    <label for="search_reg_id">Search by Registration ID</label>
                    <input type="text" id="search_reg_id" name="search_reg_id" placeholder="Enter Registration ID">
                    <button type="submit"><i class="fas fa-search"></i> Search</button>
                </form>
                <!-- Add Student Form -->
                <form class="student-form" action="students.php" method="POST">
                    <label for="student_name">Student Name</label>
                    <input type="text" id="student_name" name="student_name" required>
                    <label for="reg_id">Registration ID</label>
                    <input type="text" id="reg_id" name="reg_id" required>
                    <label for="department">Department</label>
                    <input type="text" id="department" name="department" required>
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                    <button type="submit" name="add_student"><i class="fas fa-user-plus"></i> Add Student</button>
                </form>
            </div>
            <!-- Student Table -->
            <table class="student-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Registration ID</th>
                        <th>Department</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['reg_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['department']); ?></td>
                            <td><?php echo htmlspecialchars($student['subject']); ?></td>
                            <td>
                                <form action="students.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="edit_id" value="<?php echo $student['id']; ?>">
                                    <input type="text" name="edit_name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                    <input type="text" name="edit_reg_id" value="<?php echo htmlspecialchars($student['reg_id']); ?>" required>
                                    <input type="text" name="edit_department" value="<?php echo htmlspecialchars($student['department']); ?>" required>
                                    <input type="text" name="edit_subject" value="<?php echo htmlspecialchars($student['subject']); ?>" required>
                                    <button type="submit" name="edit_student"><i class="fas fa-edit"></i> Edit</button>
                                </form>
                                <a href="?delete=<?php echo $student['id']; ?>" class="action-btn delete" onclick="return confirm('Delete this student?')"><i class="fas fa-trash-alt"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>