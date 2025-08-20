<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Teacher Portal - Profile</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
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

    .sidebar {
      width: 280px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
      position: fixed;
      height: 100vh;
      overflow-y: auto;
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
    }

    .teacher-title {
      font-size: 0.9em;
      opacity: 0.9;
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

    .header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 20px 30px;
      border-radius: 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
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

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
    }

    .profile-section {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .profile-info {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      align-items: center;
    }

    .profile-picture {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      overflow: hidden;
      border: 4px solid rgba(79, 70, 229, 0.3);
    }

    .profile-picture img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-details {
      flex: 1;
    }

    .profile-details h2 {
      font-size: 1.6em;
      margin-bottom: 5px;
      color: #1f2937;
    }

    .profile-details p {
      margin: 5px 0;
      color: #4b5563;
    }

    .edit-profile-btn {
      margin-top: 20px;
      display: inline-block;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        transform: translateX(-100%);
        position: fixed;
        z-index: 1000;
      }

      .main-content {
        margin-left: 0;
        padding: 20px;
      }

      .header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
      }

      .header-actions {
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="teacher-profile">
        <div class="teacher-avatar">
          <img src="https://scontent.fdac138-1.fna.fbcdn.net/v/t39.30808-6/476630836_1342214043451264_5083001827614943380_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEZmvzZy6F7jgGqDPq788s7hTEhiCE-5tWFMSGIIT7m1ZD4EF5He0XQFfWnnT-4E32bKQ4wVjCphIpYxjvdna9m&_nc_ohc=O7Eibxr1F7gQ7kNvwE05Zuv&_nc_oc=Adnp6JMAnvupjyo9TsYGjf28jcUsa2vu03w01W6K7_UXjqjfPj86WYVG_zYoodvK_Ag&_nc_zt=23&_nc_ht=scontent.fdac138-1.fna&_nc_gid=z7pJABwRyTCdjMp3ZlyILA&oh=00_AfUUEDZUia2fU0EBbNOjZMmtOhSWChyPbF6Mm4kukJXtIw&oe=68AA72B5" alt="Md. Mehedi Hasan" />
        </div>
        <div class="teacher-name">Md. Mehedi Hasan</div>
        <div class="teacher-title">Director</div>
        <div class="teacher-title">Mehedi's IoT Academy</div>
        <div class="teacher-id">ID: TCH001</div>
      </div>
      <nav class="nav-menu">
        <div class="nav-item"><a href="http://localhost/phplogin/welcom.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></div>
        <div class="nav-item"><a href="students.php" class="nav-link"><i class="fas fa-user-graduate"></i> Students</a></div>
        <div class="nav-item"><a href="marks.php" class="nav-link"><i class="fas fa-chart-line"></i> Marks</a></div>
        <div class="nav-item"><a href="attendance.php" class="nav-link"><i class="fas fa-calendar-check"></i> Attendance</a></div>
        <div class="nav-item"><a href="work_panel.php" class="nav-link"><i class="fas fa-tasks"></i> Work Panel</a></div>
        <div class="nav-item"><a href="notices.php" class="nav-link"><i class="fas fa-bullhorn"></i> Notices</a></div>
        <div class="nav-item"><a href="reports.php" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a></div>
        <div class="nav-item"><a href="profile.php" class="nav-link active"><i class="fas fa-user-cog"></i> Profile</a></div>
        <div class="nav-item"><a href="teacher.php" class="nav-link"><i class="fas fa-user-cog"></i> Our Faculty</a></div>
      </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
      <header class="header">
        <div>
          <h1 class="header-title">Profile</h1>
          <p class="header-subtitle">Manage your profile information</p>
        </div>
        <div class="header-actions">
          <a href="http://localhost/phplogin/index.php" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </header>

      <section class="profile-section">
        <div class="profile-info">
          <div class="profile-picture">
            <img src="https://scontent.fdac138-1.fna.fbcdn.net/v/t39.30808-6/476630836_1342214043451264_5083001827614943380_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEZmvzZy6F7jgGqDPq788s7hTEhiCE-5tWFMSGIIT7m1ZD4EF5He0XQFfWnnT-4E32bKQ4wVjCphIpYxjvdna9m&_nc_ohc=O7Eibxr1F7gQ7kNvwE05Zuv&_nc_oc=Adnp6JMAnvupjyo9TsYGjf28jcUsa2vu03w01W6K7_UXjqjfPj86WYVG_zYoodvK_Ag&_nc_zt=23&_nc_ht=scontent.fdac138-1.fna&_nc_gid=z7pJABwRyTCdjMp3ZlyILA&oh=00_AfUUEDZUia2fU0EBbNOjZMmtOhSWChyPbF6Mm4kukJXtIw&oe=68AA72B5" alt="Md. Mehedi Hasan" />
          </div>
          <div class="profile-details">
            <h2>Md. Mehedi Hasan</h2>
            <p><strong>Role:</strong> Director</p>
            <p><strong>Academy:</strong> Mehedi's IoT Academy</p>
            <p><strong>Email:</strong> mehed.iot.edu.bd</p>
            <p><strong>Teacher ID:</strong> TCH001</p>
            <a href="edit_profile.php" class="btn btn-primary edit-profile-btn"><i class="fas fa-edit"></i> Edit Profile</a>
          </div>
        </div>
      </section>
 

      
    </main>
  </div>
</body>
</html>
