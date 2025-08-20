<?php
include 'connection.php';

// ----------------------
// HANDLE MANUAL ATTENDANCE SUBMISSION
// ----------------------
if (isset($_POST['submit_attendance'])) {
    $date       = $_POST['attendance_date'];
    $time_slot  = $_POST['time_slot'];
    $department = $_POST['department'];
    $subject    = $_POST['subject'];
    $section    = $_POST['section'];
    $attendance = isset($_POST['attendance']) ? $_POST['attendance'] : [];

    foreach ($_POST['reg_ids'] as $reg_id) {
        $status = isset($attendance[$reg_id]) ? "Present" : "Absent";
        $stmt = $conn->prepare("SELECT id FROM attendance WHERE reg_id=? AND date=? AND time_slot=? AND department=? AND subject=? AND section=?");
        $stmt->bind_param("ssssss", $reg_id, $date, $time_slot, $department, $subject, $section);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            $stmt2 = $conn->prepare("UPDATE attendance SET status=? WHERE reg_id=? AND date=? AND time_slot=? AND department=? AND subject=? AND section=?");
            $stmt2->bind_param("sssssss", $status, $reg_id, $date, $time_slot, $department, $subject, $section);
            $stmt2->execute();
            $stmt2->close();
        } else {
            $stmt->close();
            $stmt2 = $conn->prepare("INSERT INTO attendance (reg_id, date, time_slot, department, subject, section, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("sssssss", $reg_id, $date, $time_slot, $department, $subject, $section, $status);
            $stmt2->execute();
            $stmt2->close();
        }
    }
    header("Location: attendance.php");
    exit();
}

// ----------------------
// HANDLE RFID ATTENDANCE FROM SERIAL/PYTHON
// ----------------------
if (isset($_POST['serial_rfid'])) {
    $uid    = $_POST['uid'];
    $name   = $_POST['name'];
    $status = $_POST['status'];
    if ($uid && $name && $status) {
        $stmt = $conn->prepare("INSERT INTO attendance_rfid (uid, name, status, timestamp) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $uid, $name, $status);
        $stmt->execute();
        $stmt->close();
        echo "RFID attendance saved";
        exit();
    } else {
        echo "Invalid RFID data";
        exit();
    }
}

// ----------------------
// FILTER STUDENTS
// ----------------------
$students = [];
$where    = [];
$params   = [];
$types    = '';

if (!empty($_POST['department'])) {
    $where[]   = "department = ?";
    $params[]  = $_POST['department'];
    $types    .= 's';
}
if (!empty($_POST['subject'])) {
    $where[]   = "subject = ?";
    $params[]  = $_POST['subject'];
    $types    .= 's';
}
if (!empty($_POST['section'])) {
    $where[]   = "section = ?";
    $params[]  = $_POST['section'];
    $types    .= 's';
}

$sql = "SELECT * FROM students";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql   .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
$stmt->close();

// ----------------------
// FETCH EXISTING ATTENDANCE RECORDS (for filtering)
// ----------------------
$attendance_records = [];
if (
    isset($_POST['attendance_date']) &&
    isset($_POST['time_slot']) &&
    isset($_POST['department']) &&
    isset($_POST['subject']) &&
    isset($_POST['section'])
) {
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE date=? AND time_slot=? AND department=? AND subject=? AND section=?");
    $stmt->bind_param(
        "sssss",
        $_POST['attendance_date'],
        $_POST['time_slot'],
        $_POST['department'],
        $_POST['subject'],
        $_POST['section']
    );
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $attendance_records[$row['reg_id']] = $row['status'];
    }
    $stmt->close();
}

// ----------------------
// GET ATTENDANCE STATISTICS
// ----------------------
$total_students_result   = $conn->query("SELECT COUNT(*) as total FROM students");
$total_students          = $total_students_result->fetch_assoc()['total'];

$today            = date('Y-m-d');
$today_attendance = $conn->query("SELECT COUNT(DISTINCT reg_id) as present FROM attendance WHERE date='$today' AND status='Present'");
$today_present    = $today_attendance->fetch_assoc()['present'];

$total_attendance = $conn->query("SELECT COUNT(*) as total FROM attendance WHERE date='$today'");
$today_total_records = $total_attendance->fetch_assoc()['total'];

