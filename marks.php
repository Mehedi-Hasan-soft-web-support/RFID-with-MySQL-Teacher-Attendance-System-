<?php
include 'connection.php';

// Add mark
if (isset($_POST['add_mark'])) {
    $reg_id = isset($_POST['reg_id']) ? $_POST['reg_id'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $marks = isset($_POST['marks']) ? $_POST['marks'] : '';
    $stmt = $conn->prepare("INSERT INTO marks (reg_id, department, subject, marks) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $reg_id, $department, $subject, $marks);
    $stmt->execute();
    $stmt->close();
    header("Location: marks.php");
    exit();
}

// Update mark
if (isset($_POST['update_mark'])) {
    $id = isset($_POST['mark_id']) ? $_POST['mark_id'] : '';
    $marks = isset($_POST['marks']) ? $_POST['marks'] : '';
    $stmt = $conn->prepare("UPDATE marks SET marks=? WHERE id=?");
    $stmt->bind_param("ii", $marks, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: marks.php");
    exit();
}

// Delete mark
if (isset($_POST['delete_mark'])) {
    $id = isset($_POST['mark_id']) ? $_POST['mark_id'] : '';
    $stmt = $conn->prepare("DELETE FROM marks WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: marks.php");
    exit();
}

// Fetch all students for display
$students_list = [];
$result_students = $conn->query("SELECT * FROM students ORDER BY id DESC");
while ($row = $result_students->fetch_assoc()) {
    $students_list[] = $row;
}

// Search marks
$marks_list = [];
$where = [];
$params = [];
$types = '';

if (isset($_GET['search_reg_id']) && $_GET['search_reg_id'] !== '') {
    $where[] = "reg_id LIKE ?";
    $params[] = "%" . $_GET['search_reg_id'] . "%";
    $types .= 's';
}

$sql = "SELECT * FROM marks";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $marks_list[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Marks Entry & Check</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: #333; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); box-shadow: 4px 0 20px rgba(0,0,0,0.1); position: fixed; height: 100vh; overflow-y: auto; transition: all 0.3s ease; }
        .teacher-profile { padding: 30px 25px; text-align: center; border-bottom: 1px solid #e8eaed; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; margin-bottom: 20px; }
        .teacher-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); margin: 0 auto 15px; overflow: hidden; }
        .teacher-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .teacher-name { font-size: 1.3em; font-weight: 600; margin-bottom: 5px; }
        .teacher-title { font-size: 0.9em; opacity: 0.9; margin-bottom: 3px; }
        .teacher-id { font-size: 0.8em; opacity: 0.8; }
        .nav-menu { padding: 10px 0; }
        .nav-item { margin: 0 15px 8px; }
        .nav-link { display: flex; align-items: center; padding: 14px 20px; color: #4b5563; text-decoration: none; border-radius: 12px; transition: all 0.3s ease; font-weight: 500; position: relative; overflow: hidden; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; transform: translateX(5px); box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }
        .nav-link i { margin-right: 12px; font-size: 1.1em; width: 20px; text-align: center; }
        .main-content { flex: 1; margin-left: 280px; padding: 30px; }
        .section-title { font-size: 1.5em; font-weight: 600; margin-bottom: 20px; color: #1f2937; }
        .search-row { display: flex; gap: 20px; align-items: center; margin-bottom: 30px; }
        .search-row input { padding: 10px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 1em; }
        .search-row button { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 500; cursor: pointer; }
        .marks-form { display: none; background: #f9f9f9; padding: 20px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.07);}
        .marks-form label { font-weight: 500; margin-bottom: 8px; display: block; }
        .marks-form input, .marks-form select { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 1em; }
        .marks-form button { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 500; cursor: pointer; }
        .marks-table { width: 100%; border-collapse: collapse; background: #f9f9f9; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 30px;}
        .marks-table th, .marks-table td { padding: 14px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .marks-table th { background: #f3f4f6; font-weight: 600; }
        .marks-table tr:last-child td { border-bottom: none; }
        .action-btn { border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .action-btn.update { background: #22c55e; color: #fff; }
        .action-btn.delete { background: #ef4444; color: #fff; }
        .collapse-btn { background: #4f46e5; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-weight: 500; cursor: pointer; margin-bottom: 10px; }
        .student-list { display: none; margin-bottom: 30px; }
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
                <div class="teacher-id"> ID: TCH001</div>
            </div>
            <nav class="nav-menu" role="menu">
                <div class="nav-item"><a href="welcom.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></div>
                <div class="nav-item"><a href="students.php" class="nav-link"><i class="fas fa-user-graduate"></i> Students</a></div>
                <div class="nav-item"><a href="marks.php" class="nav-link active"><i class="fas fa-chart-line"></i> Marks</a></div>
                <div class="nav-item"><a href="attendance.php" class="nav-link"><i class="fas fa-calendar-check"></i> Attendance</a></div>
                <div class="nav-item"><a href="work_panel.php" class="nav-link"><i class="fas fa-tasks"></i> Work Panel</a></div>
                <div class="nav-item"><a href="notices.php" class="nav-link"><i class="fas fa-bullhorn"></i> Notices</a></div>
                <div class="nav-item"><a href="reports.php" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a></div>
                <div class="nav-item"><a href="profile.php" class="nav-link"><i class="fas fa-user-cog"></i> Profile</a></div>
           <div class="nav-item"><a href="teacher.php" class="nav-link"><i class="fas fa-user-cog"></i> Our Faculty</a></div>
           
           
            </nav>
        </div>
        <main class="main-content">
            
            
 


            <h2 class="section-title">Marks Entry & Check</h2>
            <div class="search-row">
                <form method="GET" action="marks.php" style="display:inline;">
                    <input type="text" name="search_reg_id" placeholder="Search by Registration ID" value="<?php echo isset($_GET['search_reg_id']) ? htmlspecialchars($_GET['search_reg_id']) : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i> Search</button>
                </form>
                <button type="button" onclick="toggleMarksForm()" id="showMarksFormBtn"><i class="fas fa-plus"></i> Upload Marks</button>
            </div>
            <form class="marks-form" id="marksForm" action="marks.php" method="POST">
                <label for="reg_id">Registration ID</label>
                <input type="text" id="reg_id" name="reg_id" required>
                <label for="department">Department</label>
                <input type="text" id="department" name="department" required>
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
                <label for="marks">Marks</label>
                <input type="number" id="marks" name="marks" required>
                <button type="submit" name="add_mark"><i class="fas fa-plus"></i> Upload Marks</button>
            </form>
            <button class="collapse-btn" type="button" onclick="toggleStudentList()">Student List with Marks</button>
            <div class="student-list" id="studentList">
                <table class="marks-table">
                    <thead>
                        <tr>
                            <th>Registration ID</th>
                            <th>Department</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($marks_list as $mark): ?>
                            <tr>
                                <form method="POST" action="marks.php">
                                    <td><?php echo htmlspecialchars($mark['reg_id']); ?></td>
                                    <td><?php echo htmlspecialchars($mark['department']); ?></td>
                                    <td><?php echo htmlspecialchars($mark['subject']); ?></td>
                                    <td>
                                        <input type="number" name="marks" value="<?php echo htmlspecialchars($mark['marks']); ?>" style="width:70px;">
                                        <input type="hidden" name="mark_id" value="<?php echo $mark['id']; ?>">
                                    </td>
                                    <td>
                                        <button type="submit" name="update_mark" class="action-btn update"><i class="fas fa-save"></i> Update</button>
                                        <button type="submit" name="delete_mark" class="action-btn delete" onclick="return confirm('Delete this mark?')"><i class="fas fa-trash"></i> Delete</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script>
        function toggleMarksForm() {
            var form = document.getElementById('marksForm');
            form.style.display = (form.style.display === 'block') ? 'none' : 'block';
        }
        function toggleStudentList() {
            var list = document.getElementById('studentList');
            list.style.display = (list.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</body>
</html>