$avg_attendance = $total_students > 0 ? round(($today_present / $total_students) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Attendance Management System</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  
   <style>
 
    body { 
      font-family: 'Inter', 'Segoe UI', sans-serif; 
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
      min-height: 100vh; 
      color: #333; 
    }
    .container { display: flex; min-height: 100vh; }

    /* Sidebar */
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
    .teacher-avatar img { width: 100%; height: 100%; object-fit: cover; }
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
    .nav-link i { margin-right: 12px; font-size: 1.1em; width: 20px; text-align: center; }

    /* Main Content */
    .main-content { flex: 1; margin-left: 280px; padding: 30px; }
    .section-title { 
      font-size: 1.7em; 
      font-weight: 700; 
      margin-bottom: 25px; 
      letter-spacing: 1px; 
      text-align: center;
    }

    /* Mode Selection Buttons */
    .mode-selector {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
      justify-content: center;
    }
    .mode-btn {
      padding: 15px 30px;
      border: none;
      border-radius: 15px;
      font-size: 1.1em;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .mode-btn.manual {
      background-color: white; 
      color: black; 
      border: 2px solid #008CBA;
      transition-duration: 0.4s;
    }
    .mode-btn.rfid {
      background: #48ff48;
      color: black;
    }
    .mode-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Filter & Form Styles */
    .filter-form { 
      display: flex; 
      flex-wrap: wrap;
      gap: 18px; 
      margin-bottom: 30px; 
      background: rgba(255,255,255,0.95); 
      padding: 18px 20px; 
      border-radius: 15px; 
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    }
    .filter-form select, 
    .filter-form input[type="date"] { 
      padding: 10px; 
      border-radius: 8px; 
      border: 1px solid #e5e7eb; 
      font-size: 1em; 
      background: #f3f4f6; 
      min-width: 140px; 
    }
    .filter-form button { 
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
    .filter-form button:hover { 
      background: linear-gradient(135deg, #7c3aed, #4f46e5); 
    }

    /* Attendance Table */
    .attendance-table { 
      width: 100%; 
      border-collapse: collapse; 
      background: rgba(255,255,255,0.98); 
      border-radius: 15px; 
      box-shadow: 0 4px 20px rgba(0,0,0,0.08); 
      overflow: hidden; 
      margin-bottom: 30px;
    }
    .attendance-table th, .attendance-table td { 
      padding: 14px; 
      border-bottom: 1px solid #e5e7eb; 
      text-align: left; 
    }
    .attendance-table th { 
      background: #f3f4f6; 
      font-weight: 600; 
      color: #4f46e5; 
      letter-spacing: 0.5px; 
    }
    .attendance-table tr:last-child td { 
      border-bottom: none; 
    }
    .attendance-table input[type="checkbox"] { 
      transform: scale(1.3); 
      margin-right: 8px; 
    }
    .submit-btn { 
      background: linear-gradient(135deg, #22c55e, #16a34a); 
      color: #fff; 
      border: none; 
      padding: 12px 28px; 
      border-radius: 8px; 
      font-weight: 600; 
      cursor: pointer; 
      font-size: 1.1em; 
      box-shadow: 0 2px 8px rgba(34,197,94,0.08); 
      margin-top: 10px; 
      transition: background 0.2s; 
    }
    .submit-btn:hover { 
      background: linear-gradient(135deg, #16a34a, #22c55e); 
    }

    /* RFID & Manual Sections */
    .rfid-container {
      display: none;
      background: rgba(255,255,255,0.95);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
      margin-bottom: 30px;
    }
    .rfid-container.active {
      display: block;
    }
    .manual-container {
      display: block;
    }
    .manual-container.hidden {
      display: none;
    }

    /* Hide Sensitive RFID Controls */
    .rfid-controls { display: none; }

    /* Generic Control Styles */
    .controls {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 30px;
      align-items: center;
    }
    .control-group {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .control-group label {
      font-weight: 600;
      color: #333;
      font-size: 0.9rem;
    }
    .control-group input, 
    .control-group select {
      padding: 10px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }
    .control-group input:focus, 
    .control-group select:focus {
      outline: none;
      border-color: #667eea;
    }

    /* Table Container */
    .table-container {
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
    .table-header {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 20px;
      font-size: 1.2rem;
      font-weight: 600;
    }

    /* Loading & Spinner */
    .loading {
      text-align: center;
      padding: 40px;
      color: #666;
    }
    .spinner {
      display: inline-block;
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #667eea;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Status Styling */
    .status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      text-align: center;
      display: inline-block;
      min-width: 80px;
    }
    .status-success { background: #d4edda; color: #155724; }
    .status-duplicate { background: #fff3cd; color: #856404; }
    .status-unauthorized { background: #f8d7da; color: #721c24; }

    /* Responsive Adjustments */
    @media (max-width: 900px) {
      .main-content { margin-left: 0; padding: 15px; }
      .sidebar { position: static; width: 100%; }
      .filter-form { flex-direction: column; gap: 10px; }
      .mode-selector { flex-direction: column; align-items: center; }
      .stats-grid { grid-template-columns: 1fr; }
    }
  
   
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar Navigation -->
    <div class="sidebar" role="navigation" aria-label="Teacher Portal Navigation">
      <div class="teacher-profile">
        <div class="teacher-avatar">
          <img src="https://scontent.fdac138-1.fna.fbcdn.net/v/t39.30808-6/476630836_1342214043451264_5083001827614943380_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEZmvzZy6F7jgGqDPq788s7hTEhiCE-5tWFMSGIIT7m1ZD4EF5He0XQFfWnnT-4E32bKQ4wVjCphIpYxjvdna9m&_nc_ohc=O7Eibxr1F7gQ7kNvwE05Zuv&_nc_oc=Adnp6JMAnvupjyo9TsYGjf28jcUsa2vu03w01W6K7_UXjqjfPj86WYVG_zYoodvK_Ag&_nc_zt=23&_nc_ht=scontent.fdac138-1.fna&_nc_gid=z7pJABwRyTCdjMp3ZlyILA&oh=00_AfUUEDZUia2fU0EBbNOjZMmtOhSWChyPbF6Mm4kukJXtIw&oe=68AA72B5" alt="Profile picture of Md. Mehedi Hasan" />
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
        <div class="nav-item"><a href="attendance.php" class="nav-link active"><i class="fas fa-calendar-check"></i> Attendance</a></div>
        <div class="nav-item"><a href="work_panel.php" class="nav-link"><i class="fas fa-tasks"></i> Work Panel</a></div>
        <div class="nav-item"><a href="notices.php" class="nav-link"><i class="fas fa-bullhorn"></i> Notices</a></div>
        <div class="nav-item"><a href="reports.php" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a></div>
        <div class="nav-item"><a href="profile.php" class="nav-link"><i class="fas fa-user-cog"></i> Profile</a></div>
        <div class="nav-item"><a href="teacher.php" class="nav-link"><i class="fas fa-user-cog"></i> Our Faculty</a></div>
      </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
      <h2 class="section-title">Attendance Management System</h2>
      
      <!-- Mode Selection Buttons -->
      <div class="mode-selector">
        <button class="mode-btn manual" onclick="showManualMode()">
          <i class="fas fa-clipboard-list"></i>
          Manual Attendance
        </button>
        <button class="mode-btn rfid" onclick="showRFIDMode()">
          <i class="fas fa-wifi"></i>
          RFID Attendance
        </button>
      </div>

      <!-- Manual Attendance Section -->
      <div id="manualSection" class="manual-container">
        <form class="filter-form" method="POST" action="attendance.php">
          <select name="time_slot" required>
            <option value="">Time Slot</option>
            <option value="8:00-9:00" <?php if(isset($_POST['time_slot']) && $_POST['time_slot']=="8:00-9:00") echo "selected"; ?>>8:00-9:00</option>
            <option value="9:00-10:00" <?php if(isset($_POST['time_slot']) && $_POST['time_slot']=="9:00-10:00") echo "selected"; ?>>9:00-10:00</option>
            <option value="10:00-11:00" <?php if(isset($_POST['time_slot']) && $_POST['time_slot']=="10:00-11:00") echo "selected"; ?>>10:00-11:00</option>
            <option value="11:00-12:00" <?php if(isset($_POST['time_slot']) && $_POST['time_slot']=="11:00-12:00") echo "selected"; ?>>11:00-12:00</option>
            <option value="12:00-1:00" <?php if(isset($_POST['time_slot']) && $_POST['time_slot']=="12:00-1:00") echo "selected"; ?>>12:00-1:00</option>
          </select>
          <input type="date" name="attendance_date" required value="<?php echo isset($_POST['attendance_date']) ? $_POST['attendance_date'] : date('Y-m-d'); ?>">
          <select name="department" required>
            <option value="">Department</option>
            <option value="CSE" <?php if(isset($_POST['department']) && $_POST['department']=="CSE") echo "selected"; ?>>CSE</option>
            <option value="EEE" <?php if(isset($_POST['department']) && $_POST['department']=="EEE") echo "selected"; ?>>EEE</option>
            <option value="CIS" <?php if(isset($_POST['department']) && $_POST['department']=="CIS") echo "selected"; ?>>CIS</option>
            <option value="MCT" <?php if(isset($_POST['department']) && $_POST['department']=="MCT") echo "selected"; ?>>MCT</option>
          </select>
          <select name="subject" required>
            <option value="">Subject</option>
            <option value="Database" <?php if(isset($_POST['subject']) && $_POST['subject']=="Database") echo "selected"; ?>>Database</option>
            <option value="Computer Network" <?php if(isset($_POST['subject']) && $_POST['subject']=="Computer Network") echo "selected"; ?>>Computer Network</option>
            <option value="Web Engineering" <?php if(isset($_POST['subject']) && $_POST['subject']=="Web Engineering") echo "selected"; ?>>Web Engineering</option>
            <option value="Math101" <?php if(isset($_POST['subject']) && $_POST['subject']=="Math101") echo "selected"; ?>>Math101</option>
          </select>
          <select name="section" required>
            <option value="">Section</option>
            <option value="A" <?php if(isset($_POST['section']) && $_POST['section']=="A") echo "selected"; ?>>A</option>
            <option value="B" <?php if(isset($_POST['section']) && $_POST['section']=="B") echo "selected"; ?>>B</option>
            <option value="C" <?php if(isset($_POST['section']) && $_POST['section']=="C") echo "selected"; ?>>C</option>
            <option value="D" <?php if(isset($_POST['section']) && $_POST['section']=="D") echo "selected"; ?>>D</option>
          </select>
          <button type="submit"><i class="fas fa-filter"></i> Filter Students</button>
        </form>

        <?php if (!empty($students)): ?>
          <form method="POST" action="attendance.php">
            <input type="hidden" name="attendance_date" value="<?php echo isset($_POST['attendance_date']) ? $_POST['attendance_date'] : date('Y-m-d'); ?>">
            <input type="hidden" name="time_slot" value="<?php echo isset($_POST['time_slot']) ? $_POST['time_slot'] : ''; ?>">
            <input type="hidden" name="department" value="<?php echo isset($_POST['department']) ? $_POST['department'] : ''; ?>">
            <input type="hidden" name="subject" value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : ''; ?>">
            <input type="hidden" name="section" value="<?php echo isset($_POST['section']) ? $_POST['section'] : ''; ?>">
            <table class="attendance-table">
              <thead>
                <tr>
                  <th>Reg ID</th>
                  <th>Name</th>
                  <th>Present</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($students as $student): ?>
                  <tr>
                    <td>
                      <?php echo htmlspecialchars($student['reg_id']); ?>
                      <input type="hidden" name="reg_ids[]" value="<?php echo htmlspecialchars($student['reg_id']); ?>">
                    </td>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td>
                      <input type="checkbox" name="attendance[<?php echo $student['reg_id']; ?>]" value="Present"
                      <?php echo (isset($attendance_records[$student['reg_id']]) && $attendance_records[$student['reg_id']] == "Present") ? "checked" : ""; ?>>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <button type="submit" name="submit_attendance" class="submit-btn"><i class="fas fa-check"></i> Submit Attendance</button>
          </form>
        <?php endif; ?>

        <?php if (!empty($attendance_records)): ?>
          <table class="attendance-table">
            <thead>
              <tr>
                <th>Reg ID</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($attendance_records as $reg_id => $status): ?>
                <tr>
                  <td><?php echo htmlspecialchars($reg_id); ?></td>
                  <td><?php echo htmlspecialchars($status); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

      <!-- RFID Attendance Section (Local MySQL) -->
      <div id="rfidSection" class="rfid-container">
        <div class="table-container">
          <div class="table-header">
            📋 RFID Attendance Records (Local Server)
          </div>
          <table class="attendance-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Card UID</th>
                <th>Name</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $conn->query("SELECT * FROM attendance_rfid ORDER BY timestamp DESC LIMIT 100");
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $date = date('Y-m-d', strtotime($row['timestamp']));
                  $time = date('H:i:s', strtotime($row['timestamp']));
                  echo "<tr>
                    <td>{$date}</td>
                    <td>{$time}</td>
                    <td>{$row['uid']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
                }
              } else {
                echo "<tr><td colspan='5'>No RFID attendance records found.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
  <script>
    function showManualMode() {
      document.getElementById('manualSection').classList.remove('hidden');
      document.getElementById('rfidSection').classList.remove('active');
      document.querySelector('.mode-btn.manual').style.opacity = '1';
      document.querySelector('.mode-btn.rfid').style.opacity = '0.7';
    }
    function showRFIDMode() {
      document.getElementById('manualSection').classList.add('hidden');
      document.getElementById('rfidSection').classList.add('active');
      document.querySelector('.mode-btn.manual').style.opacity = '0.7';
      document.querySelector('.mode-btn.rfid').style.opacity = '1';
    }
    // Start in Manual Mode
    showManualMode();
  </script>
</body>
</html>